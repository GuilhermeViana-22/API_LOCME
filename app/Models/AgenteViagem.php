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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relacionamento com o usuário que possui este perfil de agente de viagem
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function usuario()
    {
        return $this->hasOne(User::class, 'agente_viagem_id', 'id');
    }
}
