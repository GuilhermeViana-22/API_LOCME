<?php

namespace App\Http\Requests\Position;

use Illuminate\Foundation\Http\FormRequest;

class PositionStoreRequest extends FormRequest
{

    /**
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


     /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nome_position.required' => 'O campo position é obrigatório.',
            'nivel_hierarquico.required' => 'O campo nível hierárquico é obrigatório.',
            'nivel_hierarquico.integer' => 'O campo nível hierárquico deve ser um número inteiro.',
            'nivel_hierarquico.min' => 'O campo nível hierárquico deve ser maior ou igual a 1.',
            'departamento.required' => 'O campo departamento é obrigatório.',
            'departamento.string' => 'O campo departamento deve ser uma string.',
            'descricao.string' => 'O campo descrição deve ser uma string.',
        ];
    }
}
