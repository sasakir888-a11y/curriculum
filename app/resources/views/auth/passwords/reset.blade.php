@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">
                     新しいパスワード設定
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('password.update') }}"
                          novalidate>
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        {{-- メール --}}
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">
                                メールアドレス
                            </label>

                            <div class="col-md-6">
                                <input type="email"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ $email ?? old('email') }}"
                                       readonly>

                                @error('email')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- 新パスワード --}}
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">
                                新しいパスワード
                            </label>

                            <div class="col-md-6">
                                <input type="password"
                                       name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       minlength="8"
                                       autocomplete="new-password">

                                <small class="text-muted">
                                    ※8文字以上で入力してください
                                </small>

                                @error('password')
                                    <span class="invalid-feedback">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- 確認 --}}
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">
                                パスワード確認
                            </label>

                            <div class="col-md-6">
                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control">
                            </div>
                        </div>

                        {{-- ボタン --}}
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit"
                                        class="btn btn-primary">
                                    パスワードを更新する
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