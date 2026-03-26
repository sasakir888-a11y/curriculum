@extends('layouts.app')

@section('content')

<div class="container">
    <form method="GET" action="{{ url('/') }}" class="mb-4">

        <div class="d-flex align-items-center gap-2 flex-wrap">

            <!-- キーワード -->
            <input type="text"
                name="keyword"
                class="form-control"
                style="max-width: 400px;"
                placeholder="キーワード検索"
                value="{{ request('keyword') }}">

            <!-- 開始日 -->
            <input type="date"
                name="from_date"
                class="form-control"
                style="max-width: 170px;"
                value="{{ request('from_date') }}">

            <span>〜</span>

            <!-- 終了日 -->
            <input type="date"
                name="to_date"
                class="form-control"
                style="max-width: 170px;"
                value="{{ request('to_date') }}">

            <!-- 検索 -->
            <button class="btn btn-primary">
                検索
            </button>

            <!-- クリア -->
            <a href="{{ url('/') }}"
            class="btn btn-secondary">
                クリア
            </a>

        </div>

    </form>

    @if(request()->filled('keyword') || request()->filled('from_date') || request()->filled('to_date'))

        @if($questions->total() > 0)

            <p>
                検索結果：{{ $questions->total() }} 件
            </p>

        @else

            <p>該当する結果はありません</p>

        @endif

    @endif

    <h2 class="mb-4">質問一覧</h2>

    @auth
        <a href="{{ url('/question/create') }}" class="btn btn-primary mb-3">
            質問投稿
        </a>
    @endauth


    @forelse ($questions as $q)
        <div class="card mb-3">
            <div class="card-body">

                <div class="row">

                    <!-- 左側 -->
                    <div class="col-md-8 d-flex flex-column">

                        {{-- タイトル --}}
                        <h4>
                            <a href="{{ url('/question/' . $q->id) }}">
                                {{ $q->title }}
                            </a>
                        </h4>

                        {{-- 本文 --}}
                        <p class="flex-grow-1">
                            {{ \Illuminate\Support\Str::limit($q->content, 120) }}
                        </p>

                        {{-- 投稿者 + ボタン --}}
                        <div class="d-flex gap-3 align-items-center">

                            <div class="me-2 rounded-circle overflow-hidden"
                                style="width:35px; height:35px; background:#eee;
                                        display:flex; align-items:center; justify-content:center;">

                                @if($q->user && $q->user->profile_image)

                                    <img src="{{ asset('storage/'.$q->user->profile_image) }}"
                                        style="width:100%; height:100%; object-fit:cover;">

                                @else

                                    <i class="bi bi-person-fill"
                                    style="font-size:20px; color:#aaa;"></i>

                                @endif

                            </div>

                            <p class="text-muted mb-0">
                                投稿者：{{ $q->user->name ?? '不明' }}
                                ｜ 投稿日：{{ $q->created_at->format('Y-m-d') }}
                            </p>

                            @auth
                            <button class="bookmark-btn border-0 bg-transparent p-0"
                                    data-id="{{ $q->id }}">

                                <i class="bi {{ ($q->isBookmarked ?? false)
                                    ? 'bi-bookmark-fill text-warning'
                                    : 'bi-bookmark text-secondary' }}"
                                style="font-size: 1.4rem;"></i>

                            </button>
                            @endauth

                        </div>

                    </div>

                    <!-- 右：画像 -->
                    <div class="col-md-4 text-end">

                        @if($q->image_path)
                            <img src="{{ asset('storage/'.$q->image_path) }}"
                                class="img-fluid rounded"
                                style="max-height:150px; object-fit:cover;">
                        @endif

                    </div>

                </div>

            </div>
        </div>
    @empty
        <p>質問はまだありません</p>
    @endforelse


    <!-- ページネーション -->
    <div class="mt-4">
        {{ $questions->links() }}
    </div>

</div>
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

</script>