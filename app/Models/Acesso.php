<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acesso extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rota',
        'ip',
        'dispositivo',
        'ativo',
        'data_acesso'
    ];

    // Relação com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
