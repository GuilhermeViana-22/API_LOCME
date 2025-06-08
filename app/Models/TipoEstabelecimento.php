<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEstabelecimento extends Model
{
    use HasFactory;

    protected $fillable = ['tipo'];

    /**
     * Relacionamento com empresas
     */
    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'tipo_estabelecimento_id');
    }
}
