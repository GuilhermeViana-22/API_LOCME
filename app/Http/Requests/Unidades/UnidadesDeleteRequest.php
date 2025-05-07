<?php

namespace App\Http\Requests\Unidades;

use Illuminate\Foundation\Http\FormRequest;

class UnidadesDeleteRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'id' => 'required|integer',
        ];
    }
}
