<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaNatureza extends Model
{
    use HasFactory;

    protected $fillable = ['natureza'];

    /**
     * Relacionamento com empresas
     */
    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'natureza_id');
    }
}
