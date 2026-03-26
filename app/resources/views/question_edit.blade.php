@extends('layouts.app')

@section('content')
<div class="container">

<h2>質問編集</h2>

{{-- ⭐ 成功メッセージ --}}
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- ⭐ エラー表示 --}}
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
      action="/question/update/{{ $question->id }}"
      novalidate>
    @csrf

    {{-- タイトル --}}
    <input type="text"
           name="title"
           value="{{ old('title', $question->title) }}"
           class="form-control mb-2">

    @error('title')
        <div class="text-danger">{{ $message }}</div>
    @enderror


    {{-- 本文 --}}
    <textarea name="content"
              class="form-control mb-2"
              rows="6">{{ old('content', $question->content) }}</textarea>

    @error('content')
        <div class="text-danger">{{ $message }}</div>
    @enderror


    <button class="btn btn-primary">更新</button>

    <a href="/question/{{ $question->id }}"
       class="btn btn-secondary">
       キャンセル
    </a>

</form>

</div>
@endsection