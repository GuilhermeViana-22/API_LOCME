<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
class VerificationCodeController extends Controller
{
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        // Buscar o código de verificação no banco de dados
        $verificationCode = VerificationCode::where('code', $request->code)
            ->first();

        if ($verificationCode) {
            // Código válido, prosseguir para gerar o token de acesso
            DB::beginTransaction();

            try {
                // Encontrar o usuário pelo ID
                $user = User::findOrFail( $verificationCode->user_id );

                // Atualizar as colunas 'active' e 'situacao_id' para 1
                $user->update([
                    'active' => 1,
                    'situacao_id' => 1,
                ]);

                // Gerar o token de acesso
                $token = $user->createToken('LaravelAuthApp')->accessToken;

                // Excluir o código de verificação após usar
                $verificationCode->delete();

                DB::commit();
                return response()->json(['token' => $token], Response::HTTP_OK);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Erro ao gerar o token de acesso. Por favor, tente novamente. ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

        } else {
            // Código de verificação inválido
            return response()->json(['error' => 'Código de verificação inválido.'], Response::HTTP_BAD_REQUEST);
        }
    }
}
