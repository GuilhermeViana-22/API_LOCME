<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unidade extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome_unidade',
        'codigo_unidade',
        'tipo_unidade_id',
        'endereco_rua',
        'endereco_numero',
        'endereco_complemento',
        'endereco_bairro',
        'endereco_cidade',
        'endereco_estado',
        'endereco_cep',
        'telefone_principal',
        'email_unidade',
        'data_inauguracao',
        'quantidade_setores',
        'ativo'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['tipoUnidade'];

    /**
     * Get the tipo associated with the unidade.
     */
    public function tipoUnidade(): BelongsTo
    {
        return $this->belongsTo(TipoUnidade::class);
    }
}