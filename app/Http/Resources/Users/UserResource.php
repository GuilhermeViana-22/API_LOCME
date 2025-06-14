<?php

namespace App\Http\Resources\Users;

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

            'foto_perfil' => $this->foto_perfil ?? null,
            'foto_perfil_url' => $this->foto_perfil ? asset('storage/profile/'.$this->id.'/'.$this->foto_perfil) : null,

            'tipo_perfil_id' => $this->tipo_perfil_id,
            'perfil_id' => $this->perfil_id ?? null,
            'bio' => $this->bio ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
