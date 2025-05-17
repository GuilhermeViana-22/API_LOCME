<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
 /**
     * Exibe os registros de logs do sistema.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function log()
    {
        try {
            // Recupera todos os logs do banco de dados, ordenados pela data de criação
            $logs = Log::orderBy('created_at', 'desc')->get();

            // Retorna os logs em formato JSON
            return response()->json([
                'success' => true,
                'data' => $logs
            ], 200);
        } catch (\Exception $e) {
            // Retorna erro em caso de falha ao buscar os logs
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar logs.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
