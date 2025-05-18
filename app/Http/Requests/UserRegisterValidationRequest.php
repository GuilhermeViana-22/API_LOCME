<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRegisterValidationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone_celular' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'cpf' => 'nullable|string',
            'data_nascimento' => 'nullable|date_format:Y-m-d',
            'genero' => 'nullable|string|in:masculino,feminino,outro,prefiro não informar',
            'cargo_id' => 'nullable|integer',
            'unidade_id' => 'nullable|integer',
            'status_id' => 'nullable|integer',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ativo' => 'sometimes|boolean',
            'situacao_id' => 'nullable|integer'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome completo é obrigatório.',
            'name.max' => 'O nome não pode exceder 255 caracteres.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Informe um email válido.',
            'email.max' => 'O email não pode exceder 255 caracteres.',
            'telefone_celular.required' => 'O telefone celular é obrigatório.',
            'telefone_celular.max' => 'O telefone não pode exceder 20 caracteres.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed' => 'As senhas não coincidem.',
            'password_confirmation.required' => 'A confirmação de senha é obrigatória.'
        ];
    }

    public function prepareForValidation()
    {
        if ($this->has('ativo') && is_string($this->ativo)) {
            $this->merge([
                'ativo' => filter_var($this->ativo, FILTER_VALIDATE_BOOLEAN)
            ]);
        }
    }
}
