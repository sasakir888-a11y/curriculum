<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndViewCountToQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            // status: 'open'（募集中）か 'solved'（解決済み）を管理
            $table->string('status')->default('open')->after('title'); 
            // view_count: 閲覧数。初期値は 0
            $table->unsignedInteger('view_count')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['status', 'view_count']);
        });
    }
}