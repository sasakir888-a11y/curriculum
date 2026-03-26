<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Question extends Model
{
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    protected $fillable = [
    'user_id',
    'title',
    'content',
    'image_path',
    'is_visible'
];
    public function reports()
{
    return $this->hasMany(
        \App\Models\QuestionViolationReport::class,
        'question_id'
    );
}
}