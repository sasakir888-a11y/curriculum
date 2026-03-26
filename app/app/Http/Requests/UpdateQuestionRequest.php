<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check(); // ログイン必須
    }

    public function rules()
    {
        return [
            'title'   => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'image'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'title.required'   => 'タイトルは必須です',
            'content.required' => '本文は必須です',
            'content.min'      => '本文は10文字以上で入力してください',
            'image.required' => '画像は必須です',
        ];
    }
}
