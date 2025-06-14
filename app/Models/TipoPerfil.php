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

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'tipo_perfis';
}
