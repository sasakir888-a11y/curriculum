@extends('layouts.app')

@section('content')
<div class="container">

<h2>プロフィール編集</h2>

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form method="POST"
      action="/profile/update"
      enctype="multipart/form-data"novalidate>
@csrf

<label>名前</label>
<input type="text"
       name="name"
       value="{{ Auth::user()->name }}"
       class="form-control">

{{-- ⭐ ここに追加 --}}
<label class="mt-2">自己紹介</label>
<textarea name="bio"
          class="form-control"
          rows="4"
          placeholder="自己紹介を入力">{{ Auth::user()->bio }}</textarea>

<label>アイコン画像</label>
<input type="file"
       name="icon"
       class="form-control">

<label class="mt-2">メール</label>
<input type="email"
       name="email"
       value="{{ Auth::user()->email }}"
       class="form-control">

<button class="btn btn-primary mt-3">
更新する
</button>

</form>

<hr>

<!-- 退会 -->
<h4 class="text-danger">退会</h4>

<form method="POST" action="/withdraw">
@csrf

<button class="btn btn-danger"
        onclick="return confirm('本当に退会しますか？');">
退会する
</button>

</form>

</div>
@endsection