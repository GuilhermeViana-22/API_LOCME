<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'Empresas';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome_empresa',
        'telefone',
        'email_contato',
        'url',
        'cadastur',
        'condicoes_especiais',
        'condicoes',
        'atividade_id',
        'unidades_localidades',
        'produtos_servicos',
        'nome_cadastro',
        'cargo_cadastro',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'pais',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'condicoes_especiais' => 'boolean',
        'unidades_localidades' => 'array',
        'produtos_servicos' => 'array',
    ];
}
