<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $table = 'rules';

    protected $fillable = [
        'name',
        'description',
    ];


    public function permissions()
{
    return $this->belongsToMany(Permission::class, 'rule_permissions');
}
}
