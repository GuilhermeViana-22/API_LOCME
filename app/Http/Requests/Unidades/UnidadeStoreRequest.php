<?php

namespace App\Http\Requests\Unidades;

use Illuminate\Foundation\Http\FormRequest;

class UnidadeStoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome_unidade' => 'required|string|max:100',
            'codigo_unidade' => 'required|string|max:20|unique:unidades',
            'tipo_unidade_id' => 'required',
            'endereco_rua' => 'required|string|max:100',
            'endereco_numero' => 'required|string|max:20',
            'endereco_complemento' => 'nullable|string|max:50',
            'endereco_bairro' => 'required|string|max:50',
            'endereco_cidade' => 'required|string|max:50',
            'endereco_estado' => 'required|string|size:2',
            'endereco_cep' => 'required|string|max:10',
            'telefone_principal' => 'required|string|max:20',
            'email_unidade' => 'nullable|email|max:100',
            'data_inauguracao' => 'nullable|date',
            'quantidade_setores' => 'nullable|integer',
            'ativo' => 'nullable|boolean'
        ];
    }
}
