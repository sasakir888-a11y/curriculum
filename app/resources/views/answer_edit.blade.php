@extends('layouts.app')

@section('content')
<div class="container">

<h2>回答編集</h2>

{{-- 成功メッセージ --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- エラー表示 --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST"
      action="/answer/update/{{ $answer->id }}"
      enctype="multipart/form-data"
      novalidate>
    @csrf

    {{-- 回答本文 --}}
    <div class="mb-3">
        <label class="form-label">回答内容</label>
        <textarea name="content"
                  class="form-control"
                  rows="5">{{ old('content', $answer->content) }}</textarea>

        @error('content')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- 現在の画像 --}}
    @if($answer->image_path)
        <div class="mb-3">
            <label class="form-label">現在の画像</label><br>
            <img src="{{ asset('storage/'.$answer->image_path) }}"
                 class="img-fluid rounded"
                 style="max-width: 300px;">
        </div>
    @endif

    {{-- 新しい画像 --}}
    <div class="mb-3">
        <label class="form-label">画像を変更（任意）</label>
        <input type="file"
               name="image"
               class="form-control">

        @error('image')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">
        更新
    </button>

    <a href="/question/{{ $answer->question_id }}"
       class="btn btn-secondary">
        キャンセル
    </a>
</form>

</div>
@endsection