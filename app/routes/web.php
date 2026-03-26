<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ViolationReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnswerCommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnswerViolationReportController;


Auth::routes();

// ⭐ 質問一覧（トップ）
Route::get('/', [DisplayController::class, 'index'])->name('home');
Route::get('/home', [DisplayController::class, 'index']);

// ⭐ 質問投稿
Route::get('/question/create', [RegistrationController::class, 'create']);
Route::post('/question/store', [RegistrationController::class, 'store']);

// ⭐ 詳細
Route::get('/question/{id}', [DisplayController::class, 'show']);

// ⭐ 回答投稿
Route::post('/answer/{question_id}', [AnswerController::class, 'store']);

// ⭐ マイページ
Route::get('/mypage', [MypageController::class, 'index']);

// ⭐ ブックマーク
Route::post('/bookmark/{id}', 'MypageController@bookmark')
    ->middleware('auth');

// ⭐ 通報
Route::post('/report/question/{id}', [ViolationReportController::class, 'reportQuestion']);
Route::post('/report/answer/{id}', [AnswerViolationReportController::class, 'report']);

// ⭐ 管理画面
Route::get('/admin', [AdminController::class, 'index'])->middleware('auth');
Route::get('/make-admin', [AdminController::class, 'makeAdmin']);
Route::post('/admin/user/{id}/role', [AdminController::class, 'changeRole'])
    ->middleware('auth');

// ⭐ 非表示
Route::post('/admin/hide/{id}', [AdminController::class, 'hide']);
Route::post('/admin/answer/hide/{id}', [AdminController::class, 'hideAnswer']);

// ⭐ 質問編集
Route::get('/question/edit/{id}', [RegistrationController::class, 'edit']);
Route::post('/question/update/{id}', [RegistrationController::class, 'update']);
Route::post('/question/delete/{id}', [RegistrationController::class, 'delete']);

// ⭐ 回答編集
Route::get('/answer/edit/{id}', [AnswerController::class, 'edit']);
Route::post('/answer/update/{id}', [AnswerController::class, 'update']);
Route::post('/answer/delete/{id}', [AnswerController::class, 'delete']);

// ⭐ コメント
Route::post('/comment/{answer_id}', [AnswerCommentController::class, 'store']);

// ⭐ プロフィール
Route::get('/profile', [ProfileController::class, 'edit'])->middleware('auth');
Route::post('/profile/update', [ProfileController::class, 'update'])->middleware('auth');
Route::post('/withdraw', [ProfileController::class, 'withdraw'])->middleware('auth');

Route::post( '/admin/user/stop/{id}',[AdminController::class, 'stopUser']);
