<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UserUpdateRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $input = $this->all();

        // Campos que vamos ignorar no momento
        $ignoreFields = ['permissions', 'role', 'role_since', 'id'];

        foreach ($ignoreFields as $field) {
            if (isset($input[$field])) {
                unset($input[$field]);
            }
        }

        $this->replace($input);
    }

    public function rules()
    {
        $userId = $this->route('id');

        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $userId,
            'password' => ['sometimes', 'confirmed', Rules\Password::defaults()],
            'cpf' => 'nullable|string|max:14|unique:users,cpf,' . $userId,
            'data_nascimento' => 'nullable|date',
            'telefone_celular' => 'nullable|string|max:20',
            'genero' => 'nullable|string|in:masculino,feminino,outro',
            'position_id' => 'nullable|exists:positions,id',
            'unidade_id' => 'nullable|exists:unidades,id',
            'status_id' => 'nullable|exists:status,id',
            'situacao_id' => 'nullable|exists:situacoes,id',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ativo' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'O nome deve ser uma string v�lida.',
            'name.max' => 'O nome pode ter no m�ximo 255 caracteres.',
            'email.email' => 'O e-mail deve ser v�lido.',
            'email.unique' => 'Este e-mail j� est� em uso.',
            'password.confirmed' => 'A confirma��o da senha n�o corresponde.',
            'cpf.unique' => 'Este CPF j� est� cadastrado.',
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
