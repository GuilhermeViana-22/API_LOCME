<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilIdioma extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'perfis_idiomas';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'perfil_id',
        'idioma_id',
        'tipo_perfil_id',
    ];

    /**
     * Relacionamento com o perfil.
     */
    public function perfil()
    {
        return $this->belongsTo(User::class, 'perfil_id');
    }

    /**
     * Relacionamento com o idioma.
     */
    public function idioma()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }

    /**
     * Relacionamento com o tipo de perfil.
     */
    public function tipoPerfil()
    {
        return $this->belongsTo(TipoPerfil::class, 'tipo_perfil_id');
    }
}
