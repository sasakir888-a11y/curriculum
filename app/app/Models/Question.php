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
    'is_visible',
    'status',      // 追加：プログラムから更新できるようにする
    'view_count'   // 追加：プログラムから更新できるようにする
];
    public function reports()
{
    return $this->hasMany(
        \App\Models\QuestionViolationReport::class,
        'question_id'
    );
}
/**
 * 検索・フィルタリング・ソート用のローカルスコープ
 */
    public function scopeFilter($query, array $filters)
{
    // 1. ステータスフィルタ ('open' または 'solved')
    if ($status = $filters['status'] ?? null) {
        $query->where('status', $status);
    }

    // 2. ソート機能
    $sort = $filters['sort'] ?? 'newest';
    if ($sort === 'newest') {
        $query->latest(); // 作成日時の新しい順
    } elseif ($sort === 'views') {
        $query->orderBy('view_count', 'desc'); // 閲覧数の多い順
    }
}
}