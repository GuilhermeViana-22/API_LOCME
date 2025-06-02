<?php

namespace App\Http\Requests\positions;

use Illuminate\Foundation\Http\FormRequest;

class positionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.

     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome_position' => 'required|string|max:100',
            'nivel_hierarquico' => 'required|integer|min:1',
            'departamento' => 'required|string|max:50',
            'descricao' => 'nullable|string',
        ];
    }
}
