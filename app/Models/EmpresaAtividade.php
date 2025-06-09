<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaAtividade extends Model
{
    use HasFactory;

    protected $fillable = ['atividade'];

    /**
     * Relacionamento com empresas
     */
    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'atividade_id');
    }
}
