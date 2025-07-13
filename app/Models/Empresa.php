<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'Empresas';

    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'cnpj',
        'nome_empresa',
        'telefone',
        'email_contato',
        'url',
        'cadastur',
        'condicoes_especiais',
        'condicoes',

        'nome_cadastro',
        'cargo_cadastro',

        'endereco',
        'cidade',
        'estado',
        'cep',
        'pais',
    ];

    CONST TIPO_PERFIL_ID = TipoPerfil::TIPO_EMPRESA_ENTIDADE; // 5

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'condicoes_especiais' => 'boolean',
    ];

    /**
     * Relacionamento com as atividades através do perfil
     */
    public function atividades()
    {
        return $this->hasManyThrough(
            Atividade::class,
            PerfilAtividade::class,
            'perfil_id',
            'id',
            'id',
            'atividade_id'
        )->where('tipo_perfil_id', self::TIPO_PERFIL_ID);
    }

    /**
     * Relacionamento com os produtos/serviços através do perfil
     */
    public function produtosServicos()
    {
        return $this->hasManyThrough(
            ProdutoServico::class,
            PerfilProdutoServico::class,
            'perfil_id',
            'id',
            'id',
            'produto_id'
        )->where('tipo_perfil_id', self::TIPO_PERFIL_ID);
    }

    /**
     * Relacionamento com as unidades/localidades através do perfil
     */
    public function unidadesLocalidades()
    {
        return $this->hasManyThrough(
            UnidadeLocalidade::class,
            PerfilUnidadeLocalidade::class,
            'perfil_id',
            'id',
            'id',
            'unidade_localidade_id'
        )->where('tipo_perfil_id', self::TIPO_PERFIL_ID);
    }

    /**
     * Relacionamento direto com o perfil_atividade (para casos onde precisa acessar os dados pivot)
     */
    public function perfilAtividades()
    {
        return $this->hasMany(PerfilAtividade::class, 'perfil_id')
            ->where('tipo_perfil_id', self::TIPO_PERFIL_ID);
    }

    /**
     * Relacionamento direto com o perfil_produto_servico (para acessar dados pivot)
     */
    public function perfilProdutosServicos()
    {
        return $this->hasMany(PerfilProdutoServico::class, 'perfil_id')
            ->where('tipo_perfil_id', self::TIPO_PERFIL_ID);
    }

    /**
     * Relacionamento direto com o perfil_unidade_localidade
     */
    public function perfilUnidadesLocalidades()
    {
        return $this->hasMany(PerfilUnidadeLocalidade::class, 'perfil_id')
            ->where('tipo_perfil_id', self::TIPO_PERFIL_ID);
    }
}
