<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Permite que qualquer usu�rio fa�a a requisi��o
    }

    public function rules()
    {
        $userId = $this->route('id'); // Obt�m o ID da rota

        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$userId,
            'password' => ['sometimes', 'confirmed', Rules\Password::defaults()],
            'cpf' => 'nullable|string|max:14|unique:users,cpf,'.$userId,
            'data_nascimento' => 'nullable|date',
            'telefone_celular' => 'nullable|string|max:20',
            'genero' => 'nullable|string|in:masculino,feminino,outro',
            'cargo_id' => 'nullable|exists:cargos,id',
            'unidade_id' => 'nullable',
            'status_id' => 'nullable|exists:status,id',
            'situacao_id' => 'nullable|exists:situacoes,id',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ativo' => 'nullable|boolean'
        ];
    }

    public function messages()
    {
        return [
            'name.sometimes' => 'O nome � obrigat�rio.',
            'email.sometimes' => 'O e-mail � obrigat�rio.',
            'email.email' => 'O e-mail deve ser v�lido.',
            'email.unique' => 'Este e-mail j� est� em uso.',
            'password.sometimes' => 'A senha � obrigat�ria.',
            'password.confirmed' => 'A confirma��o da senha n�o corresponde.',
            'cpf.unique' => 'Este CPF j� est� cadastrado.',
            'data_nascimento.date' => 'A data de nascimento deve ser uma data v�lida.',
            'telefone_celular.max' => 'O telefone celular deve ter no m�ximo 20 caracteres.',
            'genero.in' => 'O g�nero selecionado � inv�lido.',
            'cargo_id.exists' => 'O cargo selecionado � inv�lido.',
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
