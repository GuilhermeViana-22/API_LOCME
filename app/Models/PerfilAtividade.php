<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilAtividade extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'perfis_atividades';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'perfil_id',
        'tipo_perfil_id',
        'atividade_id',
    ];

    /**
     * Relacionamento com o produto/serviço.
     */
    public function atividade()
    {
        return $this->belongsTo(Atividade::class, 'atividade_id');
    }

    /**
     * Relacionamento com o tipo de perfil.
     */
    public function tipoPerfil()
    {
        return $this->belongsTo(TipoPerfil::class, 'tipo_perfil_id');
    }
}
