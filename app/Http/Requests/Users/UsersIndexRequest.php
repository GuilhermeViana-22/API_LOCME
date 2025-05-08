<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UsersIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'search' => 'sometimes|string|max:255',
            'status_id' => 'sometimes|exists:status,id',
            'situacao_id' => 'sometimes|exists:situacoes,id',
            'cargo_id' => 'sometimes|exists:cargos,id',
            'unidade_id' => 'sometimes|exists:unidades,id',
            'ativo' => 'sometimes|boolean',
            'sort_by' => 'sometimes|in:name,email,created_at,ultimo_acesso',
            'sort_dir' => 'sometimes|in:asc,desc',
        ];
    }
}
