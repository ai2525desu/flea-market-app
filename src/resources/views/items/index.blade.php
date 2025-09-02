@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
<div class="product-list-content__wrap">
    <div class="product-list-tab__header">
        <ul class="product-list-tab__heading">
            <li class="product-list-tab__item">
                <a class="item__product-list {{ $tab === 'recommendation' ? 'is-active' : '' }}" href="{{ route('items.index', ['tab' => 'recommendation']) }}">おすすめ</a>
            </li>
            <li class="product-list-tab__item">
                <a class="item__mylist {{ $tab === 'mylist' ? 'is-active' : '' }}" href="{{ route('items.index', ['tab' => 'mylist']) }}">マイリスト</a>
            </li>
        </ul>
    </div>
    <div class="product-list-tab__body">
        <div class="product-list-tab__content {{ $tab === 'recommendation' ? 'is-active' : '' }}">
            <div class="recommendation-product-card__list">
                @foreach ($items as $item)
                <div class="recommendation-product-card__wrap">
                    <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->item_name }}">
                    <p class="recommendation-card__title">{{ $item->item_name }}</p>
                </div>
                @endforeach
            </div>
        </div>
        <div class="product-list-tab__content {{ $tab === 'mylist' ? 'is-active' : '' }}">
            <div class="mylist-product-card__wrap">
                JavascriptでのSoldの文字が出るのは未コーディング状態
                @guest
                <!-- ゲストの場合、何も表示されない -->
                @endguest
                @auth
                @foreach ($items as $item)
                <div class="mylist-product-card__wrap">
                    <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->item_name }}">
                    <p class="mylist-card__title">{{ $item->item_name }}</p>
                </div>
                @endforeach
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection