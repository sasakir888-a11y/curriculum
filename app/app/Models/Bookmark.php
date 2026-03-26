<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $fillable = [
        'user_id',
        'question_id'
    ];

    public $timestamps = false; // ← bookmarksテーブルにupdated_at無い
}