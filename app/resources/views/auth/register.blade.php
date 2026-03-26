@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">新規登録</div>

                <div class="card-body">
                    <form method="POST"
                          action="{{ route('register') }}"
                          novalidate>
                        @csrf

                        {{-- ユーザー名 --}}
                        <div class="form-group row">
                            <label for="name"
                                   class="col-md-4 col-form-label text-md-right">
                                ユーザー名
                            </label>

                            <div class="col-md-6">
                                <input id="name"
                                       type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       name="name"
                                       value="{{ old('name') }}"
                                       autocomplete="name"
                                       autofocus>

                                @error('name')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- メール --}}
                        <div class="form-group row">
                            <label for="email"
                                   class="col-md-4 col-form-label text-md-right">
                                メールアドレス
                            </label>

                            <div class="col-md-6">
                                <input id="email"
                                       type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       name="email"
                                       value="{{ old('email') }}"
                                       autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- パスワード --}}
                        <div class="form-group row">
                            <label for="password"
                                   class="col-md-4 col-form-label text-md-right">
                                パスワード
                            </label>

                            <div class="col-md-6">
                                <input id="password"
                                       type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       name="password"
                                       autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- パスワード確認 --}}
                        <div class="form-group row">
                            <label for="password-confirm"
                                   class="col-md-4 col-form-label text-md-right">
                                パスワード確認
                            </label>

                            <div class="col-md-6">
                                <input id="password-confirm"
                                       type="password"
                                       class="form-control"
                                       name="password_confirmation"
                                       autocomplete="new-password">
                            </div>
                        </div>

                        {{-- 登録ボタン --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">

                                <button type="submit"
                                        class="btn btn-primary">
                                    登録する
                                </button>

                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection