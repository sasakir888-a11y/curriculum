@extends('layouts.app')

@section('content')
<div class="container">

    {{-- ============================= --}}
    {{-- ⭐ プロフィールカード --}}
    {{-- ============================= --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body text-center">

            {{-- アイコン --}}
            @if(Auth::user()->profile_image)
                <img src="{{ asset('storage/'.Auth::user()->profile_image) }}"
                     width="120"
                     height="120"
                     class="rounded-circle mb-3 border"
                     style="object-fit: cover;">
            @else
                <i class="bi bi-person-circle"
                   style="font-size:120px;color:#ccc;"></i>
            @endif

            {{-- 名前 --}}
            <h3 class="mb-1">{{ Auth::user()->name }}</h3>

            {{-- メール --}}
            <p class="text-muted mb-2">{{ Auth::user()->email }}</p>

            {{-- bio --}}
            @if(Auth::user()->bio)
                <p class="mb-3">{{ Auth::user()->bio }}</p>
            @else
                <p class="text-muted mb-3">自己紹介はまだありません</p>
            @endif

            {{-- 投稿数 --}}
            <div class="row text-center mt-3">

                <div class="col">
                    <h5>{{ $questions->count() }}</h5>
                    <small class="text-muted">質問</small>
                </div>

                <div class="col">
                    <h5>{{ $answers->count() }}</h5>
                    <small class="text-muted">回答</small>
                </div>

                <div class="col">
                    <h5>{{ $bookmarks->count() }}</h5>
                    <small class="text-muted">ブックマーク</small>
                </div>

            </div>

            <a href="/profile"
               class="btn btn-outline-primary btn-sm px-4 mt-3">
                プロフィール編集
            </a>

        </div>
    </div>

    {{-- ============================= --}}
    {{-- ⭐ タブメニュー --}}
    {{-- ============================= --}}
    <ul class="nav nav-tabs">

        <li class="nav-item">
            <button class="nav-link active"
                    data-bs-toggle="tab"
                    data-bs-target="#questions">
                質問 ({{ $questions->count() }})
            </button>
        </li>

        <li class="nav-item">
            <button class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#answers">
                回答 ({{ $answers->count() }})
            </button>
        </li>

        <li class="nav-item">
            <button class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#bookmarks">
                ブックマーク ({{ $bookmarks->count() }})
            </button>
        </li>

    </ul>

    {{-- ============================= --}}
    {{-- ⭐ タブ内容 --}}
    {{-- ============================= --}}
    <div class="tab-content mt-3">

        {{-- ================= 質問 ================= --}}
        <div class="tab-pane fade show active" id="questions">

            @forelse ($questions as $q)
            <div class="card mb-3">
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-8 d-flex flex-column">

                            <h5>
                                <a href="{{ url('/question/' . $q->id) }}">
                                    {{ $q->title }}
                                </a>
                            </h5>

                            <p class="flex-grow-1">
                                {{ \Illuminate\Support\Str::limit($q->content, 120) }}
                            </p>

                            <p class="text-muted mb-0">
                                投稿日：{{ $q->created_at->format('Y-m-d') }}
                            </p>

                        </div>

                        <div class="col-md-4 text-end">
                            @if($q->image_path)
                                <img src="{{ asset('storage/'.$q->image_path) }}"
                                     class="img-fluid rounded"
                                     style="max-height:150px;object-fit:cover;">
                            @endif
                        </div>

                    </div>

                </div>
            </div>
            @empty
                <p class="text-muted">質問はまだありません</p>
            @endforelse

        </div>

        {{-- ================= 回答 ================= --}}
        <div class="tab-pane fade" id="answers">

            @forelse ($answers as $answer)
            <div class="card mb-3">
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-8 d-flex flex-column">

                            <p class="flex-grow-1">
                                {{ \Illuminate\Support\Str::limit($answer->content, 120) }}
                            </p>

                            <div class="d-flex align-items-center gap-3">

                                <p class="text-muted mb-0">
                                    投稿日：{{ $answer->created_at->format('Y-m-d') }}
                                </p>

                                <a href="/question/{{ $answer->question_id }}"
                                   class="btn btn-outline-primary btn-sm">
                                    元の投稿を見る
                                </a>

                            </div>

                        </div>

                        <div class="col-md-4 text-end">
                            @if($answer->image_path)
                                <img src="{{ asset('storage/'.$answer->image_path) }}"
                                     class="img-fluid rounded"
                                     style="max-height:150px;object-fit:cover;">
                            @endif
                        </div>

                    </div>

                </div>
            </div>
            @empty
                <p class="text-muted">回答はまだありません</p>
            @endforelse

        </div>

        {{-- ================= ブックマーク ================= --}}
        <div class="tab-pane fade" id="bookmarks">

            @forelse ($bookmarks as $b)
            <div class="card mb-3">
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-8 d-flex flex-column">

                            <h5>
                                <a href="{{ url('/question/' . $b->id) }}">
                                    {{ $b->title }}
                                </a>
                            </h5>

                            <p class="flex-grow-1">
                                {{ \Illuminate\Support\Str::limit($b->content, 120) }}
                            </p>

                            <p class="text-muted mb-0">
                                投稿日：{{ $b->created_at->format('Y-m-d') }}
                            </p>

                        </div>

                        <div class="col-md-4 text-end">
                            @if($b->image_path)
                                <img src="{{ asset('storage/'.$b->image_path) }}"
                                     class="img-fluid rounded"
                                     style="max-height:150px;object-fit:cover;">
                            @endif
                        </div>

                    </div>

                </div>
            </div>
            @empty
                <p class="text-muted">ブックマークはありません</p>
            @endforelse

        </div>

    </div>

</div>
@endsection