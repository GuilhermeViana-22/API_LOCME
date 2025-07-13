<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\TipoPerfil;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $perfil = $this->perfil;
        $perfilData = $perfil ? $perfil->toArray() : null;

        // Carrega os relacionamentos específicos se for empresa
        if ($perfil && $this->tipo_perfil_id === TipoPerfil::TIPO_EMPRESA_ENTIDADE) {
            $perfil->load([
                'atividades',
                'unidadesLocalidades',
                'produtosServicos'
            ]);

            $perfilData = array_merge($perfilData, [
                'atividades' => $perfil->atividades,
                'unidades_localidades' => $perfil->unidadesLocalidades,
                'produtos_servicos' => $perfil->produtosServicos
            ]);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,

            'foto_perfil' => $this->foto_perfil ?? null,
            'foto_perfil_url' => $this->foto_perfil ? asset('storage/profile/'.$this->id.'/'.$this->foto_perfil) : null,

            'tipo_perfil_id' => $this->tipo_perfil_id,
            'tipo_perfil' => $this->tipoPerfil->tipo,

            'perfil_id' => $this->perfil_id ?? null,
            'perfil' => $perfilData,

            'bio' => $this->bio ?? null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
