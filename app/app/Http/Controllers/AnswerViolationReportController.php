<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AnswerViolationReport;

class AnswerViolationReportController extends Controller
{
    public function report(Request $request, $id)
    {
        AnswerViolationReport::create([
            'user_id'   => Auth::id(),
            'answer_id' => $id,
            'reason'    => $request->reason,
            'comment'   => $request->comment
        ]);

        return back()->with('msg', '通報しました');
    }
}