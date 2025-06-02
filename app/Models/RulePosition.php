<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RulePosition extends Model
{
    use HasFactory;

    protected $table = 'rule_positions';
    protected $fillable = ['position_id', 'rule_id'];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function rule()
    {
        return $this->belongsTo(Rule::class);
    }
}
