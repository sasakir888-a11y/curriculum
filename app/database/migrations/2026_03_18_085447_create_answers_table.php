<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('question_id'); // 質問id
            $table->integer('user_id'); // 回答者id
            $table->text('content'); // 回答本文
            $table->string('image_path', 255)->nullable(); // 画像パス
            $table->tinyInteger('is_visible')->default(1); // 表示状態
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
