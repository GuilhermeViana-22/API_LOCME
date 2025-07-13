<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilUnidadeLocalidade extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'perfis_unidades_localidades';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'perfil_id',
        'tipo_perfil_id',
        'unidade_localidade_id',
    ];

    /**
     * Relacionamento com a unidade localidade.
     */
    public function unidadeLocalidade()
    {
        return $this->belongsTo(UnidadeLocalidade::class, 'unidade_localidade_id');
    }

    /**
     * Relacionamento com o tipo de perfil.
     */
    public function tipoPerfil()
    {
        return $this->belongsTo(TipoPerfil::class, 'tipo_perfil_id');
    }
}
