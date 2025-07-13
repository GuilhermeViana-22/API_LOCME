<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilProdutoServico extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'perfis_produtos_servicos';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'perfil_id',
        'tipo_perfil_id',
        'produto_id',
    ];

    /**
     * Relacionamento com o produto/serviço.
     */
    public function produtoServico()
    {
        return $this->belongsTo(ProdutoServico::class, 'produto_id');
    }

    /**
     * Relacionamento com o tipo de perfil.
     */
    public function tipoPerfil()
    {
        return $this->belongsTo(TipoPerfil::class, 'tipo_perfil_id');
    }
}
