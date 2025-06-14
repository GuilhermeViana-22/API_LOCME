<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Representante extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'representantes';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'apelido',
        'whatsapp',
        'email_contato',
        'data_nascimento',
        'operadora_id',
        'empresa_id',
        'empresa_outra',
        'telefone_vendas',
        'url',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'pais',
        'disponivel',
        'cv'
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'data_nascimento' => 'date',
        'disponivel' => 'boolean',
    ];

    /**
     * Relacionamento com a operadora.
     */
    public function operadora()
    {
        return $this->belongsTo(Operadora::class, 'operadora_id');
    }

    /**
     * Relacionamento com a empresa.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
