<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class ResetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'informe um email válido'
        ];
    }
}
