<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgenteViagem extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'agentes_viagens';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome_completo',
        'cpf',
        'email',
        'whatsapp',
        'cidade',
        'uf',
        'vinculado_agencia',
        'cnpj_agencia_vinculada',
        'tem_cnpj_proprio',
        'portfolio_redes_sociais',
        'aceita_contato_representantes',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'vinculado_agencia' => 'boolean',
        'tem_cnpj_proprio' => 'boolean',
        'aceita_contato_representantes' => 'boolean',
        'data_cadastro' => 'datetime',
        'data_atualizacao' => 'datetime',
    ];
}
