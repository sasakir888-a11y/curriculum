<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\Bookmark;
use App\Models\Answer; // ⭐ 追加


class MypageController extends Controller
{
    public function index()
    {
        // ⭐ 自分の質問
        $questions = Question::where('user_id', Auth::id())->get();

        // ⭐ 自分の回答
        $answers = Answer::where('user_id', Auth::id())->get();

        // ⭐ ブックマークした質問
        $bookmarkIds = Bookmark::where('user_id', Auth::id())
            ->pluck('question_id');

        $bookmarks = Question::whereIn('id', $bookmarkIds)->get();

        return view('mypage', compact(
            'questions',
            'answers',
            'bookmarks'
        ));
    }

    // ⭐ ブックマーク保存
    public function bookmark($question_id)
{
    $bookmark = Bookmark::where('user_id', Auth::id())
        ->where('question_id', $question_id)
        ->first();

    if ($bookmark) {
        $bookmark->delete();
        return response()->json(['status' => 'removed']);
    } else {
        Bookmark::create([
            'user_id' => Auth::id(),
            'question_id' => $question_id
        ]);
        return response()->json(['status' => 'added']);
    }
}
}