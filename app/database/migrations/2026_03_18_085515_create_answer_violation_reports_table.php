<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerViolationReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_violation_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id'); // 報告者id
            $table->integer('answer_id'); // 回答id
            $table->string('reason', 100); // 報告理由
            $table->string('comment', 500)->nullable(); // 詳細コメント
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
        Schema::dropIfExists('answer_violation_reports');
    }
}
