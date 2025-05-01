<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    protected $fillable = [
        'tipo_pergunta_id',
        'data',
        'tipo_periodicidade_id'
    ];

}
