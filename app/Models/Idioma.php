<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idioma extends Model
{
    use HasFactory;

    /**
     * A chave primária não é um inteiro auto-incremento.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * O tipo da chave primária.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * A chave primária para o modelo.
     *
     * @var string
     */
    protected $primaryKey = 'Idiomas';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'Idioma',
    ];
}
