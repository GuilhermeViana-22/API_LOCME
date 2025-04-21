<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    protected $fillable = ['texto', 'tipo', 'categoria'];

    // Relação com respostas
    public function respostas()
    {
        return $this->hasMany(Resposta::class);
    }

    // Escopo para perguntas de sentimentos
    public function scopeSentimentos($query)
    {
        return $query->where('tipo', 'sentimento');
    }

    // Escopo para perguntas de soft skills
    public function scopeSoftSkills($query)
    {
        return $query->where('tipo', 'soft_skill');
    }
}
