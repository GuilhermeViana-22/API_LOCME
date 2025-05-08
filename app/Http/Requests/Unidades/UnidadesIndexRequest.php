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
            // Filtros básicos (mantive os que você já tinha)
            'nome_unidade' => 'nullable|string|max:255',
            'ativo' => 'nullable|integer|between:0,1',
            'tipo_unidade_id' => 'nullable|integer',
            'codigo_unidade' => 'nullable|string|max:255',

            // Paginação (apenas o essencial)
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:50',

            // Data no formato do banco (Y-m-d) - mantive como estava
            'created_at' => 'nullable|date_format:Y-m-d'
        ];
    }
}