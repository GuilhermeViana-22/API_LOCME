<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgenciaViagem extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'Agencias_Viagens';

    /**
     * Indica se o modelo deve ser timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Nome da coluna de timestamp de criação.
     *
     * @var string
     */
    const CREATED_AT = 'data_cadastro';

    /**
     * Nome da coluna de timestamp de atualização.
     *
     * @var string
     */
    const UPDATED_AT = 'data_atualizacao';

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
        'cidade',
        'uf',
        'endereco_completo',
        'cep',
        'tipo_operacao',
        'recebe_representantes',
        'necessita_agendamento',
        'atende_freelance',
        'cadastur',
        'instagram',
        'segmento_principal',
        'excursoes_proprias',
        'aceita_excursoes_outras',
        'descricao_livre',
        'logo_path',
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
        'data_cadastro' => 'datetime',
        'data_atualizacao' => 'datetime',
    ];
}
