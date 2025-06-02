<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'rule_positions', 'rule_id', 'position_id')
            ->using(RulePosition::class)
            ->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'rule_permissions', 'rule_id', 'permission_id')
            ->using(RulePermission::class)
            ->withTimestamps();
    }
}
