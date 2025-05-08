<?php

namespace App\Http\Resources\Unidades;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Http\Resources\Franqueado\FranqueadoResource;

class UnidadeResource extends JsonResource
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
            'nome_unidade' => $this->nome_unidade,
            'codigo_unidade' => $this->codigo_unidade,
            'endereco_completo' => $this->formatarEndereco(),
            'endereco_rua' => $this->endereco_rua,
            'endereco_numero' => $this->endereco_numero,
            'endereco_complemento' => $this->endereco_complemento,
            'endereco_bairro' => $this->endereco_bairro,
            'endereco_cidade' => $this->endereco_cidade,
            'endereco_estado' => $this->endereco_estado,
            'endereco_cep' => $this->formatarCEP(),
            'telefone_principal' => $this->telefone_principal,
            'email_unidade' => $this->email_unidade,
            'data_inauguracao' => $this->formatarData($this->data_inauguracao),
            'quantidade_setores' => $this->quantidade_setores,
            'ativo' => (bool)$this->ativo,
            'created_at' => $this->formatarDataHora($this->created_at),
            'updated_at' => $this->formatarDataHora($this->updated_at),
            'tipo_unidade_id' => $this->tipo_unidade_id,
            'tipo_unidade' => $this->tipo_unidade,
            'franqueados' => FranqueadoResource::collection($this->whenLoaded('franqueados')),
            'franqueados_ativos' => FranqueadoResource::collection($this->whenLoaded('franqueadosAtivos')),
            'total_franqueados' => $this->whenLoaded('franqueados', function () {
                return $this->franqueados->count();
            }),
            'total_franqueados_ativos' => $this->whenLoaded('franqueados', function () {
                return $this->franqueados->where('ativo', true)->count();
            }),
        ];
    }

    /**
     * Formata a data no padrão brasileiro (dd/mm/YYYY)
     */
    private function formatarData($data)
    {
        return $data ? Carbon::parse($data)->format('d/m/Y') : null;
    }

    /**
     * Formata a data e hora no padrão brasileiro (dd/mm/YYYY H:i:s)
     */
    private function formatarDataHora($dataHora)
    {
        return $dataHora ? Carbon::parse($dataHora)->format('d/m/Y H:i:s') : null;
    }

    /**
     * Formata o CEP no padrão brasileiro (XXXXX-XXX)
     */
    private function formatarCEP()
    {
        if (empty($this->endereco_cep)) {
            return null;
        }

        $cep = preg_replace('/[^0-9]/', '', $this->endereco_cep);
        return substr($cep, 0, 5) . '-' . substr($cep, 5, 3);
    }

    /**
     * Monta o endereço completo formatado
     */
    private function formatarEndereco()
    {
        $endereco = $this->endereco_rua . ', ' . $this->endereco_numero;

        if ($this->endereco_complemento) {
            $endereco .= ' - ' . $this->endereco_complemento;
        }

        $endereco .= ' - ' . $this->endereco_bairro . ', ' .
                    $this->endereco_cidade . ' - ' .
                    $this->endereco_estado;

        return $endereco;
    }
}