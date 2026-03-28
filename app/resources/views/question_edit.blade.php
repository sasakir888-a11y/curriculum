@extends('layouts.app')

@section('content')
<div class="container">

    <h2>質問編集</h2>

    {{-- 成功メッセージ --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- エラーメッセージ --}}
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
          action="{{ url('/question/update/' . $question->id) }}"
          enctype="multipart/form-data"
          novalidate>
        @csrf

        {{-- タイトル --}}
        <div class="mb-3">
            <label class="form-label">タイトル</label>
            <input type="text"
                   name="title"
                   value="{{ old('title', $question->title) }}"
                   class="form-control @error('title') is-invalid @enderror">

            @error('title')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- 本文 --}}
        <div class="mb-3">
            <label class="form-label">質問内容</label>
            <textarea name="content"
                      class="form-control @error('content') is-invalid @enderror"
                      rows="6">{{ old('content', $question->content) }}</textarea>

            @error('content')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- 現在画像 --}}
        @if($question->image_path)
            <div class="mb-3">
                <label class="form-label">現在の画像</label><br>
                <img src="{{ asset('storage/'.$question->image_path) }}"
                     class="img-fluid rounded"
                     style="max-width: 300px;">
            </div>
        @endif

        {{-- 画像変更 --}}
        <div class="mb-3">
            <label class="form-label">画像を変更（任意）</label>
            <input type="file"
                   name="image"
                   class="form-control">
        </div>

        <button class="btn btn-primary">更新</button>

        <a href="{{ url('/question/' . $question->id) }}"
           class="btn btn-secondary">
            キャンセル
        </a>

    </form>

</div>
@endsection