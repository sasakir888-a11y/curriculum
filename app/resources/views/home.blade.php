@extends('layouts.app')

@section('content')

<div class="container">
    <form method="GET" action="{{ url('/') }}" class="mb-4">
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- キーワード -->
            <input type="text" name="keyword" class="form-control" style="max-width: 300px;" placeholder="キーワード検索" value="{{ request('keyword') }}">
            
            <!-- 開始日 -->
            <input type="date" name="from_date" class="form-control" style="max-width: 150px;" value="{{ request('from_date') }}">
            <span>〜</span>
            <!-- 終了日 -->
            <input type="date" name="to_date" class="form-control" style="max-width: 150px;" value="{{ request('to_date') }}">

            <!-- 【追加】ステータス絞り込み -->
            <select name="status" class="form-select" style="max-width: 160px;">
                <option value="">すべての状態</option>
                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>回答募集中</option>
                <option value="solved" {{ request('status') === 'solved' ? 'selected' : '' }}>解決済み</option>
            </select>

            <!-- 【追加】並び替え -->
            <select name="sort" class="form-select" style="max-width: 140px;">
                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>新着順</option>
                <option value="views" {{ request('sort') === 'views' ? 'selected' : '' }}>閲覧数順</option>
            </select>

            <!-- 検索 -->
            <button class="btn btn-primary">検索</button>
            <!-- クリア -->
            <a href="{{ url('/') }}" class="btn btn-secondary">クリア</a>
        </div>
    </form>

    <!-- 【修正】ステータスやソートの指定がある時も、ヒット件数や「該当なし」を正しく表示する -->
    @if(request()->filled('keyword') || request()->filled('from_date') || request()->filled('to_date') || request()->filled('status') || request()->filled('sort'))
        @if($questions->total() > 0)
            <p>検索結果：{{ $questions->total() }} 件</p>
        @else
            <p>該当する結果はありません</p>
        @endif
    @endif

    <h2 class="mb-4">質問一覧</h2>

    @auth
        <a href="{{ url('/question/create') }}" class="btn btn-primary mb-3">質問投稿</a>
    @endauth

    {{-- ★ 質問が追加されていく枠組み（最初は1ページ目をインクルード） --}}
    <div id="questions-wrapper">
        @include('partials.question_list_items')
    </div>

    {{-- ★ 読み込み中のぐるぐるインジケーター（ページ送りボタンの代わりに設置） --}}
    <div id="infinite-scroll-loading" class="text-center my-4" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-muted mt-2">追加の質問を読み込み中...</p>
    </div>

</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {

    // 1. 🆕 ブックマークボタンのイベント登録（イベントデリゲーション方式）
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.bookmark-btn');
        if (!btn) return;

        const id = btn.dataset.id;

        fetch('/bookmark/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            const icon = btn.querySelector('i');
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

    // 2. ⚡ 無限スクロール（純粋な生のJavaScript + Fetch API版）
    let page = 1;
    let hasMorePages = {{ $questions->hasMorePages() ? 'true' : 'false' }};
    let isLoading = false;

    const wrapper = document.getElementById('questions-wrapper');
    const loadingIndicator = document.getElementById('infinite-scroll-loading');
    const queryParams = new URLSearchParams(window.location.search);

    window.addEventListener('scroll', function () {
        if ((window.innerHeight + window.scrollY) >= document.documentElement.scrollHeight - 200) {
            
            if (isLoading || !hasMorePages) return;

            isLoading = true;
            page++;
            loadingIndicator.style.display = 'block'; 

            queryParams.set('page', page);

            fetch('?' + queryParams.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' 
                }
            })
            .then(res => res.text())
            .then(html => {
                if (!html.includes('question-item')) {
                    hasMorePages = false;
                    loadingIndicator.innerHTML = '<p class="text-muted">すべての質問を読み込みました</p>';
                    return;
                }

                wrapper.insertAdjacentHTML('beforeend', html);
                isLoading = false;
                loadingIndicator.style.display = 'none';
            })
            .catch(() => {
                alert('追加データの読み込みに失敗しました。');
                isLoading = false;
                loadingIndicator.style.display = 'none';
            });
        }
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
</script>
