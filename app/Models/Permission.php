<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = ['name', 'description'];

    /**
     * Relacionamento many-to-many com Rule
     */
    public function rules(): BelongsToMany
    {
        return $this->belongsToMany(Rule::class, 'rule_permissions')
            ->withTimestamps();
    }
}
