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

        $comment = AnswerComment::create([
            'answer_id' => $answer_id,
            'user_id'   => Auth::id(),
            'content'   => trim($request->comment_content),
        ]);

        // ⚡【追加】もしAjax通信なら、保存したデータをJSONで返して終了する
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'comment' => [
                    'content' => $comment->content,
                    'user_name' => Auth::user()->name,
                    'created_at' => $comment->created_at->format('Y-m-d H:i')
                ]
            ]);
        }

        // 通常の送信（一応残しておく）
        return back();
    }
}