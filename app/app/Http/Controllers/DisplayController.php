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
    // 一覧
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

        // 🔄【追加】新機能のモデルスコープをここで呼び出す（ステータス・ソート）
        // $request->only(['status', 'sort']) で画面からの選択値だけを渡します
        $query->filter($request->only(['status', 'sort']));

        // ⭐ ページネーション（既存のOrderByはスコープ側で制御するため削除し、安定化）
        $questions = $query->paginate(10);

        // ⭐ ログイン時：ブックマーク状態付与
        if (Auth::check()) {
            $bookmarkIds = Bookmark::where('user_id', Auth::id())
                ->pluck('question_id')
                ->toArray();

            foreach ($questions as $q) {
                $q->isBookmarked = in_array($q->id, $bookmarkIds);
            }
        }

        // ⚡【追加】もしAjax（無限スクロールの裏側通信）からの要求なら、追加分のリストHTMLだけを返す
        if ($request->ajax()) {
            return view('partials.question_list_items', compact('questions'))->render();
        }

        // 最初は今まで通り全体画面（home）を表示する
        return view('home', compact('questions'));
    }

     // 🆕 質問詳細
    public function show($id)
    {
        $question = Question::findOrFail($id);

        // 📈【追加】閲覧数を1増やす処理（インクリメント）
        $question->increment('view_count');

        // 👑【変更】ベストアンサー（1）を最優先で一番上に、それ以外（0）は投稿日順で並べる！
        $answers = Answer::where('question_id', $id)
            ->where('is_visible', 1)
            ->orderBy('is_best_answer', 'desc') // is_best_answerが1のデータを最上位に固定
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

    // ⭐ ベストアンサー決定処理
    public function selectBestAnswer(Request $request, $id, $answerId)
    {
        // 1. データベースから対象の質問を取得
        $question = Question::findOrFail($id);

        // 2. 安全対策：ログインユーザーがこの質問の投稿者本人かチェック
        if (Auth::id() !== $question->user_id) {
            abort(403, '権限がありません。');
        }

        // 3. 質問のステータスを「解決済み(solved)」に更新
        $question->update(['status' => 'solved']);

        // 👑【追加】選ばれた特定の回答データを見つけて、is_best_answer フラグを 1 に更新する
        $answer = Answer::findOrFail($answerId);
        $answer->update(['is_best_answer' => 1]);

        return back()->with('question_success', 'ベストアンサーを決定し、質問を解決済みにしました！');
    }
}