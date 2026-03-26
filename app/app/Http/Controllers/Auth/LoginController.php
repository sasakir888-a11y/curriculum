<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    

    protected function redirectTo()
{
    $user = Auth::user();

    if ($user->role == 1) {
        return '/admin';     // 管理者
    }

    return '/';    // 一般ユーザー
}

    protected function credentials(Request $request)
{
    return [
        'email' => $request->email,
        'password' => $request->password,
        'stop_flg' => 0,
        'del_flg'  => 0,
    ];
}


protected function sendFailedLoginResponse(Request $request)
{
    $user = \App\User::where('email', $request->email)->first();

    if ($user) {

        if ($user->del_flg) {
            $msg = 'このアカウントは退会済みです。';
        }
        elseif ($user->stop_flg) {
            $msg = 'このアカウントは利用停止されています。';
        }
        else {
            $msg = 'メールアドレスまたはパスワードが違います。';
        }

    } else {
        $msg = 'メールアドレスまたはパスワードが違います。';
    }

    return redirect()->back()
        ->withInput($request->only('email'))
        ->withErrors(['email' => $msg]);
}
}
