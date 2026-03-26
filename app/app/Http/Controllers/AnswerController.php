<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Answer;

class AnswerController extends Controller
{
    public function store(StoreAnswerRequest $request, $question_id)
{
    $path = null;

    // ⭐ 画像があれば保存
    if ($request->hasFile('image')) {
        $path = $request->file('image')
                        ->store('answers', 'public');
    }

    Answer::create([
        'question_id' => $question_id,
        'user_id'     => Auth::id(),
        'content'     => $request->content,
        'image_path'  => $path,
        'is_visible'  => 1
    ]);

    return back()->with('success', '回答を投稿しました');
}

    // 編集画面
    public function edit($id)
    {
        $answer = Answer::findOrFail($id);

        if ($answer->user_id != Auth::id()) {
            abort(403);
        }

        return view('answer_edit', compact('answer'));
    }

    // 更新
    public function update(UpdateAnswerRequest $request, $id)
{
    $answer = Answer::findOrFail($id);

    if ($answer->user_id != Auth::id()) {
        abort(403);
    }

    $answer->content = $request->content;
    $answer->save();

    return redirect('/question/' . $answer->question_id)
           ->with('success', '回答を更新しました');
}

    // 削除
    public function delete($id)
    {
    $answer = Answer::findOrFail($id);

    if ($answer->user_id != Auth::id()) {
        abort(403);
    }

    $answer->is_visible = 0;
    $answer->save();

    return back();
    }   

    
}

