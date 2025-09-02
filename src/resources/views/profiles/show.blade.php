@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profiles/show.css') }}">
@endsection

@section('content')
<div class="profile-show-content__wrap">
    <div class="profile-show-content__user-information">
        <div class="user-information__profile-image">
            @if ($user->profile?->image)
            <img src="{{  asset('storage/' . $user->profile->image) }}" class="profile-image">
            @endif
        </div>
        <p class="user-information__user-name">
            {{ $user->name }}
        </p>
        <div class="user-information__screen-transition">
            <a class="screen-transition__edit" href="{{ route('profiles.edit') }}">プロフィールを編集
            </a>
        </div>
    </div>
    <div class="profile-show-content__product-information">
        <div class="product-tab__header" id="product-tab__header">
            <ul class="product-tab__heading">
                <li class="product-tab__item">
                    <a class="item__exhibition-list {{ $tab === 'sell' ? 'is-active' : '' }}" href="{{ route('profiles.show', ['tab' => 'sell']) }}">出品した商品</a>
                </li>
                <li class="product-tab__item">
                    <a class="item__purchase-list {{ $tab === 'buy' ? 'is-active' : '' }}" href="{{ route('profiles.show', ['tab' => 'buy']) }}">購入した商品</a>
                </li>
            </ul>
        </div>
        <div class="product-tab__body" id="product-tab__body">
            <div class="product-tab__content {{ $tab === 'sell' ? 'is-active' : '' }}">
                <div class="exhibition-product-card__list">
                    @foreach ($user->items as $item)
                    <div class="exhibition-product-card__wrap">
                        <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->item_name }}">
                        <p class="exhibition-card__title">{{ $item->item_name }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="product-tab__content {{ $tab === 'buy' ? 'is_active' : '' }}">
                <div class="purchase-product-card__wrap">
                    @foreach ($user->purchases as $purchase)
                    @if ($purchase->item)
                    <div class="purchase-product-card__wrap">
                        <img src="{{ asset('storage/' . $purchase->item->item_image) }}" alt="{{ $purchase->item->item_name }}">
                        <p class="purchase-card__title">{{ $purchase->item->item_name }}</p>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection