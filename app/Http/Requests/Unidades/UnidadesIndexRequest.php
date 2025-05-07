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
            'nome_unidade' => 'nullable|string|max:255',
            'ativo' => 'nullable|integer|between:0,1',
            'tipo_unidade_id' => 'nullable|integer',
            'created_at' => 'nullable|date_format:Y-m-d',
            'codigo_unidade' => 'nullable|string|max:255',
        ];
    }
}
