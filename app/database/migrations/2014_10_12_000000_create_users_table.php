<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 50); // 名前
            $table->string('email', 255)->unique(); // メールアドレス
            $table->string('password', 255); // パスワード
            $table->string('profile_image', 255)->nullable(); // プロフィール画像
            $table->string('bio', 500)->nullable(); // 自己紹介文
            $table->tinyInteger('role')->default(0); // 権限 一般=0 / 管理=1
            $table->rememberToken(); // ログイン維持用 [1]
            $table->tinyInteger('del_flg')->default(0); // 退会フラグ
            $table->tinyInteger('stop_flg')->default(0); // 利用停止フラグ
            $table->timestamps(); // created_at, updated_at [9]
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
