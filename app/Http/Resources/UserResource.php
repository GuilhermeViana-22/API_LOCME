<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'foto_perfil' => $this->foto_perfil,
            'tipo_perfil_id' => $this->tipo_perfil_id,
            'perfil_id' => $this->perfil_id,
            'bio' => $this->bio,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at
        ];
    }
}
