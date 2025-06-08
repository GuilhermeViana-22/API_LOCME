<?php

namespace App\Http\Requests\Profiles;

use Illuminate\Foundation\Http\FormRequest;

class CompletarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cargo_funcao' => 'required|string|max:500',
            'data_nascimento' => 'required|date',
            'empresa_id' => 'nullable|exists:empresas,id',
            'empresa_outro' => 'nullable|string|max:500',
            'telefone_celular' => 'nullable|string|max:20',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cidade' => 'required|string|max:500',
            'estado' => 'required|string|max:500',
            'email_contato' => 'nullable|email',
            'linkedin' => 'nullable|url',
            'bio' =>  'nullable|string|max:500',
            'visibilidade_email' =>  'nullable|boolean',
            'visibilidade_telefone' =>  'nullable|boolean',
            'visibilidade_linkedin' =>  'nullable|boolean',
            'genero' =>  'required|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'cargo_funcao.required' => 'O campo cargo/função é obrigatório.',
            'cargo_funcao.string' => 'O campo cargo/função deve ser um texto.',
            'cargo_funcao.max' => 'O campo cargo/função não pode ter mais que 500 caracteres.',

            'data_nascimento.required' => 'O campo data de nascimento é obrigatório.',
            'data_nascimento.date' => 'O campo data de nascimento deve ser uma data válida.',

            'empresa_id.exists' => 'A empresa selecionada não é válida.',

            'empresa_outro.string' => 'O campo outra empresa deve ser um texto.',
            'empresa_outro.max' => 'O campo outra empresa não pode ter mais que 500 caracteres.',

            'telefone_celular.string' => 'O campo telefone celular deve ser um texto.',
            'telefone_celular.max' => 'O campo telefone celular não pode ter mais que 20 caracteres.',

            'foto_perfil.image' => 'O arquivo deve ser uma imagem válida.',
            'foto_perfil.mimes' => 'A imagem deve ser do tipo: jpeg, png ou jpg.',
            'foto_perfil.max' => 'A imagem não pode ser maior que 2MB.',

            'cidade.required' => 'O campo cidade é obrigatório.',
            'cidade.string' => 'O campo cidade deve ser um texto.',
            'cidade.max' => 'O campo cidade não pode ter mais que 500 caracteres.',

            'estado.required' => 'O campo estado é obrigatório.',
            'estado.string' => 'O campo estado deve ser um texto.',
            'estado.max' => 'O campo estado não pode ter mais que 500 caracteres.',

            'email_contato.email' => 'O campo e-mail de contato deve ser um endereço de e-mail válido.',

            'linkedin.url' => 'O campo LinkedIn deve ser uma URL válida.',

            'bio.string' => 'O campo bio deve ser um texto.',
            'bio.max' => 'O campo bio não pode ter mais que 500 caracteres.',

            'visibilidade_email.boolean' => 'O campo visibilidade do e-mail deve ser verdadeiro ou falso.',

            'visibilidade_telefone.boolean' => 'O campo visibilidade do telefone deve ser verdadeiro ou falso.',

            'visibilidade_linkedin.boolean' => 'O campo visibilidade do LinkedIn deve ser verdadeiro ou falso.',

            'genero.required' => 'O campo gênero é obrigatório.',
            'genero.string' => 'O campo gênero deve ser um texto.',
            'genero.max' => 'O campo gênero não pode ter mais que 500 caracteres.',
        ];
    }
}
