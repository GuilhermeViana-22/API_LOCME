<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo para representar Agentes de Viagem no sistema
 *
 * @package App\Models
 */
class AgenteViagem extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'agentes_viagens';

    /**
     * Os atributos atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome_completo',
        'cpf',
        'data_nascimento',
        'email',
        'whatsapp',
        'cidade',
        'uf',
        'vinculado_agencia',
        'agencia_id',
        'tem_cnpj_proprio',
        'cnpj_proprio',
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
        'data_nascimento' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
