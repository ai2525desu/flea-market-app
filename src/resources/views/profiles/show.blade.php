@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profiles/show.css') }}">
@endsection

@section('content')
<div class="profile-show-content__wrap">
    <div class="profile-show-content__user-information">
        <div class="user-information__profile-image">
            <img src="{{  asset('storage/' . $user->profile?->image) }}" class="profile-image">
        </div>
        <p class="user-information__user-name">
            {{ $user->name }}
        </p>
        <div class="user-information__screen-transition">
            <a class="screen-transition__edit" href="{{ route('profiles.edit') }}">プロフィールを編集
            </a>
        </div>
    </div>
    <div class="profile-show-content__product-information--header">
        出品した商品と購入した商品のタブ、ここはＪＳで画面切り替え
    </div>
    <div class="profile-show-content__product-information--item">
        各ユーザーの出品と購入商品の内容
    </div>
</div>
@endsection