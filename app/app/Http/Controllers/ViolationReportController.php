<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuestionViolationReport;

class ViolationReportController extends Controller
{
    // ⭐ 質問の通報
    public function reportQuestion(Request $request, $id)
    {
        QuestionViolationReport::create([
            'user_id' => Auth::id(),
            'question_id' => $id,
            'reason' => $request->reason,
            'comment' => $request->comment
        ]);

        return back()->with('msg', '通報しました');
    }
}