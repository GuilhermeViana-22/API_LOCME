<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPerfil extends Model
{
    use HasFactory;

    CONST TIPO_REPRESENTANTE = 1;
    CONST TIPO_AGENTE_VIAGEM = 2;
    CONST TIPO_AGENCIA_VIAGEM = 3;
    CONST TIPO_GUIA_TURISMO = 4;
    CONST TIPO_EMPRESA_ENTIDADE = 5;

    /// AQUI VAI AS RULES DE CADA TIPO
    const RULES = [

        /// TIPO REPRESENTANTE
        self::TIPO_REPRESENTANTE => [
            'apelido' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'email_contato' => 'required|email|max:255',
            'data_nascimento' => 'required|date',
            'operadora_id' => 'required|integer|exists:operadoras,id',
            'empresa_id' => 'nullable|integer|exists:empresas,id',
            'empresa_outra' => 'nullable|string|max:255|required_if:empresa_id,null',
            'telefone_vendas' => 'required|string|max:20',
            'url' => 'nullable|url|max:255',
            'endereco' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:2',
            'cep' => 'required|string|max:10',
            'pais' => 'required|string|max:255',
            'disponivel' => 'nullable|boolean',
            'cv' => 'nullable|string',
        ],

    ];

    /// AQUI VAI AS MESSAGES DE CADA TIPO
    const MESSAGES = [

        /// TIPO REPRESENTANTE
        self::TIPO_REPRESENTANTE => [
            'apelido.required' => 'O campo apelido é obrigatório.',
            'apelido.string' => 'O apelido deve ser um texto.',
            'apelido.max' => 'O apelido não pode ter mais de 255 caracteres.',

            'whatsapp.required' => 'O número do WhatsApp é obrigatório.',
            'whatsapp.string' => 'O WhatsApp deve ser um texto.',
            'whatsapp.max' => 'O WhatsApp não pode ter mais de 20 caracteres.',

            'email_contato.required' => 'O e-mail de contato é obrigatório.',
            'email_contato.email' => 'Informe um e-mail válido.',
            'email_contato.max' => 'O e-mail não pode ter mais de 255 caracteres.',

            'data_nascimento.required' => 'A data de nascimento é obrigatória.',
            'data_nascimento.date' => 'Informe uma data válida.',

            'operadora_id.required' => 'A operadora é obrigatória.',
            'operadora_id.integer' => 'Selecione uma operadora válida.',
            'operadora_id.exists' => 'A operadora selecionada é inválida.',

            'empresa_id.integer' => 'Selecione uma empresa válida.',
            'empresa_id.exists' => 'A empresa selecionada é inválida.',

            'empresa_outra.string' => 'O nome da outra empresa deve ser um texto.',
            'empresa_outra.max' => 'O nome da outra empresa não pode ter mais de 255 caracteres.',
            'empresa_outra.required_if' => 'Informe o nome da empresa quando não selecionar uma da lista.',

            'telefone_vendas.required' => 'O telefone de vendas é obrigatório.',
            'telefone_vendas.string' => 'O telefone de vendas deve ser um texto.',
            'telefone_vendas.max' => 'O telefone de vendas não pode ter mais de 20 caracteres.',

            'url.url' => 'Informe uma URL válida.',
            'url.max' => 'A URL não pode ter mais de 255 caracteres.',

            'endereco.required' => 'O endereço é obrigatório.',
            'endereco.string' => 'O endereço deve ser um texto.',
            'endereco.max' => 'O endereço não pode ter mais de 255 caracteres.',

            'cidade.required' => 'A cidade é obrigatória.',
            'cidade.string' => 'A cidade deve ser um texto.',
            'cidade.max' => 'A cidade não pode ter mais de 255 caracteres.',

            'estado.required' => 'O estado é obrigatório.',
            'estado.string' => 'O estado deve ser um texto.',
            'estado.max' => 'O estado não pode ter mais de 2 caracteres.',

            'cep.required' => 'O CEP é obrigatório.',
            'cep.string' => 'O CEP deve ser um texto.',
            'cep.max' => 'O CEP não pode ter mais de 10 caracteres.',

            'pais.required' => 'O país é obrigatório.',
            'pais.string' => 'O país deve ser um texto.',
            'pais.max' => 'O país não pode ter mais de 255 caracteres.',

            'disponivel.boolean' => 'O campo disponível deve ser verdadeiro ou falso.',
        ],

    ];

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'tipo_perfis';
}
