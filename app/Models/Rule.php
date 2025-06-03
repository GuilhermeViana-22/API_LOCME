<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rule extends Model
{
    protected $fillable = ['name', 'description'];

    /**
     * Relacionamento com Position através da tabela rule_positions
     */
    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class, 'rule_positions')
            ->withTimestamps();
    }

    /**
     * Relacionamento com Permission através da tabela rule_permissions
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'rule_permissions')
            ->withTimestamps();
    }
}
