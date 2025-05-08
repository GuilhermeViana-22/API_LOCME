<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resposta extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionario_id',
        'pergunta_id',
        'user_id',
        'resposta'
    ];

    public function questionario()
    {
        return $this->belongsTo(Questionario::class);
    }

    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}