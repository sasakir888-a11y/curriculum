<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Question;
use App\Models\Answer;
use App\Models\QuestionViolationReport;
use App\Models\AnswerViolationReport;
use App\User;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::user()->role != 1) abort(403);

        // 通常の質問
        $questions = Question::latest()->get();

        // 通報一覧（質問）
        $reports = QuestionViolationReport::latest()->get();

        // 🚨 通報された質問
        $reportedQuestionIds =
            QuestionViolationReport::pluck('question_id');

        $reportedQuestions =
            Question::withCount('reports')
                ->having('reports_count', '>', 0)
                ->orderByDesc('reports_count')
                ->limit(10)
                ->get();

        // 🚨 通報された回答
        $reportedAnswerIds =
            AnswerViolationReport::pluck('answer_id');

        $reportedAnswers =
            Answer::withCount('reports')
                ->having('reports_count', '>', 0)
                ->orderByDesc('reports_count')
                ->limit(10)
                ->get();

        // 👤 ユーザー一覧
        $users =
            User::withCount([
                'questions as hidden_questions_count' =>
                    fn($q) => $q->where('is_visible', 0),

                'answers as hidden_answers_count' =>
                    fn($q) => $q->where('is_visible', 0),
            ])
            ->orderByRaw('(hidden_questions_count + hidden_answers_count) DESC')
            ->limit(10)
            ->get();

        return view('admin', compact(
            'questions',
            'reports',
            'reportedQuestions',
            'reportedAnswers',
            'users'
        ));
    }


    public function hide($id)
    {
        $q = Question::findOrFail($id);
        $q->is_visible = 0;
        $q->save();

        return back();
    }
    public function hideAnswer($id)
    {
    $a = Answer::findOrFail($id);
    $a->is_visible = 0;
    $a->save();

    return back();
    }
    

    public function stopUser($id)
    {
        $user = User::findOrFail($id);

        // ON/OFF 切り替え
        $user->stop_flg = !$user->stop_flg;
        $user->save();

        return back()->with('msg', 'ユーザー状態を変更しました');
    }

    // public function makeAdmin()
    // {
    // $user = User::find(Auth::id());

    // $user->role = 1; // admin の代わり
    // $user->save();

    // return "管理者になりました 👑";
    // }
    public function changeRole($id)
{
    // admin1 が操作中
    if (Auth::user()->role !== 1) abort(403);

    $user = User::findOrFail($id); // 変更対象
    if ($user->id === Auth::id()) return back(); // 自分は変更不可

    $user->role = ($user->role == 1) ? 0 : 1; // トグル
    $user->save();

    return back(); // admin1 の画面に戻る
}
}