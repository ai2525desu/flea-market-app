@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email-content__send-message">
    {{--@if (session('message'))--}}
    <div class="verify-email-content__send-message--success">
        認証メールを送信しました
        {{ session('message') }}
    </div>
    {{--@endif--}}
</div>
<div class="verify-email-content__wrap">
    <div class="verify-email-content__notice">
        <p class="verify-email-content__notice--text">
            登録していただいたメールアドレスに認証メールを送付しました。<br />
            メール認証を完了してください。
        </p>
    </div>
    <!--  -->
    <form class="verify-email-content__form" method="post" action="{{ route('verification.send') }}">
        @csrf
        <div class="verify-email-content__form--first-send-button">
            <button class="first-send-button__submit" type="submit">
                認証はこちらから
            </button>
        </div>
        <div class="verify-email-content__form--resend-email-button">
            <button class="resend-email-button__submit" type="submit">
                認証メールを再送する
        </div>
    </form>
</div>
@endsection