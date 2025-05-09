<?php

namespace App\Http\Controllers;

use App\Http\Requests\CodeRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use App\Models\VerificationCode;
use Illuminate\Http\Response;
use App\Models\User;
use OpenApi\Annotations as OA;


class VerificationCodeController extends Controller
{

    public function verifyCode(CodeRequest $request)
    {
        // Buscar o código de verificação no banco de dados
        $verificationCode = VerificationCode::where('code', $request->get('code'))->first();

        if ($verificationCode) {
            // Código válido, prosseguir para gerar o token de acesso
            DB::beginTransaction();

            try {
                // Encontrar o usuário pelo ID
                $user = User::findOrFail($verificationCode->user_id);

                // Atualizar as colunas 'active' e 'situacao_id' para 1
                $user->update([
                    'active' => User::USUARIO_ATIVO,
                    'situacao_id' => User::SITUACAO_ATIVA,
                ]);

                // Gerar o token de acesso usando Laravel Passport
                try {
                    // Revoke any existing tokens
                    $user->tokens()->where('revoked', false)->update(['revoked' => true]);

                    // Criar um novo token para o usuário
                    $tokenResult = $user->createToken('Personal Access Token');
                    $token = $tokenResult->accessToken;
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['error' => 'Erro ao gerar o token de acesso. ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                DB::commit();
                return response()->json([
                    'user' => new UserResource($user),
                    'token' => $token,
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Erro ao encontrar o usuário. ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return response()->json(['error' => 'Código de verificação inválido.'], Response::HTTP_BAD_REQUEST);
        }
    }
}
