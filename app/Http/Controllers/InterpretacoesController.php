<?php

namespace App\Http\Controllers;

use App\Models\Interpretacoes;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInterpretacoesRequest;
use App\Http\Requests\UpdateInterpretacoesRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InterpretacoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $interpretacoes = Interpretacoes::with(['user'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Interpretações listadas com sucesso',
                'data' => $interpretacoes
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao listar interpretações',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreInterpretacoesRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreInterpretacoesRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();
            $data['job'] = $this->generateJobNumber();
            $data['status'] = 'pendente';

            // Processar arquivo (XML ou JSON)
            if ($request->hasFile('xml_file')) {
                $data['xml'] = $this->storeFile($request->file('xml_file'), 'xml');
                $data['status'] = $this->validateXml($data['xml']) ? 'processado' : 'erro';
            }

            if ($request->hasFile('json_file')) {
                $data['json'] = $this->storeFile($request->file('json_file'), 'json');
                $data['status'] = $this->validateJson($data['json']) ? 'processado' : 'erro';
            }

            $interpretacao = Interpretacoes::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Interpretação criada com sucesso',
                'data' => $interpretacao,
                'job_number' => $data['job']
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao criar interpretação',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Interpretacoes  $interpretacao
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Interpretacoes $interpretacao)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Interpretação recuperada com sucesso',
                'data' => $interpretacao->load(['user'])
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao recuperar interpretação',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInterpretacoesRequest  $request
     * @param  \App\Models\Interpretacoes  $interpretacao
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateInterpretacoesRequest $request, Interpretacoes $interpretacao)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id(); // Registrar quem fez a alteração

            if ($request->hasFile('xml_file')) {
                // Remove o arquivo antigo se existir
                if ($interpretacao->xml) {
                    Storage::delete($interpretacao->xml);
                }
                $data['xml'] = $this->storeFile($request->file('xml_file'), 'xml');
                $data['status'] = $this->validateXml($data['xml']) ? 'processado' : 'erro';
            }

            if ($request->hasFile('json_file')) {
                // Remove o arquivo antigo se existir
                if ($interpretacao->json) {
                    Storage::delete($interpretacao->json);
                }
                $data['json'] = $this->storeFile($request->file('json_file'), 'json');
                $data['status'] = $this->validateJson($data['json']) ? 'processado' : 'erro';
            }

            $interpretacao->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Interpretação atualizada com sucesso',
                'data' => $interpretacao
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao atualizar interpretação',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Interpretacoes  $interpretacao
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Interpretacoes $interpretacao)
    {
        try {
            // Remove arquivos associados
            if ($interpretacao->xml) {
                Storage::delete($interpretacao->xml);
            }
            if ($interpretacao->json) {
                Storage::delete($interpretacao->json);
            }

            $interpretacao->delete();

            return response()->json([
                'success' => true,
                'message' => 'Interpretação removida com sucesso'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao remover interpretação',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Busca interpretação por número de job
     *
     * @param int $job
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchByJob($job)
    {
        try {
            $interpretacao = Interpretacoes::where('job', $job)->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Interpretação encontrada',
                'data' => $interpretacao
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhuma interpretação encontrada com este job',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Gera número de job incremental
     *
     * @return int
     */
    private function generateJobNumber()
    {
        $lastJob = Interpretacoes::orderBy('job', 'desc')->first();
        return $lastJob ? $lastJob->job + 1 : 1000;
    }

    /**
     * Armazena arquivo no sistema
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $type
     * @return string
     */
    private function storeFile($file, $type)
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs("interpretacoes/{$type}", $filename);
    }

    /**
     * Valida arquivo XML
     *
     * @param string $filePath
     * @return bool
     */
    private function validateXml($filePath)
    {
        try {
            $content = Storage::get($filePath);
            simplexml_load_string($content);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Valida arquivo JSON
     *
     * @param string $filePath
     * @return bool
     */
    private function validateJson($filePath)
    {
        try {
            $content = Storage::get($filePath);
            json_decode($content);
            return json_last_error() === JSON_ERROR_NONE;
        } catch (\Exception $e) {
            return false;
        }
    }
}
