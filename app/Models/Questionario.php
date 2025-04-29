<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionario extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'periodicidade',
        'data_inicio',
        'data_termino',
        'ativo',
        'publico_alvo',
        'criado_por',
    ];

    public function perguntas()
    {
        return $this->hasMany(Pergunta::class);
    }
}
