<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RulePosition extends Model
{
    protected $table = 'rule_positions';

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function rule()
    {
        return $this->belongsTo(Rule::class);
    }
}
