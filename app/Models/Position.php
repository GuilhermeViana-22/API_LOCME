<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'positions';
    protected $fillable = [
        'position',
        'nivel_hierarquico',
        'departamento',
        'descricao'
    ];

    // Relacionamento com rules através da tabela intermediária rule_positions
    public function rules()
    {
        return $this->belongsToMany(Rule::class, 'rule_positions', 'position_id', 'rule_id')
            ->withTimestamps();
    }

    // Relacionamento direto com a tabela intermediária
    public function rulePositions()
    {
        return $this->hasMany(RulePosition::class, 'position_id');
    }

    // Relacionamento com permissions através de rules (caso seja necessário)
    public function permissions()
    {
        return $this->hasManyThrough(
            Permission::class,
            RulePermission::class,
            'rule_id', // Foreign key on RulePermission table
            'id',       // Foreign key on Permission table
            'id',       // Local key on Position table
            'permission_id' // Local key on RulePermission table
        );
    }
}
