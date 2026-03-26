<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'question_id',
        'user_id',
        'content',
        'image_path',
        'is_visible'
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function comments()
    {
    return $this->hasMany(AnswerComment::class);
    }
    public function reports()
{
    return $this->hasMany(
        \App\Models\AnswerViolationReport::class,
        'answer_id'
    );
}
}