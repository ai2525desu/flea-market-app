@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/email-verification.css') }}">
@endsection

@section('content')
<div class="email-verificatio-form__wrap">
    <form class="email-verificatio-form__form" method="" action="">
        <p class="email-verificatio-form__notice">
            登録していただいたメールアドレスに認証メールを送付しました。
            メール認証を完了してください。
        </p>
        <div class="email-verificatio-form__button">
            <button class="email-verificatio-form__button--submit" type="submit">
                認証はこちらから
            </button>
        </div>
        <div class="email-verificatio-form__resend-email">
            <!-- 何タグにするか不明なためdivで囲ってあるだけ -->
            認証メールを再送する
        </div>
    </form>
</div>
@endsection