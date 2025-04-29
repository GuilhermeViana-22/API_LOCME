<?php

namespace App\Http\Requests\Unidades;

use Illuminate\Foundation\Http\FormRequest;

class UnidadeUpdateRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome_unidade' => 'sometimes|string|max:100',
            'codigo_unidade' => 'sometimes|',
            'tipo_unidade_id' => 'sometimes',
            'endereco_rua' => 'sometimes|string|max:100',
            'endereco_numero' => 'sometimes|string|max:20',
            'endereco_complemento' => 'nullable|string|max:50',
            'endereco_bairro' => 'sometimes|string|max:50',
            'endereco_cidade' => 'sometimes|string|max:50',
            'endereco_estado' => 'sometimes|string|size:2',
            'endereco_cep' => 'sometimes|string|max:10',
            'telefone_principal' => 'sometimes|string|max:20',
            'email_unidade' => 'nullable|email|max:100',
            'data_inauguracao' => 'nullable|date',
            'quantidade_setores' => 'nullable|integer',
            'ativo' => 'nullable|boolean'
        ];
    }
}
