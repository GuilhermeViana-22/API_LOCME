<?php

namespace App\Http\Resources\Perfis;

use Illuminate\Http\Resources\Json\JsonResource;

class AgenteViagemResource extends JsonResource
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
            'nome_completo' => $this->nome_completo,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'whatsapp' => $this->whatsapp,
            'cidade' => $this->cidade,
            'uf' => $this->uf,
            'vinculado_agencia' => $this->vinculado_agencia,
            'cnpj_agencia_vinculada' => $this->when($this->vinculado_agencia, $this->cnpj_agencia_vinculada),
            'tem_cnpj_proprio' => $this->tem_cnpj_proprio,
            'portfolio_redes_sociais' => $this->portfolio_redes_sociais,
            'aceita_contato_representantes' => $this->aceita_contato_representantes,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
