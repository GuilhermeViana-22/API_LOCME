<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Position extends Model
{
    protected $fillable = [
        'position',
        'nivel_hierarquico',
        'departamento',
        'descricao'
    ];

    /**
     * Relacionamento many-to-many com Rule
     */
    public function rules(): BelongsToMany
    {
        return $this->belongsToMany(Rule::class, 'rule_positions')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    /**
     * Relacionamento através de rule_positions
     */
    public function rulePositions()
    {
        return $this->hasMany(RulePosition::class);
    }
}
