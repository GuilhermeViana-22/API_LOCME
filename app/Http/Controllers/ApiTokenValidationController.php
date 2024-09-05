<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Certifique-se de que o modelo está correto

class ApiTokenValidationController extends Controller
{
    /**
     * Valida se o token é válido para o usuário fornecido.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateToken(Request $request)
    {
        dd(1);
        $request->validate([
            'user_id' => 'required',
            'token' => 'required'
        ]);

        $userId = $request->get('user_id');
        $token = $request->get('token');

        // Buscar o token na tabela personal_access_tokens
        $tokenRecord = DB::table('personal_access_tokens')::where('tokenable_id', $userId)
            ->where('token', $token)
            ->first();

        if ($tokenRecord) {
            return response()->json([
                'authorized' => true,
                'message' => 'Token válido.'
            ], 200);
        }

        return response()->json(['error' => 'Token inválido ou não encontrado.'], 401);
    }
}
