<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
{
    $user = Auth::user();

    // ⭐ 管理者なら管理画面へ
    if ($user->role == 1) {
        return redirect('/admin');
    }

    // ⭐ 一般ユーザーは質問一覧
    return redirect('/questions');
}
}
