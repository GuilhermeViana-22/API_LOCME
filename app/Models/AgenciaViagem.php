<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgenciaViagem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'agencias_viagens';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome_agencia',
        'cnpj',
        'razao_social',
        'nome_fantasia',
        'email_institucional',
        'telefone_whatsapp',

        'endereco',
        'cidade',
        'estado',
        'cep',
        'pais',

        'tipo_operacao_id', // trazer para BD e validar

        'recebe_representantes',
        'necessita_agendamento',
        'atende_freelance',
        'cadastur',
        'instagram',

        'segmento_principal_id', // trazer para BD e validar

        'excursoes_proprias',
        'aceita_excursoes_outras',
        'descricao_livre',

        'divulgar',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'recebe_representantes' => 'boolean',
        'necessita_agendamento' => 'boolean',
        'atende_freelance' => 'boolean',
        'divulgar' => 'boolean',
        'excursoes_proprias' => 'boolean',
        'aceita_excursoes_outras' => 'boolean',

        'data_cadastro' => 'datetime',
        'data_atualizacao' => 'datetime',
    ];
}
