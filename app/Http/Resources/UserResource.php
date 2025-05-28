<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'telefone_celular' => $this->telefone_celular,
            'ativo' => $this->ativo,
            'data_nascimento' => $this->data_nascimento
                ? \Carbon\Carbon::parse($this->data_nascimento)->format('d/m/Y')
                : null,
            'genero' => $this->genero,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'unidade_id' => $this->unidade_id,
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
            'rules' => $this->whenLoaded('rulesUser', function () {
                return $this->rulesUser->map(function ($ruleUser) {
                    return [
                        'rule' => [
                            'id' => $ruleUser->rule->id,
                            'name' => $ruleUser->rule->name,
                            'description' => $ruleUser->rule->description,
                            'permissions' => $ruleUser->rule->permissions->map(function ($permission) {
                                return [
                                    'id' => $permission->id,
                                    'name' => $permission->name,
                                    'description' => $permission->description
                                ];
                            })
                        ],
                        'assigned_at' => $ruleUser->created_at
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
