@extends('layouts.app')

@section('content')

@php
    $canPost = auth()->check() && auth()->user()->role == 0;
@endphp

<div class="container">
<div class="container">

    <!-- 質問 -->
    <div class="card mb-4">
        <div class="card-body">

            <h2>{{ $question->title }}</h2>

            <p class="text-muted mb-1">
                <div class="me-2 rounded-circle overflow-hidden"
                    style="width:35px; height:35px; background:#eee; display:flex; align-items:center; justify-content:center;">

                    @if($question->user && $question->user->profile_image)
                        <img src="{{ asset('storage/'.$question->user->profile_image) }}"
                            style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <i class="bi bi-person-fill" style="font-size:20px; color:#aaa;"></i>
                    @endif

                </div>

                投稿者：{{ $question->user->name ?? '不明' }}
                ｜ 投稿日：{{ $question->created_at->format('Y-m-d H:i') }}
                （{{ $question->created_at->diffForHumans() }}）
            </p>

            <p>{{ $question->content }}</p>

            @if($question->image_path)
                <img src="{{ asset('storage/'.$question->image_path) }}"
                    class="img-fluid mt-2"
                    style="max-width: 400px;">
            @endif


            {{-- ⭐ ここが重要：card-bodyの中 --}}
            @if($canPost)
<div class="d-flex justify-content-end align-items-center gap-2 mt-3">

    {{-- ブックマーク --}}
    <button class="bookmark-btn border-0 bg-transparent p-0"
            data-id="{{ $question->id }}">
        <i class="bi {{ $isBookmarked ? 'bi-bookmark-fill text-warning' : 'bi-bookmark text-secondary' }}"
           style="font-size: 1.4rem;"></i>
    </button>

    {{-- 違反報告 --}}
    @if(Auth::id() !== $question->user_id)
        <button class="btn btn-sm btn-outline-danger"
                data-bs-toggle="modal"
                data-bs-target="#reportQuestionModal">
            違反報告
        </button>
    @endif
               

                <div class="modal fade"
                    id="reportQuestionModal"
                    tabindex="-1">

                <div class="modal-dialog">
                    <div class="modal-content">

                    <form method="POST"
                            action="/report/question/{{ $question->id }}">
                        @csrf

                        <div class="modal-header">
                        <h5 class="modal-title">質問の違反報告</h5>
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                        {{-- 理由（選択式） --}}
                        <label class="form-label">報告理由</label>

                        <select name="reason"
                                class="form-select"
                                required>
                            <option value="">選択してください</option>
                            <option value="spam">スパム・広告</option>
                            <option value="abuse">暴言・誹謗中傷</option>
                            <option value="illegal">違法・不適切</option>
                            <option value="other">その他</option>
                        </select>

                        {{-- コメント --}}
                        <label class="form-label mt-3">
                            詳細（任意）
                        </label>

                        <textarea name="comment"
                                    class="form-control"
                                    rows="3"></textarea>

                        </div>

                        <div class="modal-footer">
                        <button class="btn btn-danger">
                            報告する
                        </button>
                        </div>

                    </form>

                    </div>
                </div>
                </div>


                {{-- 自分の投稿 --}}
    @if(Auth::id() == $question->user_id)
        <a href="/question/edit/{{ $question->id }}"
           class="btn btn-warning btn-sm">
            編集
        </a>

        <form method="POST"
              action="/question/delete/{{ $question->id }}"
              class="m-0">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">
                削除
            </button>
        </form>
    @endif


            </div>
            @endif

        </div>
    </div>
    <hr>

    <!-- ⭐ 回答投稿フォーム -->
@if($canPost)
<h4>回答する</h4>

{{-- 成功メッセージ --}}
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- エラー表示 --}}
@if ($errors->answer->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->answer->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST"
      action="/answer/{{ $question->id }}"
      enctype="multipart/form-data"
      novalidate>
    @csrf

    <textarea name="content"
            class="form-control"
            rows="4"
            placeholder="回答を入力"
            required>{{ old('content') }}</textarea>

    {{-- ⭐ 画像（任意） --}}
    <input type="file"
        name="image"
        class="form-control mt-2">

    <button class="btn btn-success mt-2">
        回答投稿
    </button>
</form>


<hr>
@endif

    <!-- 回答一覧 -->
    <h4>回答一覧</h4>

    @foreach ($answers as $answer)

    <div class="card mb-3">
        <div class="card-body">

            {{-- 投稿者・日時 --}}
            <p class="text-muted mb-1">
                <div class="me-2 rounded-circle overflow-hidden"
                    style="width:35px; height:35px; background:#eee; display:flex; align-items:center; justify-content:center;">

                    @if($answer->user && $answer->user->profile_image)
                        <img src="{{ asset('storage/'.$answer->user->profile_image) }}"
                            style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <i class="bi bi-person-fill" style="font-size:20px; color:#aaa;"></i>
                    @endif

                </div>

                投稿者：{{ $answer->user->name ?? '不明' }}
                ｜ 投稿日：{{ $answer->created_at->format('Y-m-d H:i') }}
                （{{ $answer->created_at->diffForHumans() }}）
            </p>

            {{-- 本文 --}}
            <p>{{ $answer->content }}</p>

            {{-- ⭐ 回答画像 --}}
            @if($answer->image_path)
                <img src="{{ asset('storage/'.$answer->image_path) }}"
                    class="img-fluid mt-2 d-block"
                    style="max-width: 400px;">
            @endif

           @if($canPost)

    @if(Auth::id() !== $answer->user_id)
        <button class="btn btn-sm btn-outline-danger mt-2"
                data-bs-toggle="modal"
                data-bs-target="#reportAnswerModal{{ $answer->id }}">
            違反報告
        </button>
    @endif

    @if(Auth::id() == $answer->user_id)
        <div class="mt-2">
            <a href="/answer/edit/{{ $answer->id }}"
               class="btn btn-warning btn-sm">
                編集
            </a>

            <form method="POST"
                  action="/answer/delete/{{ $answer->id }}"
                  style="display:inline;">
                @csrf
                <button class="btn btn-danger btn-sm">
                    削除
                </button>
            </form>
        </div>
    @endif

@endif

            <div class="modal fade"
                id="reportAnswerModal{{ $answer->id }}"
                tabindex="-1">

            <div class="modal-dialog">
                <div class="modal-content">

                <form method="POST"
                        action="/report/answer/{{ $answer->id }}">
                    @csrf

                    <div class="modal-header">
                    <h5 class="modal-title">回答の違反報告</h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                    <label class="form-label">報告理由</label>

                    <select name="reason"
                            class="form-select"
                            required>
                        <option value="">選択してください</option>
                        <option value="spam">スパム・広告</option>
                        <option value="abuse">暴言・誹謗中傷</option>
                        <option value="illegal">違法・不適切</option>
                        <option value="other">その他</option>
                    </select>

                    <label class="form-label mt-3">
                        詳細（任意）
                    </label>

                    <textarea name="comment"
                                class="form-control"
                                rows="3"></textarea>

                    </div>

                    <div class="modal-footer">
                    <button class="btn btn-danger">
                        報告する
                    </button>
                    </div>

                </form>

                </div>
            </div>
            </div>

            <!-- ⭐ コメント（回答ごと） -->
            <h6 class="mt-3">コメント</h6>

            @foreach ($answer->comments as $comment)
                <div class="border p-2 mb-2">

                    <small class="text-muted">
                        {{ $comment->user->name ?? '不明' }}
                        ｜ {{ $comment->created_at->format('Y-m-d H:i') }}
                        （{{ $comment->created_at->diffForHumans() }}）
                    </small>

                    <p class="mb-0 mt-1">
                        {{ $comment->content }}
                    </p>

                </div>
            @endforeach


            <!-- ⭐ コメント投稿フォーム -->
            @if($canPost)
<h6 class="mt-2">コメントする</h6>

<form method="POST" action="/comment/{{ $answer->id }}">
    @csrf

    <textarea name="comment_content"
              class="form-control mb-1"
              rows="2">{{ old('comment_content') }}</textarea>

    @error('comment_content')
        <div class="text-danger small">{{ $message }}</div>
    @enderror

    <button class="btn btn-sm btn-primary mt-1">
        投稿
    </button>
</form>
@endif

        </div>
    </div>

    @endforeach
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.bookmark-btn').forEach(btn => {

        btn.addEventListener('click', function () {

            const id = this.dataset.id;

            fetch('/bookmark/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN':
                        document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {

                const icon = this.querySelector('i');

                if (data.status === 'added') {

                    icon.classList.remove('bi-bookmark', 'text-secondary');
                    icon.classList.add('bi-bookmark-fill', 'text-warning');

                    showToast('ブックマークに追加しました');

                } else {

                    icon.classList.remove('bi-bookmark-fill', 'text-warning');
                    icon.classList.add('bi-bookmark', 'text-secondary');

                    showToast('ブックマークを解除しました');

                }

            });

        });

    });

});


// ⭐⭐⭐ これを追加 ⭐⭐⭐
function showToast(message) {

    const toastEl = document.getElementById('bookmarkToast');
    const messageEl = document.getElementById('toastMessage');

    if (!toastEl) return;

    messageEl.textContent = message;

    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}

function showReportToast(message) {

    const toastEl = document.getElementById('reportToast');
    const msgEl = document.getElementById('reportToastMessage');

    if (!toastEl) return;

    msgEl.textContent = message;

    const toast = new bootstrap.Toast(toastEl);
    toast.show();
}


</script>
@if(session('msg'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    showReportToast("{{ session('msg') }}");
});
</script>
@endif