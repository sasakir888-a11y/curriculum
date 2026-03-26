<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AnswerComment;

class AnswerCommentController extends Controller
{
    public function store(Request $request, $answer_id)
    {
        $request->validate([
            'comment_content' => 'required|string|max:1000',
        ]);

        AnswerComment::create([
            'answer_id' => $answer_id,
            'user_id'   => Auth::id(),
            'content'   => trim($request->comment_content),
        ]);

        return back();
    }
}