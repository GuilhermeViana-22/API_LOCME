<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Franqueado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'franqueados';

    protected $fillable = [
        'unidade_id',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    // Relacionamento com Unidade
    public function unidade()
    {
        return $this->belongsTo(Unidade::class);
    }
}