<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresaPorte extends Model
{
    use HasFactory;

    protected $fillable = ['porte'];

    /**
     * Relacionamento com empresas
     */
    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'porte_id');
    }
}
