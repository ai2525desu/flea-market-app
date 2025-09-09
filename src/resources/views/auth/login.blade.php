@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<!-- コントローラーのフラッシュメッセージで設定 -->
@if(session('errorMessage'))
<div class="login-alert">
    <div class="login-alert__error">
        <!-- フラッシュメッセージ設定後文章削除でコメントアウト部分復活させること！ -->
        <!-- ログイン情報が登録されていません。 -->
        {{ session('errorMessage') }}
    </div>
</div>
@endif
<div class="login-form__content">
    <div class="login-form__form-wrap">
        <h2 class="login-form__heading">
            ログイン
        </h2>
        <form class="login-form__form" action="{{ route('auth.login') }}" method="post" novalidate>
            @csrf
            <div class="login-form__group">
                <div class="login-form__group">
                    <div class="login-form__group--title">
                        <label for="email" class="login-form__group--label">
                            メールアドレス
                        </label>
                    </div>
                    <div class="login-form__group--content">
                        <input id="email" type="email" name="email" value="{{ old('email') }}">
                    </div>
                    <div class="login-form__group--error">
                        @error('email')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="login-form__group">
                    <div class="login-form__group--title">
                        <label for="password" class="login-form__group--label">
                            パスワード
                        </label>
                    </div>
                    <div class="login-form__group--content">
                        <input id="password" type="password" name="password">
                    </div>
                    <div class="login-form__group--error">
                        @error('password')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="login-form__button">
                    <button class="login-form__button--submit" type="submit">
                        ログインする
                    </button>
                </div>
        </form>
    </div>
    <div class="login-form__content--screen-transition">
        <a class="screen-transition__register" href="{{ route('auth.register') }}">
            会員登録はこちら
        </a>
    </div>
</div>
@endsection