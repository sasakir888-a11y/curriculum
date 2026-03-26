<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\AnswerComment;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;

class DisplayController extends Controller
{
    // 一覧（作成済）
     public function index(Request $request)
    {
        $query = Question::where('is_visible', 1);

        // 🔎 キーワード検索（タイトル＋本文）
        if ($request->filled('keyword')) {

            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        // 📅 投稿日フィルター（期間）

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // ⭐ ページネーション（安定）
        $questions = $query->orderBy('created_at', 'desc')
                           ->paginate(10);

        // ⭐ ログイン時：ブックマーク状態付与
        if (Auth::check()) {

            $bookmarkIds = Bookmark::where('user_id', Auth::id())
                ->pluck('question_id')
                ->toArray();

            foreach ($questions as $q) {
                $q->isBookmarked = in_array($q->id, $bookmarkIds);
            }
        }

        return view('home', compact('questions'));
    }

    // 🆕 質問詳細
    public function show($id)
    {
        $question = Question::findOrFail($id);

        $answers = Answer::where('question_id', $id)
            ->where('is_visible', 1)
            ->orderBy('created_at', 'asc')
            ->get();

        $isBookmarked = false;

            if (Auth::check()) {
                $isBookmarked = Bookmark::where('user_id', Auth::id())
                    ->where('question_id', $question->id)
                    ->exists();
            }

        return view('question_detail', compact(
            'question',
            'answers',
            'isBookmarked'
        ));
    }
}