<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interpretacoes extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'xml',
        'json',
        'status',
        'resultado',
        'job'
    ];

    protected $casts = [
        'resultado' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
