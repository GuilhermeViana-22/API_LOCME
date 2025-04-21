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
            'cpf' => 'required|string|size:13|unique:users,cpf',
            'email_corporativo' => 'required|email|max:255|unique:users,email_corporativo',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'data_nascimento' => 'required|date_format:Y-m-d',
            'telefone_celular' => 'required|string|max:20',
            'genero' => [
                'required',
                'string',
                Rule::in(['masculino', 'feminino', 'outro', 'prefiro não informar'])
            ],
            'cargo_id' => 'required|integer',
            'unidade_id' => 'required|integer',
            'status_id' => 'required|integer',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ativo' => 'sometimes|boolean',
            'situacao_id' => 'nullable|integer|exists:situacoes,id'
        ];
    }

    public function messages()
    {
        return [
            // Nome
            'name.required' => 'O campo nome completo é obrigatório.',
            'name.max' => 'O nome não pode exceder 255 caracteres.',

            // CPF
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.size' => 'O CPF deve ter 14 caracteres (incluindo pontuações).',
            'cpf.unique' => 'Este CPF já está cadastrado em nosso sistema.',

            // Email corporativo
            'email_corporativo.required' => 'O email corporativo é obrigatório.',
            'email_corporativo.email' => 'Informe um email corporativo válido.',
            'email_corporativo.max' => 'O email não pode exceder 255 caracteres.',
            'email_corporativo.unique' => 'Este email corporativo já está em uso.',

            // Senha
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed' => 'As senhas não coincidem.',
            'password_confirmation.required' => 'A confirmação de senha é obrigatória.',

            // Data de nascimento
            'data_nascimento.required' => 'A data de nascimento é obrigatória.',
            'data_nascimento.date_format' => 'Formato de data inválido. Use YYYY-MM-DD.',

            // Telefone
            'telefone_celular.required' => 'O telefone celular é obrigatório.',
            'telefone_celular.max' => 'O telefone não pode exceder 20 caracteres.',

            // Gênero
            'genero.required' => 'O gênero é obrigatório.',
            'genero.in' => 'Gênero inválido. Opções válidas: masculino, feminino, outro, prefiro não informar.',

            // IDs relacionais
            'cargo_id.required' => 'O cargo é obrigatório.',
            'cargo_id.exists' => 'O cargo selecionado é inválido.',
            'unidade_id.required' => 'A unidade é obrigatória.',
            'unidade_id.exists' => 'A unidade selecionada é inválida.',
            'status_id.required' => 'O status é obrigatório.',
            'status_id.exists' => 'O status selecionado é inválido.',
            'situacao_id.exists' => 'A situação selecionada é inválida.',

            // Foto
            'foto_perfil.image' => 'O arquivo deve ser uma imagem válida.',
            'foto_perfil.mimes' => 'Formatos aceitos: JPEG, PNG, JPG ou GIF.',
            'foto_perfil.max' => 'A imagem não pode exceder 2MB.',

            // Ativo
            'ativo.boolean' => 'O campo ativo deve ser verdadeiro ou falso.'
        ];
    }

    public function prepareForValidation()
    {
        if ($this->has('ativo') && is_string($this->ativo)) {
            $this->merge([
                'ativo' => $this->ativo === 'true' || $this->ativo === '1',
            ]);
        }
    }
}
