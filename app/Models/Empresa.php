<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'atividade_id',
        'cnpj',
        'nome_fantasia',
        'razao_social',
        'situacao_id',
        'tipo_estabelecimento_id',
        'natureza_id',
        'porte_id',
        'endereco',
        'uf',
        'municipio',
        'data_abertura',
        'telefone',
        'email',
        'website',
        'idiomas',
        'tipo_hospedagem_id'
    ];

    protected $casts = [
        'data_abertura' => 'datetime',
    ];

    /**
     * Relacionamento com atividade da empresa
     */
    public function atividade()
    {
        return $this->belongsTo(EmpresaAtividade::class, 'atividade_id');
    }

    /**
     * Relacionamento com situação da empresa
     */
    public function situacao()
    {
        return $this->belongsTo(EmpresaSituacao::class, 'situacao_id');
    }

    /**
     * Relacionamento com tipo de estabelecimento
     */
    public function tipoEstabelecimento()
    {
        return $this->belongsTo(TipoEstabelecimento::class, 'tipo_estabelecimento_id');
    }

    /**
     * Relacionamento com natureza jurídica
     */
    public function natureza()
    {
        return $this->belongsTo(EmpresaNatureza::class, 'natureza_id');
    }

    /**
     * Relacionamento com porte da empresa
     */
    public function porte()
    {
        return $this->belongsTo(EmpresaPorte::class, 'porte_id');
    }

    /**
     * Relacionamento com tipo de hospedagem
     */
    public function tipoHospedagem()
    {
        return $this->belongsTo(TipoHospedagem::class, 'tipo_hospedagem_id');
    }

    /**
     * Relacionamento com usuários que trabalham na empresa
     */
    public function usuarios()
    {
        return $this->hasMany(User::class, 'empresa_atual_id');
    }
}
