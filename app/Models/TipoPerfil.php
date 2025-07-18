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
            'cpf' => 'required|string|unique:representantes,cpf',
            'nome' => 'required|string|max:255',
            'bio' => 'required|string|max:500',

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
            'bio' => 'required|string|max:500',
            'cpf' => 'required|string|unique:agentes_viagens,cpf',
            'email' => 'required|email|max:255',
            'whatsapp' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
            'vinculado_agencia' => 'required|boolean',
            'data_nascimento' => 'required|date',

            //'agencia_id' => 'required_if:vinculado_agencia,true|integer|exists:agencias_viagens,id',
            'agencia_id' => 'required_if:vinculado_agencia,true|integer',

            'tem_cnpj_proprio' => 'required|boolean',
            'cnpj_proprio' => 'required_if:tem_cnpj_proprio,true',

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

            'endereco' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:2',
            'cep' => 'required|string|max:10',
            'pais' => 'required|string|max:255',

            'tipo_operacao_id' => 'required|numeric',

            'recebe_representantes' => 'required|boolean',
            'necessita_agendamento' => 'required|boolean',
            'atende_freelance' => 'required|boolean',
            'cadastur' => 'required|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'segmento_principal_id' => 'required|integer',
            'excursoes_proprias' => 'required|boolean',
            'aceita_excursoes_outras' => 'required|boolean',
            'descricao_livre' => 'nullable|string',
            //'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'divulgar' => 'required|boolean',
        ],

        /// TIPO GUIA DE TURISMO
        self::TIPO_GUIA_TURISMO => [
            'cpf' => 'required|string|unique:guias_turismos,cpf',
            'apelido' => 'required|string|max:255',
            'bio' => 'required|string|max:500',
            'whatsapp' => 'required|string|max:20',
            'email_contato' => 'required|email|max:255',
            'data_nascimento' => 'required|date',
            'cadastur' => 'nullable|string|max:255',
            'abrangencia_id' => 'required|integer',
        ],

        /// TIPO EMPRESA/ENTIDADE
        self::TIPO_EMPRESA_ENTIDADE => [
            'atividades' => 'required|array',
            'produtos_servicos' => 'required|array',
            'unidades_localidades' => 'required|array',

            'nome_empresa' => 'required|string|max:255',
            'cnpj' => 'required|string|unique:empresas,cnpj',
            'telefone' => 'required|string|max:20',
            'email_contato' => 'required|email|max:255',
            'url' => 'nullable|url|max:255',
            'cadastur' => 'nullable|string|max:255',
            'condicoes_especiais' => 'required|boolean',
            'condicoes' => 'nullable|string|required_if:condicoes_especiais,true',

            'nome_cadastro' => 'required|string|max:255',
            'cargo_cadastro' => 'required|string|max:255',

            'endereco' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:2',
            'cep' => 'required|string|max:10',
            'pais' => 'required|string|max:255',
        ]
    ];

    /// AQUI VAI AS MESSAGES DE CADA TIPO
    const MESSAGES = [

        /// TIPO REPRESENTANTE
        self::TIPO_REPRESENTANTE => [
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.string' => 'O CPF deve ser um texto.',
            'cpf.unique' => 'Este CPF já está cadastrado no sistema para os representantes.',

            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O nome deve ser um texto.',
            'nome.max' => 'O nome não pode ter mais de 255 caracteres.',

            'bio.required' => 'O campo biografia é obrigatório.',
            'bio.string' => 'O biografia deve ser um texto.',
            'bio.max' => 'O biografia não pode ter mais de 500 caracteres.',

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

            'bio.required' => 'O campo biografia é obrigatório.',
            'bio.string' => 'O biografia deve ser um texto.',
            'bio.max' => 'O biografia não pode ter mais de 500 caracteres.',

            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.string' => 'O CPF deve ser um texto.',
            'cpf.unique' => 'Este CPF já está cadastrado no sistema para outro agente.',

            'data_nascimento.required' => 'A data de nascimento é obrigatória.',
            'data_nascimento.date' => 'Informe uma data válida.',

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

            'agencia_id.required_if' => 'O informe da agência vinculada é obrigatório quando vinculado a uma agência.',
            'agencia_id.integer' => 'A agência informada está inválida.',
            'agencia_id.exists' => 'Selecione um agência válida.',

            'tem_cnpj_proprio.required' => 'Informe se possui CNPJ próprio.',
            'tem_cnpj_proprio.boolean' => 'O campo tem CNPJ próprio deve ser verdadeiro ou falso.',

            'cnpj_proprio.required_if' => 'Informe o seu CNPJ próprio.',

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
            'cnpj.unique' => 'Este CNPJ já está cadastrado no sistema como uma agência.',

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

            'tipo_operacao_id.required' => 'O tipo de operação é obrigatório.',
            'tipo_operacao_id.numeric' => 'O tipo de operação deve ser um número.',

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
            'excursoes_proprias.boolean' => 'O campo de excursões próprias deve ser verdadeiro ou falso.',

            'aceita_excursoes_outras.required' => 'Informe se aceita excursões de outras empresas.',
            'aceita_excursoes_outras.boolean' => 'O campo de excursões de outras empresas deve ser verdadeiro ou falso.',

            'logo.image' => 'O arquivo deve ser uma imagem.',
            'logo.mimes' => 'A imagem deve ser dos tipos: jpeg, png, jpg, gif ou webp.',
            'logo.max' => 'A imagem não pode ser maior que 2MB.',

            'divulgar.required' => 'Informe se deseja divulgar a agência.',
            'divulgar.boolean' => 'O campo divulgar deve ser verdadeiro ou falso.',
        ],

        /// TIPO GUIA DE TURISMO
        self::TIPO_GUIA_TURISMO => [
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.string' => 'O CPF deve ser um texto.',
            'cpf.unique' => 'Este CPF já está cadastrado no sistema para os guias de turismo.',

            'apelido.required' => 'O campo apelido é obrigatório.',
            'apelido.string' => 'O apelido deve ser um texto.',
            'apelido.max' => 'O apelido não pode ter mais de 255 caracteres.',

            'bio.required' => 'O campo biografia é obrigatório.',
            'bio.string' => 'O biografia deve ser um texto.',
            'bio.max' => 'O biografia não pode ter mais de 500 caracteres.',

            'whatsapp.required' => 'O número do WhatsApp é obrigatório.',
            'whatsapp.string' => 'O WhatsApp deve ser um texto.',
            'whatsapp.max' => 'O WhatsApp não pode ter mais de 20 caracteres.',

            'email_contato.required' => 'O e-mail de contato é obrigatório.',
            'email_contato.email' => 'Informe um e-mail válido.',
            'email_contato.max' => 'O e-mail não pode ter mais de 255 caracteres.',

            'data_nascimento.required' => 'A data de nascimento é obrigatória.',
            'data_nascimento.date' => 'Informe uma data válida.',

            'cadastur.string' => 'O CADASTUR deve ser um texto.',
            'cadastur.max' => 'O CADASTUR não pode ter mais de 255 caracteres.',

            'abrangencia_id.required' => 'A abrangência é obrigatória.',
            'abrangencia_id.integer' => 'Selecione uma abrangência válida.',
        ],

        /// TIPO EMPRESA/ENTIDADE
        self::TIPO_EMPRESA_ENTIDADE => [
            'atividades.required' => 'Alguma atividade deve ser selecionada.',
            'produtos_servicos.required' => 'Algum produto/serviço deve ser selecionado.',
            'unidades_localidades.required' => 'Alguma localidade deve ser selecionada.',

            'nome_empresa.required' => 'O nome da empresa é obrigatório.',
            'nome_empresa.string' => 'O nome da empresa deve ser um texto.',
            'nome_empresa.max' => 'O nome da empresa não pode ter mais de 255 caracteres.',

            'cnpj.required' => 'O CNPJ é obrigatório.',
            'cnpj.string' => 'O CNPJ deve ser um texto.',
            'cnpj.unique' => 'Este CNPJ já está cadastrado no sistema.',

            'telefone.required' => 'O telefone é obrigatório.',
            'telefone.string' => 'O telefone deve ser um texto.',
            'telefone.max' => 'O telefone não pode ter mais de 20 caracteres.',

            'email_contato.required' => 'O e-mail de contato é obrigatório.',
            'email_contato.email' => 'Informe um e-mail válido.',
            'email_contato.max' => 'O e-mail não pode ter mais de 255 caracteres.',

            'url.url' => 'Informe uma URL válida.',
            'url.max' => 'A URL não pode ter mais de 255 caracteres.',

            'cadastur.string' => 'O CADASTUR deve ser um texto.',
            'cadastur.max' => 'O CADASTUR não pode ter mais de 255 caracteres.',

            'condicoes_especiais.required' => 'Informe se possui condições especiais.',
            'condicoes_especiais.boolean' => 'O campo condições especiais deve ser verdadeiro ou falso.',

            'condicoes.string' => 'As condições especiais devem ser um texto.',
            'condicoes.required_if' => 'Descreva as condições especiais quando marcado.',

            'nome_cadastro.required' => 'O nome do responsável pelo cadastro é obrigatório.',
            'nome_cadastro.string' => 'O nome do responsável deve ser um texto.',
            'nome_cadastro.max' => 'O nome do responsável não pode ter mais de 255 caracteres.',

            'cargo_cadastro.required' => 'O cargo do responsável é obrigatório.',
            'cargo_cadastro.string' => 'O cargo deve ser um texto.',
            'cargo_cadastro.max' => 'O cargo não pode ter mais de 255 caracteres.',

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
        ]
    ];

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'tipo_perfis';
}
