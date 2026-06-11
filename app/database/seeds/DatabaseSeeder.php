<?php

use Illuminate\Database\Seeder;
use App\Models\AnswerComment; // ★これを入れておくと1つのコマンドでまとめて作れます

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 最も末端の「コメント」を100件作る指示を出すだけで、
        // Laravelが設計図の繋がりに従って、自動的に100件の「回答」と100件の「質問」も逆算して作成・紐付けしてくれます！
        factory(AnswerComment::class, 50)->create();
    }
}