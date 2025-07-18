<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profiles\CompletarRequest;
use App\Http\Resources\Perfis\TiposPerfisResource;
use App\Http\Resources\Users\UserResource;
use App\Models\AgenciaViagem;
use App\Models\AgenteViagem;
use App\Models\Empresa;
use App\Models\GuiaTurismo;
use App\Models\Log;
use App\Models\PerfilAtividade;
use App\Models\PerfilProdutoServico;
use App\Models\PerfilUnidadeLocalidade;
use App\Models\Representante;
use App\Models\TipoPerfil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/profile",
     *     tags={"Perfil"},
     *     summary="Retorna os dados do usuário autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dados do usuário autenticado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="João da Silva"),
     *             @OA\Property(property="email", type="string", example="joao@example.com"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autenticado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor"
     *     )
     * )
     */
    public function show()
    {
        $user = Auth::user();

        return new UserResource($user);
    }

    /**
     * @OA\Post(
     *     path="/api/profile/completar",
     *     tags={"Perfil"},
     *     summary="Completa o perfil do usuário com base no seu tipo",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados do perfil (variam conforme o tipo)",
     *         @OA\JsonContent(
     *             type="object",
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/RepresentanteData"),
     *                 @OA\Schema(ref="#/components/schemas/AgenteViagemData"),
     *                 @OA\Schema(ref="#/components/schemas/AgenciaViagemData")
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Perfil salvo com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Os dados foram salvos com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erros de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Os dados fornecidos são inválidos"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"campo": {"Mensagem de erro para o campo"}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno no servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object"),
     *             @OA\Property(property="message", type="string", example="Erro durante o salvamento dos dados"),
     *             @OA\Property(property="log", type="string")
     *         )
     *     )
     * )
     *
     * Método que realiza a inclusão das informações do perfil do usuário
     *
     * @param CompletarRequest $request
     * @return JsonResponse
     */
    public function completar(CompletarRequest $request)
    {
        $user = Auth::user();

        // Inicia a transação do banco de dados
        DB::beginTransaction();

        try {
            // Verifica o tipo de perfil e direciona para o método específico
            switch ($user->tipo_perfil_id) {
                case TipoPerfil::TIPO_REPRESENTANTE:
                    $perfil = $this->salvarPerfilRepresentante($user, $request);
                    break;

                case TipoPerfil::TIPO_AGENTE_VIAGEM:
                    $perfil = $this->salvarPerfilAgenteViagem($user, $request);
                    break;

                case TipoPerfil::TIPO_AGENCIA_VIAGEM:
                    $perfil = $this->salvarPerfilAgenciaViagem($user, $request);
                    break;
                case TipoPerfil::TIPO_GUIA_TURISMO:
                    $perfil = $this->salvarPerfilGuiaTurismo($user, $request);
                    break;
                case TipoPerfil::TIPO_EMPRESA_ENTIDADE:
                    $perfil = $this->salvarPerfilEmpresaEntidade($user, $request);
                    break;

                default:
                    throw new \Exception('Tipo de perfil não implementado ou inválido.');
            }

            // Commit da transação
            DB::commit();

            return response()->json([
                'message' => 'Os dados foram salvos com sucesso',
                'perfil_id' => $perfil->id ?? null
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Rollback em caso de erro de validação
            DB::rollBack();

            return response()->json([
                'errors' => $e->errors(),
                'message' => 'Erro de validação dos dados'
            ], 422);

        } catch (\Throwable $e) {
            // Rollback em caso de qualquer outro erro
            DB::rollBack();

            return response()->json([
                'errors' => [
                    'general' => ['Erro ao processar os dados']
                ],
                'message' => 'Erro durante o salvamento dos dados',
                'log' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Implementa a lógica de criação de perfil do representante e também salva o ID na tabela do USER
     *
     * @param $user
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    private function salvarPerfilRepresentante($user, $request)
    {
        try {
            // Os dados já foram validados pelo FormRequest
            $validatedData = $request->validated();

            $representante = Representante::create($validatedData);

            // Atualização do utilizador com o ‘ID’ do representante
            $user->perfil_id = $representante->id;
            $user->bio = $validatedData['bio'];

            $user->save();

            return $representante;
        } catch (\Illuminate\Database\QueryException $e) {
            // Tratar erros específicos de banco de dados
            throw new \Exception('Erro ao salvar no banco de dados: ' . $e->getMessage(), 500);
        } catch (\Exception $e) {
            throw new \Exception('Erro ao processar o perfil do representante: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Implementa a lógica de criação de perfil de agente de viagem e também salva o ID na tabela do USER
     *
     * @param $user
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    private function salvarPerfilAgenteViagem($user, $request)
    {
        try {
            // Os dados já foram validados pelo FormRequest
            $validatedData = $request->validated();

            // Criar o agente de viagem usando o modelo
            $agenteViagem = AgenteViagem::create($validatedData);

            // Atualizar o perfil do utilizador com o ‘ID’ do agente de viagem
            $user->perfil_id = $agenteViagem->id;
            $user->bio = $validatedData['bio'];
            $user->save();

            return $agenteViagem;
        } catch (\Illuminate\Database\QueryException $e) {
            throw new \Exception('Erro ao salvar no banco de dados: ' . $e->getMessage(), 500);
        } catch (\Exception $e) {
            throw new \Exception('Erro ao processar o perfil do agente de viagem: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Implementa a lógica de criação de perfil de agência de viagem e também salva o ID na tabela do USER
     *
     * @param $user
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    private function salvarPerfilAgenciaViagem($user, $request)
    {
        try {
            // Os dados já foram validados pelo FormRequest
            $validatedData = $request->validated();

            // Processar upload de logo se existir no request
            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                $logoFileName = time() . '_' . $logoFile->getClientOriginalName();
                $logoPath = $logoFile->storeAs('agencias/logos', $logoFileName, 'public');
                $validatedData['logo_path'] = $logoPath;
            }

            // Criar a agência de viagem usando o modelo
            $agenciaViagem = AgenciaViagem::create($validatedData);

            // Atualizar o perfil do utilizador com o ‘ID’ da agência de viagem
            $user->perfil_id = $agenciaViagem->id;
            $user->save();

            return $agenciaViagem;
        } catch (\Illuminate\Database\QueryException $e) {
            throw new \Exception('Erro ao salvar no banco de dados: ' . $e->getMessage(), 500);
        } catch (\Exception $e) {
            throw new \Exception('Erro ao processar o perfil da agência: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Implementa a lógica de criação de perfil de guia de turismo e também salva o ID na tabela do USER
     *
     * @param $user
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    private function salvarPerfilGuiaTurismo($user, $request)
    {
        try {
            // Os dados já foram validados pelo FormRequest
            $validatedData = $request->validated();

            // Criar o guia usando o modelo
            $guiaTurismo = GuiaTurismo::create($validatedData);

            // Atualizar o perfil do utilizador com o ‘ID’ da agência de viagem
            $user->perfil_id = $guiaTurismo->id;
            $user->bio = $validatedData['bio'];
            $user->save();

            return $guiaTurismo;
        } catch (\Illuminate\Database\QueryException $e) {
            throw new \Exception('Erro ao salvar no banco de dados: ' . $e->getMessage(), 500);
        } catch (\Exception $e) {
            throw new \Exception('Erro ao processar o perfil da agência: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Implementa a lógica de criação de perfil da empresa/entidade e também salva o ID na tabela do USER
     *
     * @param $user
     * @param $request
     * @return mixed
     * @throws \Exception
     */
    private function salvarPerfilEmpresaEntidade($user, $request)
    {
        try {
            // Os dados já foram validados pelo FormRequest
            $validatedData = $request->validated();

            $empresaEntidade = Empresa::create($validatedData);

            // Atualização do utilizador com o ‘ID’ da empresa/entidade
            $user->perfil_id = $empresaEntidade->id;
            ///$user->bio = $validatedData['bio'];

            /// salva as atividades
            if(!empty($validatedData['atividades'])){
                foreach ( $validatedData['atividades'] as $atividade_id )
                {
                    PerfilAtividade::create([
                        'perfil_id' => $empresaEntidade->id,
                        'atividade_id' => $atividade_id,
                        'tipo_perfil_id' => TipoPerfil::TIPO_EMPRESA_ENTIDADE,
                    ]);
                }
            }

            /// salva os produtos serviços
            if(!empty($validatedData['produtos_servicos'])){
                foreach ( $validatedData['produtos_servicos'] as $produto_id )
                {
                    PerfilProdutoServico::create([
                        'perfil_id' => $empresaEntidade->id,
                        'produto_id' => $produto_id,
                        'tipo_perfil_id' => TipoPerfil::TIPO_EMPRESA_ENTIDADE,
                    ]);
                }
            }

            /// salva as unidades localidades
            if(!empty($validatedData['unidades_localidades'])){
                foreach ( $validatedData['unidades_localidades'] as $unidade_localidade_id )
                {
                    PerfilUnidadeLocalidade::create([
                        'perfil_id' => $empresaEntidade->id,
                        'unidade_localidade_id' => $unidade_localidade_id,
                        'tipo_perfil_id' => TipoPerfil::TIPO_EMPRESA_ENTIDADE,
                    ]);
                }
            }

            $user->save();

            return $empresaEntidade;
        } catch (\Illuminate\Database\QueryException $e) {
            // Tratar erros específicos de banco de dados
            throw new \Exception('Erro ao salvar no banco de dados: ' . $e->getMessage(), 500);
        } catch (\Exception $e) {
            throw new \Exception('Erro ao processar o perfil do empresa/entidade: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Método que realiza o upload da profile picture
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'foto_perfil' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = Auth::user();
        $userId = $user->id;

        if ($request->hasFile('foto_perfil')) {
            // Deletar foto anterior se existir
            if ($user->foto_perfil) {
                Storage::disk('public')->delete($user->foto_perfil);
            }

            // Gerar nome único para o arquivo
            $fileName = uniqid().'.'.$request->file('foto_perfil')->extension();

            // Salvar apenas o caminho relativo no banco
            $path = $request->file('foto_perfil')
                ->storeAs("profile/{$userId}", $fileName, 'public');

            // Salvar apenas o nome/caminho relativo no BD
            $user->foto_perfil = $fileName;
            $user->save();
        }

        return response()->json([
            'foto_perfil_url' => asset('storage/'.$path), // Retorna apenas o caminho relativo
            'message' => 'Foto de perfil atualizada com sucesso!'
        ]);
    }

    /**
     * Método para recuperar todas os tipos de perfis do usuário
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function tiposPerfis(Request $request)
    {
        $tipos_perfis = TipoPerfil::all();

        return TiposPerfisResource::collection($tipos_perfis);
    }
}
