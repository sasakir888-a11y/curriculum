<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Q&A掲示板</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ドロップダウンの余白ゼロ化 */
        .navbar-nav .dropdown-menu {
            margin-top: 0 !important;  /* 上の余白を削除 */
            min-width: auto !important; /* 幅を親リンクに合わせる */
        }

        /* ドロップダウン内のボタンも幅100%に */
        .navbar-nav .dropdown-menu .dropdown-item {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            width: 100%;
        }

        /* アイコンと名前を縦中央に揃える */
        .navbar-nav .dropdown-toggle {
            display: flex;
            align-items: center;
        }
    </style>
</head>

<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Q&A掲示板</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- 左空 -->
            <ul class="navbar-nav me-auto"></ul>

            <!-- 右側 -->
            <ul class="navbar-nav ms-auto align-items-center">

                @auth
                    @if(Auth::user()->role != 1)
                        <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">質問一覧</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/question/create') }}">質問投稿</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ url('/mypage') }}">マイページ</a></li>
                    @endif
                    @if(Auth::user()->role === 1)
                        <li class="nav-item"><a class="nav-link" href="{{ url('/admin') }}">管理画面</a></li>
                    @endif

                    {{-- アイコン＋名前＋ログアウト横並び --}}
                    <li class="nav-item d-flex align-items-center ms-3">
                        {{-- アイコン --}}
                        @if(Auth::user()->profile_image)
                            <img src="{{ asset('storage/'.Auth::user()->profile_image) }}"
                                 width="32" height="32"
                                 class="rounded-circle me-2"
                                 style="object-fit: cover;">
                        @else
                            <i class="bi bi-person-circle me-2" style="font-size:32px;color:#ccc;"></i>
                        @endif

                        {{-- 名前 --}}
                        <span class="me-3">{{ Auth::user()->name }}</span>

                        {{-- ログアウトボタン --}}
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                ログアウト
                            </button>
                        </form>
                    </li>

                @endauth

                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">ログイン</a></li>
                    @if(Route::has('register'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">新規登録</a></li>
                    @endif
                @endguest

            </ul>
        </div>
    </div>
</nav>
    <main class="py-4">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="bookmarkToast" class="toast text-bg-dark border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">メッセージ</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>

    <div id="reportToast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="reportToastMessage">通報しました</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
</body>
</html>