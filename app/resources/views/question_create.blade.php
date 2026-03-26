@extends('layouts.app')

@section('content')
<div class="container">

    <h2>質問投稿</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form method="POST"
      action="/question/store"
      enctype="multipart/form-data"
      novalidate>
    @csrf

    <div class="form-group">
        <label>タイトル</label>
        <input type="text"
               name="title"
               class="form-control"
               value="{{ old('title') }}">
    </div>

    <div class="form-group mt-3">
        <label>内容</label>
        <textarea name="content"
                  class="form-control"
                  rows="5">{{ old('content') }}</textarea>
    </div>

    <div class="form-group mt-3">
        <label>画像（必須）</label>
        <input type="file"
               name="image"
               class="form-control">
    </div>

    <button class="btn btn-primary mt-3">
        投稿する
    </button>

</form>

</div>
@endsection