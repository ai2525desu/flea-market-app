@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profiles/edit.css') }}">
@endsection

@section('content')
<div class="profile-edit-form__content">
    <div class="profile-edit-form__form-wrap">
        <h2 class="profile-edit-form__heading">
            プロフィール設定
        </h2>
        <!-- いったん初回ログイン登録時のmethod="post"を作成 -->
        <form class="profile-edit-form__form" action="{{ route('profiles.edit') }}" method="post" novalidate>
            @csrf
            <div class="profile-edit-form__group">
                <div class="profile-edit-form__group--content">
                    <!-- imageの登録処理 -->
                    <input type="file" name="image">
                </div>
                <div class="profile-edit-form__group--error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="profile-edit-form__group">
                <div class="profile-edit-form__group--title">
                    <label for="user_name" class="profile-edit-form__group--label">
                        ユーザー名
                    </label>
                </div>
                <div class="profile-edit-form__group--content">
                    <input id="user_name" type="text" name="name" value="{{ $user->name }}">
                </div>
                <div class="profile-edit-form__group--error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="profile-edit-form__button">
                <button type="submit" class="profile-edit-form__button--submit">
                    更新する
                </button>
            </div>
        </form>
    </div>
</div>
@endsection