<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class ProfileController extends Controller
{
    // 編集画面
    public function edit()
    {
        return view('profile_edit');
    }

    // 更新
    public function update(Request $request)
{
    $request->validate([
        'name'  => 'required|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        'bio'   => 'nullable|max:500',
        'icon'  => 'nullable|image|max:2048'
    ]);

    $user = User::find(Auth::id());

    $user->name  = $request->name;
    $user->email = $request->email;
    $user->bio   = $request->bio;

    if ($request->file('icon')) {
        $path = $request->file('icon')->store('icons', 'public');
        $user->profile_image = $path;
    }

    $user->save();

    return redirect('/mypage');
}

    // 退会
    public function withdraw()
    {
    $user = User::find(Auth::id());

    // ⭐ 論理削除
    $user->del_flg = 1;
    $user->save();

    Auth::logout();

    return redirect('/');
    }
}