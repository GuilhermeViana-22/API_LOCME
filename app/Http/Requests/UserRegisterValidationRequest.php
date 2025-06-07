<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterValidationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'cpf' => 'required|string',

            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',

            // Campos opcionais
            'telefone_celular' => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'genero' => 'nullable|string',
            'position_id' => 'nullable|integer',
            'unidade_id' => 'nullable|integer',
            'status_id' => 'nullable|integer',
            'foto_perfil' => 'nullable|image',
            'ativo' => 'nullable|boolean',
            'situacao_id' => 'nullable|integer'
        ];
    }

    public function messages()
    {
        return [
            // Mensagens para campos obrigatórios
            'name.required' => 'O nome completo é obrigatório',
            'email.required' => 'O email é obrigatório',
            'email.email' => 'Digite um email válido',
            'cpf.required' => 'O CPF é obrigatório',
            'password.required' => 'A senha é obrigatória',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres',
            'password.confirmed' => 'As senhas não coincidem',
            'password_confirmation.required' => 'Confirme sua senha',
            'password_confirmation.min' => 'A confirmação da senha deve ter no mínimo 8 caracteres',

            // Mensagens genéricas para campos opcionais
            '*.max' => 'Este campo não pode exceder :max caracteres',
            '*.date' => 'Digite uma data válida',
            '*.integer' => 'Este campo deve ser um número',
            '*.boolean' => 'Este campo deve ser verdadeiro ou falso',
            'foto_perfil.image' => 'O arquivo deve ser uma imagem válida'
        ];
    }
}
