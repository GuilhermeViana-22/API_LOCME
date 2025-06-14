<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterValidationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'tipo_perfil_id' => 'required|integer|exists:tipo_perfis,id',

            // Campos opcionais da migration
            'email_verified_at' => 'nullable|date',
            'rememberToken' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            // Mensagens para campos obrigatórios
            'name.required' => 'O nome completo é obrigatório',
            'name.max' => 'O nome não pode exceder 255 caracteres',

            'email.required' => 'O email é obrigatório',
            'email.email' => 'Digite um email válido',
            'email.max' => 'O email não pode exceder 255 caracteres',
            'email.unique' => 'Este email já está cadastrado',

            'password.required' => 'A senha é obrigatória',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres',
            'password.confirmed' => 'As senhas não coincidem',

            'password_confirmation.required' => 'Confirme sua senha',
            'password_confirmation.min' => 'A confirmação da senha deve ter no mínimo 8 caracteres',

            'tipo_perfil_id.required' => 'O tipo do perfil deve ser informado',
            'tipo_perfil_id.integer' => 'O tipo do perfil informado está inválido',
            'tipo_perfil_id.exists' => 'O tipo do perfil informado está inválido',

            'email_verified_at.date' => 'A data de verificação de email deve ser válida',
            'rememberToken.string' => 'O token de lembrete deve ser uma string'
        ];
    }
}
