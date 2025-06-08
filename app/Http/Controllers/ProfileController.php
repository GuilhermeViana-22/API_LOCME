<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profiles\CompletarRequest;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
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
    public function completar( CompletarRequest  $request)
    {
        $user = Auth::user();
        $user->fill($request->validated());

        try {
            $user->save();

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
}
