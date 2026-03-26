<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnswerRequest extends FormRequest
{
    public function authorize()
{
    return auth()->check();
}

public function rules()
{
    return [
        'content' => 'required|string|min:5|max:5000',
        'image'   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ];
}

public function messages()
    {
        return [
            'content.required' => '回答内容を入力してください',
            'content.min'      => '回答は5文字以上で入力してください',
        ];
    }
}
