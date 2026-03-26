<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function create()
    {
        return view('question_create');
    }

    public function store(StoreQuestionRequest $request)
{
    // ⭐ 画像保存
    $path = $request->file('image')->store('questions', 'public');

    Question::create([
        'user_id'    => Auth::id(),
        'title'      => $request->title,
        'content'    => $request->content,
        'image_path' => $path,
        'is_visible' => 1
    ]);

    return redirect('/')
           ->with('success', '質問を投稿しました');
}

    // 編集画面
    public function edit($id)
    {
        $question = Question::findOrFail($id);

        if ($question->user_id != Auth::id()) {
            abort(403);
        }

        return view('question_edit', compact('question'));
    }

    // 更新
    public function update(UpdateQuestionRequest $request, $id)
{
    $question = Question::findOrFail($id);

    // ⭐ 本人のみ
    if ($question->user_id != Auth::id()) {
        abort(403);
    }

    $question->title   = $request->title;
    $question->content = $request->content;
    $question->save();

    return redirect('/question/' . $id)
           ->with('success', '質問を更新しました');
}

    // 削除
    public function delete($id)
    {
    $question = Question::findOrFail($id);

    if ($question->user_id != Auth::id()) {
        abort(403);
    }

    $question->is_visible = 0; // ⭐ 非表示
    $question->save();

    return redirect('/');
    }

    protected function validator(array $data)
{
    return Validator::make($data, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);
}
    
}