@extends('layouts.app')

@section('content')
<div class="container">

    <h2>管理画面</h2>
    @php
    $reasons = [
        'spam' => 'スパム・広告',
        'abuse' => '暴言・誹謗中傷',
        'illegal' => '違法・不適切',
        'other' => 'その他'
    ];
    @endphp
    <ul class="nav nav-tabs mb-3">

    <li class="nav-item">
        <button class="nav-link active"
                data-bs-toggle="tab"
                data-bs-target="#q">
            通報質問
        </button>
    </li>

    <li class="nav-item">
        <button class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#a">
            通報回答
        </button>
    </li>

    <li class="nav-item">
        <button class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#u">
            ユーザー
        </button>
    </li>

</ul>
<div class="tab-content">
    <div class="tab-pane fade show active" id="q">

    <hr>
        <h3>通報された質問</h3>

        @forelse ($reportedQuestions as $q)

        <div class="card mb-2">
            <div class="card-body">

                <h5>{{ $q->title }}</h5>
                <p>{{ $q->content }}</p>

                {{-- ⭐ 通報一覧 --}}
                <p class="text-danger mb-1">
                    通報内容（{{ $q->reports->count() }}件）
                </p>

                @foreach ($q->reports as $r)

                <div class="border rounded p-2 mb-2 bg-light">


                    理由：
                    <strong>{{ $reasons[$r->reason] ?? $r->reason }}</strong>

                    @if($r->comment)
                        <div class="small text-muted">
                            詳細：{{ $r->comment }}
                        </div>
                    @endif

                    <div class="small text-muted">
                        通報日時：
                        {{ $r->created_at ? $r->created_at->format('Y-m-d H:i') : '' }}
                    </div>

                </div>

                @endforeach

                {{-- ⭐ 状態 --}}
                状態：
                @if ($q->is_visible)
                    <span class="text-success">表示中</span>
                @else
                    <span class="text-danger">非表示</span>
                @endif

                {{-- ⭐ 非表示ボタン --}}
                <form method="POST" action="{{ url('/admin/hide/' . $q->id) }}">
                    @csrf
                    <button class="btn btn-danger btn-sm">
                        非表示にする
                    </button>
                </form>

            </div>
        </div>

        @empty
            <p>通報された質問はありません</p>
        @endforelse

        </div>
        <div class="tab-pane fade" id="a">
        <h3>通報された回答</h3>

        @forelse ($reportedAnswers as $a)

        <div class="card mb-3">
            <div class="card-body">

                {{-- 投稿者・日時 --}}
                <p class="text-muted mb-1">
                    投稿者：{{ $a->user->name ?? '不明' }}
                    ｜ {{ $a->created_at->format('Y-m-d H:i') }}
                </p>

                {{-- 本文 --}}
                <p class="mb-2">{{ $a->content }}</p>

                {{-- ⭐ 通報一覧 --}}
                <p class="text-danger mb-1">
                    通報内容（{{ $a->reports->count() }}件）
                </p>

                @foreach ($a->reports as $r)

                <div class="border rounded p-2 mb-2 bg-light">

                    理由：
                    <strong>{{ $reasons[$r->reason] ?? $r->reason }}</strong>

                    @if($r->comment)
                        <div class="small text-muted">
                            詳細：{{ $r->comment }}
                        </div>
                    @endif

                    <div class="small text-muted">
                        通報日時：
                        {{ $r->created_at ? $r->created_at->format('Y-m-d H:i') : '' }}
                    </div>

                </div>

                @endforeach


                {{-- ⭐ 状態 --}}
                <p>
                    状態：
                    @if ($a->is_visible)
                        <span class="text-success">表示中</span>
                    @else
                        <span class="text-danger">非表示</span>
                    @endif
                </p>


                {{-- ⭐ 操作ボタン --}}
                <div class="d-flex gap-2 align-items-center">

                    <a href="/question/{{ $a->question_id }}"
                    class="btn btn-primary btn-sm">
                        質問へ
                    </a>

                    @if ($a->is_visible)
                        <form method="POST"
                            action="/admin/answer/hide/{{ $a->id }}"
                            class="m-0 d-inline">
                            @csrf
                            <button type="submit"
                                    class="btn btn-danger btn-sm">
                                非表示にする
                            </button>
                        </form>
                    @endif

                </div>

            </div>
        </div>

        @empty
            <p>通報された回答はありません</p>
        @endforelse

        </div>
        <div class="tab-pane fade" id="u">
        <h3>👤 ユーザー一覧</h3>

@forelse ($users as $u)
    <div class="card mb-2">
        <div class="card-body">

            名前：{{ $u->name }} <br>
            メール：{{ $u->email }} <br>

            停止投稿数：
            {{ $u->hidden_questions_count + $u->hidden_answers_count }}<br>

            権限：
            @if($u->role == 1)
                <span class="text-danger">管理者</span>
            @else
                <span class="text-secondary">一般</span>
            @endif
            <br>

            状態：
            @if($u->stop_flg)
                <span class="text-danger">利用停止中</span>
            @else
                <span class="text-success">利用可能</span>
            @endif

            <hr>

            {{-- ⭐ 利用停止ボタン --}}
            <form method="POST"
                  action="/admin/user/stop/{{ $u->id }}"
                  class="d-inline">
                @csrf

                @if($u->stop_flg)
                    <button class="btn btn-success btn-sm">
                        利用停止解除
                    </button>
                @else
                    <button class="btn btn-danger btn-sm">
                        利用停止
                    </button>
                @endif
            </form>

            {{-- ⭐ 管理者権限ボタン --}}
            @if($u->id !== Auth::id()) {{-- 自分は変更不可 --}}
            <form method="POST"
                action="/admin/user/{{ $u->id }}/role"
                class="d-inline ms-2">
                @csrf

                @if($u->role == 1)
                    <button class="btn btn-warning btn-sm">
                        一般に戻す
                    </button>
                @else
                    <button class="btn btn-primary btn-sm">
                        管理者にする
                    </button>
                @endif
            </form>
            @else
                <span class="text-muted ms-2">※自分は変更不可</span>
            @endif

        </div>
    </div>
@empty
    <p>ユーザーはいません</p>
@endforelse

        </div>
        </div> {{-- tab-content --}}

</div>
@endsection
<script>
document.addEventListener("DOMContentLoaded", function () {

    // 保存されたタブを復元
    const activeTab = localStorage.getItem("adminActiveTab");
    if (activeTab) {
        const tab = document.querySelector(`[data-bs-target="${activeTab}"]`);
        if (tab) new bootstrap.Tab(tab).show();
    }

    // タブクリック時に保存
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (e) {
            localStorage.setItem(
                "adminActiveTab",
                e.target.getAttribute("data-bs-target")
            );
        });
    });

});
</script>