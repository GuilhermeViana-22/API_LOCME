<?php

namespace App\Http\Resources\Positions;

use Illuminate\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'position' => $this->position,
            'hierarchical_level' => $this->nivel_hierarquico,
            'department' => $this->departamento,
            'description' => $this->descricao,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relacionamentos condicionais usando whenLoaded
            'rules' => $this->whenLoaded('rules', function() {
                return $this->rules->map(function($rule) {
                    return [
                        'id' => $rule->id,
                        'name' => $rule->name,
                        'description' => $rule->description ?? null,
                        'permissions' => $rule->relationLoaded('permissions') ? $rule->permissions->map(function($permission) {
                            return [
                                'id' => $permission->id,
                                'name' => $permission->name,
                                'description' => $permission->description
                            ];
                        }) : []
                    ];
                });
            }),

            'rule_positions' => $this->whenLoaded('rulePositions', function() {
                return $this->rulePositions->map(function($rulePosition) {
                    return [
                        'rule' => $rulePosition->relationLoaded('rule') ? [
                            'id' => $rulePosition->rule->id,
                            'name' => $rulePosition->rule->name,
                            'permissions' => $rulePosition->rule->relationLoaded('permissions') ? $rulePosition->rule->permissions->map(function($permission) {
                                return [
                                    'id' => $permission->id,
                                    'name' => $permission->name,
                                    'description' => $permission->description
                                ];
                            }) : []
                        ] : null
                    ];
                });
            })
        ];
    }

    /**
     * Get all unique permissions from all rules
     */
    protected function getAggregatedPermissions()
    {
        return $this->rules->flatMap(function ($rule) {
            return $rule->permissions ?? collect();
        })
            ->unique('id')
            ->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'description' => $permission->description
                ];
            })
            ->values()
            ->all();
    }
}
