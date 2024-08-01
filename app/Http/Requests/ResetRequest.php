<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class ResetRequest extends FormRequest
{
    /**
     * Obtém as regras de validação que se aplicam à solicitação.
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Obtém as mensagens de erro personalizadas para a validação de regras.
     * @return array
     */
    public function messages()
    {
        return [
            'password.required' => 'A senha é obrigatória.',
            'password.string' => 'A senha deve ser uma string.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
        ];
    }
}
