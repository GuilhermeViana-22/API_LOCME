<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resposta extends Model
{
    protected $fillable = ['user_id', 'pergunta_id', 'resposta', 'data_resposta'];

    // Relação com usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relação com pergunta
    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class);
    }

    // Opções de resposta
    public static function opcoesResposta()
    {
        return [
            'A' => 'Não é verdade',
            'B' => 'Raramente é verdade',
            'C' => 'Às vezes é verdade',
            'D' => 'Frequentemente é verdade',
            'E' => 'Sempre é verdade'
        ];
    }
}
