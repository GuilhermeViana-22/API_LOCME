<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,

            // Dados do perfil
            'data_nascimento' => $this->data_nascimento?->format('Y-m-d'),
            'telefone_celular' => $this->telefone_celular,
            'cargo_funcao' => $this->cargo_funcao,
            'empresa_atual' => $this->empresaAtual ? [
                'id' => $this->empresaAtual->id,
                'nome' => $this->empresaAtual->nome_fantasia,
            ] : null,
            'empresa_outro' => $this->empresa_outro,
            'foto_perfil' => $this->foto_perfil ? asset('storage/' . $this->foto_perfil) : null,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'email_contato' => $this->email_contato,
            'linkedin' => $this->linkedin,
            'bio' => $this->bio,

            // Configurações de visibilidade
            'visibilidade' => [
                'telefone' => $this->visibilidade_telefone,
                'email' => $this->visibilidade_email,
                'linkedin' => $this->visibilidade_linkedin,
            ],

            // Dados extras
            'genero' => $this->genero,
            'position_id' => $this->position_id,
            'unidade_id' => $this->unidade_id,
            'status_id' => $this->status_id,
            'ultimo_acesso' => $this->ultimo_acesso?->format('Y-m-d H:i:s'),
            'ativo' => $this->ativo,
            'situacao_id' => $this->situacao_id,

            // Timestamps
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}
