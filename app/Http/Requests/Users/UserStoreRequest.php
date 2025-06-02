<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email_corporativo' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'cpf' => 'nullable|string|max:14|unique:users',
            'data_nascimento' => 'nullable|date',
            'telefone_celular' => 'nullable|string|max:20',
            'genero' => 'nullable|string|in:masculino,feminino,outro',
            'position_id' => 'nullable|exists:positions,id',
            'unidade_id' => 'nullable|exists:unidades,id',
            'status_id' => 'nullable|exists:status,id',
            'situacao_id' => 'nullable|exists:situacoes,id',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ativo' => 'nullable|boolean'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome � obrigat�rio.',
            'email.required' => 'O e-mail � obrigat�rio.',
            'email.email' => 'O e-mail deve ser v�lido.',
            'email.unique' => 'Este e-mail j� est� em uso.',
            'password.required' => 'A senha � obrigat�ria.',
            'password.confirmed' => 'A confirma��o da senha n�o corresponde.',
            'cpf.unique' => 'Este CPF j� est� cadastrado.',
            'email_corporativo.unique' => 'Este e-mail corporativo j� est� em uso.',
            'data_nascimento.date' => 'A data de nascimento deve ser uma data v�lida.',
            'telefone_celular.max' => 'O telefone celular deve ter no m�ximo 20 caracteres.',
            'genero.in' => 'O g�nero selecionado � inv�lido.',
            'position_id.exists' => 'O position selecionado � inv�lido.',
            'unidade_id.exists' => 'A unidade selecionada � inv�lida.',
            'status_id.exists' => 'O status selecionado � inv�lido.',
            'situacao_id.exists' => 'A situa��o selecionada � inv�lida.',
            'foto_perfil.image' => 'O arquivo de foto deve ser uma imagem.',
            'foto_perfil.mimes' => 'A foto deve estar em um dos formatos: jpeg, png, jpg ou gif.',
            'foto_perfil.max' => 'A foto deve ter no m�ximo 2MB.',
            'ativo.boolean' => 'O campo ativo deve ser verdadeiro ou falso.',
        ];
    }
}
