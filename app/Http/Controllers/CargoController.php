<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\Cargos\CargoStoreRequest;
use App\Http\Requests\Cargos\CargoUpdateRequest;

class CargoController extends Controller
{
    public function index()
    {
        try {
            $cargos = Cargo::all();

            return response()->json([
                'success' => true,
                'message' => 'Cargos recuperados com sucesso',
                'data' => $cargos
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao recuperar cargos',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function store(CargoStoreRequest $request)
    {
        try {
            $cargo = Cargo::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Cargo criado com sucesso',
                'data' => $cargo
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao criar cargo',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function show($id)
    {
        try {
            $cargo = Cargo::find($id);

            if (!$cargo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cargo não encontrado'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'message' => 'Cargo recuperado com sucesso',
                'data' => $cargo
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao recuperar cargo',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function update(CargoUpdateRequest $request, $id)
    {
        try {
            $cargo = Cargo::find($id);

            if (!$cargo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cargo n�o encontrado'
                ], Response::HTTP_NOT_FOUND);
            }

            $cargo->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Cargo atualizado com sucesso',
                'data' => $cargo
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao atualizar cargo',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

   
    public function destroy($id)
    {
        try {
            $cargo = Cargo::find($id);

            if (!$cargo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cargo não encontrado'
                ], Response::HTTP_NOT_FOUND);
            }

            $cargo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cargo removido com sucesso'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Falha ao remover cargo',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
