<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Perfis\AgenteViagemResource;
use App\Models\AgenteViagem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class AgenteViagemController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/agentes-viagem",
     *     tags={"Agentes de Viagem"},
     *     summary="Lista todos os agentes de viagem",
     *     security={"bearerAuth":{}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de agentes de viagem",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/AgenteViagemData")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     )
     * )
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $agentes = AgenteViagem::all();
        return AgenteViagemResource::collection($agentes);
    }

    /**
     * @OA\Get(
     *     path="/api/agentes-viagem/{id}",
     *     tags={"Agentes de Viagem"},
     *     summary="Obtém os detalhes de um agente de viagem específico",
     *     security={"bearerAuth":{}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do agente de viagem",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do agente de viagem",
     *         @OA\JsonContent(ref="#/components/schemas/AgenteViagemData")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Agente de viagem não encontrado"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     )
     * )
     *
     * @param int $id
     * @return \App\Http\Resources\Perfis\AgenteViagemResource
     */
    public function show(int $id): AgenteViagemResource
    {
        $agente = AgenteViagem::findOrFail($id);
        return new AgenteViagemResource($agente);
    }

    /**
     * @OA\Get(
     *     path="/api/agentes-viagem/buscar",
     *     tags={"Agentes de Viagem"},
     *     summary="Busca agentes de viagem por nome, cidade ou estado",
     *     security={"bearerAuth":{}},
     *     @OA\Parameter(
     *         name="termo",
     *         in="query",
     *         required=true,
     *         description="Termo de busca",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de agentes de viagem encontrados",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/AgenteViagemData")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado"
     *     )
     * )
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function buscar(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'termo' => 'required|string|min:3'
        ]);

        $termo = $request->termo;

        $agentes = AgenteViagem::where('nome_completo', 'like', "%{$termo}%")
            ->orWhere('cidade', 'like', "%{$termo}%")
            ->orWhere('uf', 'like', "%{$termo}%")
            ->get();

        return AgenteViagemResource::collection($agentes);
    }
}
