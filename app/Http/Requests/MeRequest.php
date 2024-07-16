<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Identificador do usuário não identificado.'
        ];
    }
}
