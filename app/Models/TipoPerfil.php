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
            'nome' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'email_contato' => 'required|email|max:255',
            'data_nascimento' => 'required|date',


//            'operadora_id' => 'required|integer|exists:operadoras,id',
//            'empresa_id' => 'nullable|integer|exists:empresas,id',

            'operadora_id' => 'required|integer',
            'empresa_id' => 'nullable|integer',

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

        /// TIPO AGENTE DE VIAGEM
        self::TIPO_AGENTE_VIAGEM => [
            'nome_completo' => 'required|string|max:255',
            'cpf' => 'required|string|unique:agentes_viagens,cpf',
            'email' => 'required|email|max:255',
            'whatsapp' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
            'vinculado_agencia' => 'required|boolean',
            'cnpj_agencia_vinculada' => 'required_if:vinculado_agencia,true|string|max:255',
            'tem_cnpj_proprio' => 'required|boolean',
            'portfolio_redes_sociais' => 'nullable|string|max:255',
            'aceita_contato_representantes' => 'required|boolean',
        ],

        /// TIPO AGÊNCIA DE VIAGEM
        self::TIPO_AGENCIA_VIAGEM => [
            'nome_agencia' => 'required|string|max:255',
            'cnpj' => 'required|string|unique:agencias_viagens,cnpj',
            'razao_social' => 'required|string|max:255',
            'nome_fantasia' => 'required|string|max:255',
            'email_institucional' => 'required|email|max:255',
            'telefone_whatsapp' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
            'endereco_completo' => 'required|string|max:255',
            'cep' => 'required|string|max:10',
            'tipo_operacao' => 'required|numeric',
            'recebe_representantes' => 'required|boolean',
            'necessita_agendamento' => 'required|boolean',
            'atende_freelance' => 'required|boolean',
            'cadastur' => 'required|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'segmento_principal_id' => 'required|integer',
            'excursoes_proprias' => 'required|string|size:1',
            'aceita_excursoes_outras' => 'required|string|size:1',
            'descricao_livre' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'divulgar' => 'required|boolean',
        ],
    ];

    /// AQUI VAI AS MESSAGES DE CADA TIPO
    const MESSAGES = [

        /// TIPO REPRESENTANTE
        self::TIPO_REPRESENTANTE => [
            'nome.required' => 'O campo apelido é obrigatório.',
            'nome.string' => 'O apelido deve ser um texto.',
            'nome.max' => 'O apelido não pode ter mais de 255 caracteres.',

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

        /// TIPO AGENTE DE VIAGEM
        self::TIPO_AGENTE_VIAGEM => [
            'nome_completo.required' => 'O nome completo é obrigatório.',
            'nome_completo.string' => 'O nome completo deve ser um texto.',
            'nome_completo.max' => 'O nome completo não pode ter mais de 255 caracteres.',

            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.string' => 'O CPF deve ser um texto.',
            'cpf.unique' => 'Este CPF já está cadastrado no sistema.',

            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.max' => 'O e-mail não pode ter mais de 255 caracteres.',

            'whatsapp.required' => 'O WhatsApp é obrigatório.',
            'whatsapp.string' => 'O WhatsApp deve ser um texto.',
            'whatsapp.max' => 'O WhatsApp não pode ter mais de 255 caracteres.',

            'cidade.required' => 'A cidade é obrigatória.',
            'cidade.string' => 'A cidade deve ser um texto.',
            'cidade.max' => 'A cidade não pode ter mais de 255 caracteres.',

            'uf.required' => 'O estado (UF) é obrigatório.',
            'uf.string' => 'O estado (UF) deve ser um texto.',
            'uf.size' => 'O estado (UF) deve ter exatamente 2 caracteres.',

            'vinculado_agencia.required' => 'Informe se está vinculado a uma agência.',
            'vinculado_agencia.boolean' => 'O campo vinculado a agência deve ser verdadeiro ou falso.',

            'cnpj_agencia_vinculada.required_if' => 'O CNPJ da agência vinculada é obrigatório quando vinculado a uma agência.',
            'cnpj_agencia_vinculada.string' => 'O CNPJ da agência vinculada deve ser um texto.',
            'cnpj_agencia_vinculada.max' => 'O CNPJ da agência vinculada não pode ter mais de 255 caracteres.',

            'tem_cnpj_proprio.required' => 'Informe se possui CNPJ próprio.',
            'tem_cnpj_proprio.boolean' => 'O campo tem CNPJ próprio deve ser verdadeiro ou falso.',

            'portfolio_redes_sociais.string' => 'O portfólio/redes sociais deve ser um texto.',
            'portfolio_redes_sociais.max' => 'O portfólio/redes sociais não pode ter mais de 255 caracteres.',

            'aceita_contato_representantes.required' => 'Informe se aceita contato de representantes.',
            'aceita_contato_representantes.boolean' => 'O campo aceita contato de representantes deve ser verdadeiro ou falso.',
        ],

        /// TIPO AGÊNCIA DE VIAGEM
        self::TIPO_AGENCIA_VIAGEM => [
            'nome_agencia.required' => 'O nome da agência é obrigatório.',
            'nome_agencia.string' => 'O nome da agência deve ser um texto.',
            'nome_agencia.max' => 'O nome da agência não pode ter mais de 255 caracteres.',

            'cnpj.required' => 'O CNPJ é obrigatório.',
            'cnpj.string' => 'O CNPJ deve ser um texto.',
            'cnpj.unique' => 'Este CNPJ já está cadastrado no sistema.',

            'razao_social.required' => 'A razão social é obrigatória.',
            'razao_social.string' => 'A razão social deve ser um texto.',
            'razao_social.max' => 'A razão social não pode ter mais de 255 caracteres.',

            'nome_fantasia.required' => 'O nome fantasia é obrigatório.',
            'nome_fantasia.string' => 'O nome fantasia deve ser um texto.',
            'nome_fantasia.max' => 'O nome fantasia não pode ter mais de 255 caracteres.',

            'email_institucional.required' => 'O e-mail institucional é obrigatório.',
            'email_institucional.email' => 'Informe um e-mail institucional válido.',
            'email_institucional.max' => 'O e-mail institucional não pode ter mais de 255 caracteres.',

            'telefone_whatsapp.required' => 'O telefone/WhatsApp é obrigatório.',
            'telefone_whatsapp.string' => 'O telefone/WhatsApp deve ser um texto.',
            'telefone_whatsapp.max' => 'O telefone/WhatsApp não pode ter mais de 255 caracteres.',

            'cidade.required' => 'A cidade é obrigatória.',
            'cidade.string' => 'A cidade deve ser um texto.',
            'cidade.max' => 'A cidade não pode ter mais de 255 caracteres.',

            'uf.required' => 'O estado (UF) é obrigatório.',
            'uf.string' => 'O estado (UF) deve ser um texto.',
            'uf.size' => 'O estado (UF) deve ter exatamente 2 caracteres.',

            'endereco_completo.required' => 'O endereço completo é obrigatório.',
            'endereco_completo.string' => 'O endereço completo deve ser um texto.',
            'endereco_completo.max' => 'O endereço completo não pode ter mais de 255 caracteres.',

            'cep.required' => 'O CEP é obrigatório.',
            'cep.string' => 'O CEP deve ser um texto.',
            'cep.max' => 'O CEP não pode ter mais de 10 caracteres.',

            'tipo_operacao.required' => 'O tipo de operação é obrigatório.',
            'tipo_operacao.numeric' => 'O tipo de operação deve ser um número.',

            'recebe_representantes.required' => 'Informe se recebe representantes.',
            'recebe_representantes.boolean' => 'O campo recebe representantes deve ser verdadeiro ou falso.',

            'necessita_agendamento.required' => 'Informe se necessita agendamento.',
            'necessita_agendamento.boolean' => 'O campo necessita agendamento deve ser verdadeiro ou falso.',

            'atende_freelance.required' => 'Informe se atende freelancers.',
            'atende_freelance.boolean' => 'O campo atende freelancers deve ser verdadeiro ou falso.',

            'cadastur.required' => 'O número do CADASTUR é obrigatório.',
            'cadastur.string' => 'O número do CADASTUR deve ser um texto.',
            'cadastur.max' => 'O número do CADASTUR não pode ter mais de 255 caracteres.',

            'instagram.max' => 'O Instagram não pode ter mais de 255 caracteres.',

            'segmento_principal_id.required' => 'O segmento principal é obrigatório.',
            'segmento_principal_id.integer' => 'O segmento principal deve ser um número inteiro.',

            'excursoes_proprias.required' => 'Informe se realiza excursões próprias.',
            'excursoes_proprias.string' => 'O campo excursões próprias deve ser um texto.',
            'excursoes_proprias.size' => 'O campo excursões próprias deve ter exatamente 1 caractere.',

            'aceita_excursoes_outras.required' => 'Informe se aceita excursões de outras empresas.',
            'aceita_excursoes_outras.string' => 'O campo aceita excursões de outras empresas deve ser um texto.',
            'aceita_excursoes_outras.size' => 'O campo aceita excursões de outras empresas deve ter exatamente 1 caractere.',

            'logo.image' => 'O arquivo deve ser uma imagem.',
            'logo.mimes' => 'A imagem deve ser dos tipos: jpeg, png, jpg, gif ou webp.',
            'logo.max' => 'A imagem não pode ser maior que 2MB.',

            'divulgar.required' => 'Informe se deseja divulgar a agência.',
            'divulgar.boolean' => 'O campo divulgar deve ser verdadeiro ou falso.',
        ],
    ];

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'tipo_perfis';
}
