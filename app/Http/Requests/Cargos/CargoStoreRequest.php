<?php

namespace App\Http\Requests\Cargos;

use Illuminate\Foundation\Http\FormRequest;

class CargoStoreRequest extends FormRequest
{
   
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome_cargo' => 'required|string|max:100',
            'nivel_hierarquico' => 'required|integer|min:1',
            'departamento' => 'required|string|max:50',
            'descricao' => 'nullable|string',
        ];
    }
}
