<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('answer_id'); // 回答id
            $table->integer('user_id'); // コメント者id
            $table->string('content', 500); // コメント内容
            $table->timestamps(); // ※定義書では更新日時不要とありますが、Laravel標準で作成します [3]
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer_comments');
    }
}
