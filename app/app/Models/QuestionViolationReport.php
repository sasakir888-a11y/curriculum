<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionViolationReport extends Model
{
    protected $fillable = [
        'user_id',
        'question_id',
        'reason',
        'comment'
    ];

    public $timestamps = false;
}