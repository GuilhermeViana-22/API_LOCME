<?php

namespace App\Http\Requests\Unidades;

use Illuminate\Foundation\Http\FormRequest;

class UnidadesIndexRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome_unidade' => 'string|max:255',
            'ativo' => 'integer|between:0,1',
            'tipo_unidade_id' => 'integer',
            'created_at' => 'date_format:Y-m-d',
            'codigo_unidade' => 'string|max:255',
        ];
    }
}
