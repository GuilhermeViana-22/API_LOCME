<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profiles\CompletarRequest;
use App\Http\Resources\Perfis\TiposPerfisResource;
use App\Http\Resources\Users\UserResource;
use App\Models\TipoPerfil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
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
     * Método que realiza a inclusão das informações do perfil do usuário
     *
     * @param CompletarRequest $request
     * @return JsonResponse
     */
    public function completar( CompletarRequest $request)
    {
        $user = Auth::user();

        try {

        //// implementa a o case com cada tipo de perfil
        switch ( $user->tipo_perfil_id ){
            case TipoPerfil::TIPO_REPRESENTANTE:
                $this->salvarPerfilRepresentante($user);

                /// outros perfis




        }

        } catch (\Throwable $e) {
            return response()->json([
                'errors' => [
                    'general' => ['Erro no banco de dados']
                ],
                'message' => 'Erro durante o salvamento dos dados',
                'log' => $e->getMessage()
            ], 500);
        }



        return response()->json(['message' => 'Os dados foram salvos com sucesso']);
    }

    /**
     * Implementa a lógica de criação de perfil do representante e também salva o ID na tabela do USER
     *
     * @param $user
     * @return void
     */
    private function salvarPerfilRepresentante($user)
    {

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
