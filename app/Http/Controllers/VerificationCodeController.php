<?php

namespace App\Http\Controllers;

use App\Http\Requests\CodeRequest;
use Illuminate\Support\Facades\DB;
use App\Models\VerificationCode;
use Illuminate\Http\Response;
use App\Models\User;
class VerificationCodeController extends Controller
{
    public function verifyCode(CodeRequest $request)
    {
        // Buscar o código de verificação no banco de dados
        $verificationCode = VerificationCode::where('code', 'like', '%'. $request->code . '%')
            ->first();

        if ($verificationCode) {
            // Código válido, prosseguir para gerar o token de acesso
            DB::beginTransaction();

            try {
                // Encontrar o usuário pelo ID
                $user = User::findOrFail( $verificationCode->user_id );

                // Atualizar as colunas 'active' e 'situacao_id' para 1
                $user->update([
                    'active' => User::USUARIO_ATIVO,
                    'situacao_id' => User::SITUACAO_ATIVA,
                ]);

                // Gerar o token de acesso
                $token = $user->createToken('LaravelAuthApp')->accessToken;
                $verificationCode->delete();

                DB::commit();
                return response()->json(['token' => $token], Response::HTTP_OK);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Erro ao gerar o token de acesso. Por favor, tente novamente. ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return response()->json(['error' => 'Código de verificação inválido.'], Response::HTTP_BAD_REQUEST);
        }
    }
}
