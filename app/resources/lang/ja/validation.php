<?php

return [

    'required' => ':attribute は必須です。',

    'email' => ':attribute の形式が正しくありません。',

    'min' => [
        'string' => ':attribute は :min 文字以上で入力してください。',
    ],

    'confirmed' => ':attribute が一致しません。', // ⭐ 追加
    

    'attributes' => [
        'name' => 'ユーザー名',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'comment_content' => 'コメント'
    ],

];