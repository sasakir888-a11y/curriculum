<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsBestAnswerToAnswersTable extends Migration
{
    public function up()
    {
        Schema::table('answers', function (Blueprint $table) {
            // boolean型（0か1）。デフォルトは0（ベストアンサーではない）
            $table->boolean('is_best_answer')->default(0)->after('question_id');
        });
    }

    public function down()
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->dropColumn('is_best_answer');
        });
    }
}