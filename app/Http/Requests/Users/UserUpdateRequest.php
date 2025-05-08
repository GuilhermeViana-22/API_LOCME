<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email_corporativo' => 'sometimes|email|unique:users,email,'.$this->user->id,
            'password' => ['sometimes', 'confirmed', Rules\Password::defaults()],
            'cpf' => 'nullable|string|max:14|unique:users,cpf,'.$this->user->id,
            'email_corporativo' => 'nullable|email|unique:users,email_corporativo,'.$this->user->id,
            'data_nascimento' => 'nullable|date',
            'telefone_celular' => 'nullable|string|max:20',
            'genero' => 'nullable|string|in:masculino,feminino,outro',
            'cargo_id' => 'nullable|exists:cargos,id',
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
            'name.sometimes' => 'O nome é obrigatório.',
            'email.sometimes' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'password.sometimes' => 'A senha é obrigatória.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'email_corporativo.unique' => 'Este e-mail corporativo já está em uso.',
            'data_nascimento.date' => 'A data de nascimento deve ser uma data válida.',
            'telefone_celular.max' => 'O telefone celular deve ter no máximo 20 caracteres.',
            'genero.in' => 'O gênero selecionado é inválido.',
            'cargo_id.exists' => 'O cargo selecionado é inválido.',
            'unidade_id.exists' => 'A unidade selecionada é inválida.',
            'status_id.exists' => 'O status selecionado é inválido.',
            'situacao_id.exists' => 'A situação selecionada é inválida.',
            'foto_perfil.image' => 'O arquivo de foto deve ser uma imagem.',
            'foto_perfil.mimes' => 'A foto deve estar em um dos formatos: jpeg, png, jpg ou gif.',
            'foto_perfil.max' => 'A foto deve ter no máximo 2MB.',
            'ativo.boolean' => 'O campo ativo deve ser verdadeiro ou falso.',
        ];
    }
}
