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
            'active' => $this->active,
            'situacao_id' => $this->situacao_id,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'remember_token' => $this->remember_token,
            'password' => $this->password
        ];
    }
}
