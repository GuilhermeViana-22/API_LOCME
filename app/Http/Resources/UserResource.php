<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     title="User Resource",
 *     description="User resource with all fields",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Jo�o Silva"),
 *     @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
 *     @OA\Property(property="active", type="integer", example=1),
 *     @OA\Property(property="situacao_id", type="integer", example=1),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="remember_token", type="string", nullable=true),
 *     @OA\Property(property="password", type="string")
 * )
 */
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'telefone_celular'  => $this->telefone_celular,
            'ativo' => $this->ativo,
            'active' => $this->active,
            'data_nascimento' => $this->data_nascimento
                ? \Carbon\Carbon::parse($this->data_nascimento)->format('d/m/Y')
                : null,
            'genero' => $this->genero,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'unidade' => $this->whenLoaded('unidade', function () {
                return [
                    'id' => $this->unidade->id,
                    'nome_unidade' => $this->unidade->nome_unidade
                ];
            }),
            'cargo' => $this->whenLoaded('cargo', function () {
                return [
                    'id' => $this->cargo->id,
                    'nome_cargo' => $this->cargo->nome_cargo,
                    'nivel_hierarquico' => $this->cargo->nivel_hierarquico,
                    'departamento' => $this->cargo->departamento,

                ];
            }),
            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name
                    ];
                });
            }),
            'logs' => $this->whenLoaded('logs', function () {
                return $this->logs->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'route' => $log->rota,
                        'authenticated' => (bool)$log->autenticado,
                        'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $log->updated_at->format('Y-m-d H:i:s')
                    ];
                });
            }),
        ];
    }
}
