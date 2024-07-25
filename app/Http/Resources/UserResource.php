<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'deleted_at' => $this->deleted_at,
            'remember_token' => $this->remember_token,
            'situacao_id' => $this->situacao_id,
            'created_at' => $this->created_at,
            'active' => $this->active,
            'updated_at' => $this->updated_at,
            'name' => $this->name,
        ];
    }
}
