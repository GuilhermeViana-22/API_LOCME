<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    protected $table = 'perguntas';
    protected $fillable = [
        'pergunta',
        'tipo_pergunta_id',
        'data',
        'tipo_periodicidade_id'
    ];

}
