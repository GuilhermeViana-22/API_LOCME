<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoHospedagem extends Model
{
    use HasFactory;

    protected $fillable = ['tipo'];

    /**
     * Relacionamento com empresas
     */
    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'tipo_hospedagem_id');
    }
}
