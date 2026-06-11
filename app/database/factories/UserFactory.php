<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Models\Question;
use App\Models\Answer;
use App\Models\AnswerComment;


/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});

// ①「投稿」の設計図（本物の列名 content に修正）
$factory->define(Question::class, function (Faker $faker) {
    return [
        'user_id'    => 1, // とりあえずテスト用にID「1」のユーザーの投稿にする
        'title'      => $faker->realText(20) . 'について教えてください',
        'content'    => $faker->realText(100), // ★bodyからcontentに変更！
        'image_path' => 'questions/' . $faker->randomElement(['YjDJWcllFgoEGpIHEIG18cMBOKIqezbsTF4rsXYH.jpg', 'yI0knOrPjTJDOuIka0xgaX3OtwwXs08SFTsFjevL.jpg','RS1ubcZXgORwc58SutGXfthhN3pyIAuZMq8wkCJ4.jpg']),
        'is_visible' => 1,                  // 画面に表示するフラグ

    ];
});

// ②「回答」の設計図
$factory->define(Answer::class, function (Faker $faker) {
    return [
        'user_id'     => 1, // とりあえずID「1」のユーザーの回答にする
        'question_id' => factory(Question::class), 
        'content'     => $faker->realText(50) . 'だと思います！', // もし回答側も列名がcontentなら直してください
    ];
});

// ③「コメント」の設計図
$factory->define(AnswerComment::class, function (Faker $faker) {
    return [
        'user_id'   => 1, // とりあえずID「1」のユーザーのコメントにする
        'answer_id' => factory(Answer::class), 
        'content'   => $faker->randomElement(['なるほど！', 'ありがとうございます！', '参考になります。']), // もしコメント側も列名がcontentなら直してください
    ];
});