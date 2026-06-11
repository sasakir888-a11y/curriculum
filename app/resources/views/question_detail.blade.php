@extends('layouts.app')

@section('content')

@php
    $canPost = auth()->check() && auth()->user()->role == 0;
@endphp

<div class="container">
    @if(session('question_success'))
        <div class="alert alert-success">
            {{ session('question_success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <h2>{{ $question->title }}</h2>
                @if($question->status === 'open')
                    <span class="badge bg-success text-white">回答募集中</span>
                @elseif($question->status === 'solved')
                    <span class="badge bg-primary text-white">解決済み</span>
                @endif
            </div>

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 text-muted mb-3 pb-3 border-bottom">
                
                <!-- 左側：アバター・名前・投稿日 -->
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle overflow-hidden border" style="width:35px; height:35px; background:#eee; display:flex; align-items:center; justify-content:center;">
                        @if($question->user && $question->user->profile_image)
                            <img src="{{ asset('storage/'.$question->user->profile_image) }}" style="width:100%; height:100%; object-fit:cover;">
                        @else
                            <i class="bi bi-person-fill" style="font-size:20px; color:#aaa;"></i>
                        @endif
                    </div>
                    <span class="small">
                        投稿者：{{ $question->user->name ?? '不明' }}
                        ｜ 投稿日：{{ $question->created_at->format('Y-m-d H:i') }}（{{ $question->created_at->diffForHumans() }}）
                    </span>
                </div>

                <!-- 右側：操作ボタン（ブックマーク、違反報告、編集、削除） -->
                @if($canPost)
                    <div class="d-flex align-items-center gap-2">
                        <button class="bookmark-btn border-0 bg-transparent p-0 me-1" data-id="{{ $question->id }}">
                            <i class="bi {{ $isBookmarked ? 'bi-bookmark-fill text-warning' : 'bi-bookmark text-secondary' }}" style="font-size: 1.4rem;"></i>
                        </button>

                        @if(Auth::id() !== $question->user_id)
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reportQuestionModal">
                                違反報告
                            </button>
                        @endif

                        @if(Auth::id() == $question->user_id)
                            <a href="/question/edit/{{ $question->id }}" class="btn btn-warning btn-sm">編集</a>
                            <form method="POST" action="/question/delete/{{ $question->id }}" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">削除</button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>

            <!-- 📝 質問の本文と画像 -->
            <p>{{ $question->content }}</p>

            @if($question->image_path)
                <img src="{{ asset('storage/'.$question->image_path) }}" class="img-fluid mt-2" style="max-width: 400px;">
            @endif

            <!-- 🚨 質問の違反報告モーダル（見た目に影響しないよう外側に配置） -->
            <div class="modal fade" id="reportQuestionModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="/report/question/{{ $question->id }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title text-dark">質問の違反報告</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-start">
                                <label class="form-label text-dark">報告理由</label>
                                <select name="reason" class="form-select" required>
                                    <option value="">選択してください</option>
                                    <option value="spam">スパム・広告</option>
                                    <option value="abuse">暴言・誹謗中傷</option>
                                    <option value="illegal">違法・不適切</option>
                                    <option value="other">その他</option>
                                </select>
                                <label class="form-label mt-3 text-dark">詳細（任意）</label>
                                <textarea name="comment" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger">報告する</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>

    @if($canPost)
        @if($question->status === 'open')
            <h4>回答する</h4>
            @if(session('answer_success'))
                <div class="alert alert-success">{{ session('answer_success') }}</div>
            @endif
            @error('content')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @if ($errors->answer->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->answer->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="/answer/{{ $question->id }}" enctype="multipart/form-data" novalidate>
                @csrf
                <textarea name="content" class="form-control" rows="4" placeholder="回答を入力" required>{{ old('content') }}</textarea>
                <input type="file" name="image" class="form-control mt-2">
                <button class="btn btn-success mt-2">回答投稿</button>
            </form>
        @else
            <div class="alert alert-secondary text-center">
                <i class="bi bi-lock-fill"></i> この質問は解決済みのため、新しい回答の投稿は締め切られました。
            </div>
        @endif
        <hr>
    @endif

    <h4>回答一覧</h4>

    @foreach ($answers as $answer)
        <!-- 👑 【変更】ベストアンサーに選ばれている場合は、カードの枠線をゴールド（border-warning）にして少し太く（border-2）する -->
        <div class="card mb-3 {{ $answer->is_best_answer ? 'border-warning border-2 shadow-sm' : '' }}">
            <div class="card-body">
                
                <!-- 👑 【追加】ベストアンサー専用のゴールドバッジ（王冠マーク付き） -->
                @if($answer->is_best_answer)
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark fw-bold p-2">
                            <i class="bi bi-star-fill"></i> ベストアンサー
                        </span>
                    </div>
                @endif

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 text-muted mb-3 pb-3 border-bottom">
                    
                    <!-- 左側：アバター・名前・投稿日 -->
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle overflow-hidden border" style="width:35px; height:35px; background:#eee; display:flex; align-items:center; justify-content:center;">
                            @if($answer->user && $answer->user->profile_image)
                                <img src="{{ asset('storage/'.$answer->user->profile_image) }}" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <i class="bi bi-person-fill" style="font-size:20px; color:#aaa;"></i>
                            @endif
                        </div>
                        <span class="small">
                            投稿者：{{ $answer->user->name ?? '不明' }}
                            ｜ 投稿日：{{ $answer->created_at->format('Y-m-d H:i') }}（{{ $answer->created_at->diffForHumans() }}）
                        </span>
                    </div>

                    <!-- 右側：操作ボタン（ベストアンサー、違反報告、編集、削除） -->
                    @if($canPost)
                        <div class="d-flex align-items-center gap-2">
                            @if($question->status === 'open' && Auth::id() == $question->user_id && Auth::id() !== $answer->user_id)
                                <form method="POST" action="{{ url('/question/'.$question->id.'/best-answer/'.$answer->id) }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="bi bi-check-circle-fill"></i> ベストアンサーに選ぶ
                                    </button>
                                </form>
                            @endif

                            @if(Auth::id() !== $answer->user_id)
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#reportAnswerModal{{ $answer->id }}">
                                    違反報告
                                </button>
                            @endif

                            @if(Auth::id() == $answer->user_id)
                                <div class="mt-0 d-flex gap-2">
                                    <a href="/answer/edit/{{ $answer->id }}" class="btn btn-warning btn-sm">編集</a>
                                    <form method="POST" action="/answer/delete/{{ $answer->id }}" style="display:inline;" class="m-0">
                                        @csrf
                                        <button class="btn btn-danger btn-sm">削除</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- 📝 回答の本文と画像 -->
                <p>{{ $answer->content }}</p>

                @if($answer->image_path)
                    <img src="{{ asset('storage/'.$answer->image_path) }}" class="img-fluid mt-2 d-block" style="max-width: 400px;">
                @endif

                <!-- 💬 コメント一覧 -->
                <div class="comments-area ms-4 mt-3 pt-2 border-top">
                    @if($answer->comments && $answer->comments->count() > 0)
                        <h6 class="text-muted small fw-bold">コメント一覧</h6>
                        @foreach($answer->comments as $comment)
                            <div class="p-2 mb-2 bg-light rounded small">
                                <p class="mb-1 text-dark">{{ $comment->content }}</p>
                                <span class="text-muted" style="font-size: 0.75rem;">
                                    投稿者：{{ $comment->user->name ?? '不明' }} 
                                    ｜ {{ $comment->created_at->format('Y-m-d H:i') }}
                                </span>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- ✍️ コメント入力フォーム（回答募集中の時だけ表示） -->
                @if($canPost && $question->status === 'open')
                    <form method="POST" action="/comment/{{ $answer->id }}" class="comment-form ms-4 mt-2">
                        @csrf
                        <div class="input-group input-group-sm">
                            <input type="text" name="comment_content" class="form-control" placeholder="コメントを入力..." required>
                            <button class="btn btn-outline-secondary" type="submit">送信</button>
                        </div>
                    </form>
                @endif

                <!-- 🚨 各回答の違反報告モーダル -->
                <div class="modal fade" id="reportAnswerModal{{ $answer->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="/report/answer/{{ $answer->id }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">回答の違反報告</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">報告理由</label>
                                    <select name="reason" class="form-select" required>
                                        <option value="">選択してください</option>
                                        <option value="spam">スパム・広告</option>
                                        <option value="abuse">暴言・誹謗中傷</option>
                                        <option value="illegal">違法・不適切</option>
                                        <option value="other">その他</option>
                                    </select>
                                    <label class="form-label mt-3">詳細（任意）</label>
                                    <textarea name="comment" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger">報告する</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 💬 コメントのAjax送信処理
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // 🌟 画面が上に戻る（リロード）のを強制ストップ！

            const currentForm = this;
            const actionUrl = currentForm.action;
            const inputField = currentForm.querySelector('input[name="comment_content"]');
            const commentContent = inputField.value;
            
            // コメントが表示されるエリア（フォームの直前にある一覧枠）を取得
            const commentsArea = currentForm.closest('.card-body').querySelector('.comments-area');

            fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest' // Laravel側にAjaxだと知らせる
                },
                body: JSON.stringify({
                    comment_content: commentContent
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // 1. 新しいコメントのHTMLを作成
                    const newCommentHtml = `
                        <div class="p-2 mb-2 bg-light rounded small">
                            <p class="mb-1 text-dark">${data.comment.content}</p>
                            <span class="text-muted" style="font-size: 0.75rem;">
                                投稿者：${data.comment.user_name} ｜ ${data.comment.created_at}
                            </span>
                        </div>
                    `;
                    
                    // 最初は「コメント一覧」の文字タイトルがない場合もあるのでケア
                    if (!commentsArea.querySelector('h6')) {
                        commentsArea.innerHTML = '<h6 class="text-muted small fw-bold">コメント一覧</h6>';
                    }

                    // 2. 画面をリロードせずに、その場に新しいコメントを継ぎ足す！
                    commentsArea.insertAdjacentHTML('beforeend', newCommentHtml);
                    
                    // 3. 入力欄を空っぽにする
                    inputField.value = '';

                    // 4. すでにある通報用のトースト等を使って「投稿しました」と通知する
                    if (typeof showReportToast === 'function') {
                        showReportToast('コメントを投稿しました！');
                    }
                }
            })
            .catch(() => {
                alert('コメントの送信に失敗しました。');
            });
        });
    });


    document.querySelectorAll('.bookmark-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            fetch('/bookmark/' + id, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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

@endsection
