@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
<div class="product-list-content__wrap">
    <div class="product-list-tab__header">
        <ul class="product-list-tab__heading">
            <li class="product-list-tab__item">
                <a class="item__recommendation {{ $tab !== 'mylist' ? 'is-active' : '' }}" href="{{ route('items.index', ['item_name' => request('item_name')]) }}">おすすめ</a>
            </li>
            <li class=" product-list-tab__item">
                <a class="item__mylist {{ $tab === 'mylist' ? 'is-active' : '' }}" href="{{ route('items.index', ['tab' => 'mylist', 'item_name' => request('item_name')]) }}">マイリスト</a>
            </li>
        </ul>
    </div>
    <div class="product-list-tab__body">
        <div class="product-list-tab__content {{ $tab !== 'mylist' ? 'is-active' : '' }}">
                <div class="product-card__list">
                    @foreach ($items as $item)
                    <a class="product-card__detail-link" href="{{ route('items.detail', ['item_id' => $item->id]) }}">
                        <div class="product-card__wrap">
                            <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->item_name }}">
                            <div class="product-card__text">
                                <p class="product-card__text--title">{{ $item->item_name }}</p>
                                @if ($item->purchase)
                                <span class="product-card__text--sold-display">Sold</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
        </div>
        <div class="product-list-tab__content {{ $tab === 'mylist' ? 'is-active' : '' }}">
            <div class="product-card__list">
                @foreach ($items as $item)
                <a class="product-card__detail-link" href="{{ route('items.detail', ['item_id' => $item->id]) }}">
                    <div class="product-card__wrap">
                        <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->item_name }}">
                        <div class="product-card__text">
                            <p class="product-card__text--title">{{ $item->item_name }}</p>
                            @if ($item->purchase)
                            <span class="product-card__text--sold-display">Sold</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endsection