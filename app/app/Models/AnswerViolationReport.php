<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerViolationReport extends Model
{
    protected $fillable = [
        'user_id',
        'answer_id',
        'reason',
        'comment'
    ];

    public function answer()
    {
        return $this->belongsTo(\App\Models\Answer::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}