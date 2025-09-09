@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="register-form__content">
    <div class="register-form__form-wrap">
        <h2 class="register-form__heading">
            会員登録
        </h2>
        <form class="register-form__form" action="{{ route('auth.register') }}" method="post" novalidate>
            @csrf
            <div class="register-form__group">
                <div class="register-form__group--title">
                    <label for="name" class="register-form__group--label">
                        ユーザー名
                    </label>
                </div>
                <div class="register-form__group--content">
                    <input id="name" type="text" name="name" value="{{ old('name') }}">
                </div>
                <div class="register-form__group--error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="register-form__group">
                <div class="register-form__group--title">
                    <label for="email" class="register-form__group--label">
                        メールアドレス
                    </label>
                </div>
                <div class="register-form__group--content">
                    <input id="email" type="email" name="email" value="{{ old('email') }}">
                </div>
                <div class="register-form__group--error">
                    @error('email')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="register-form__group">
                <div class="register-form__group--title">
                    <label for="password" class="register-form__group--label">
                        パスワード
                    </label>
                </div>
                <div class="register-form__group--content">
                    <input id="password" type="password" name="password">
                </div>
                <div class="register-form__group--error">
                    @error('password')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="register-form__group">
                <div class="register-form__group--title">
                    <label for="password_confirmation" class="register-form__group--label">
                        確認用パスワード
                    </label>
                </div>
                <div class="register-form__group--content">
                    <input id="password_confirmation" type="password" name="password_confirmation">
                </div>
                <div class="register-form__group--error">
                    @error('password_confirmation')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="register-form__button">
                <button class="register-form__button--submit" type="submit">
                    登録する
                </button>
            </div>
        </form>
    </div>
    <div class="register-form__content--screen-transition">
        <a class="screen-transition__login" href="{{ route('auth.login') }}">
            ログインはこちら
        </a>
    </div>
</div>
@endsection