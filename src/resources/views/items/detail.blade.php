@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/detail.css') }}">
@endsection

@section('content')
<div class="detail-content__wrap">
    <div class="detail-content__product-image">
        <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->item_name }}">
    </div>
    <div class="detail-content__product-introduction">
        <h2 class="product-introduction__heading">{{ $item->item_name }}</h2>
        <p class="product-introduction__brand">ブランド名：{{ $item->brand }}</p>
        <div class="product-introduction__price">
            <span class="product-introduction__price--symbol">¥</span>
            <span class="product-introduction__price--amount">
                {{ $item->price }}
            </span>
            <span class="product-introduction__price--symbol">(税込)</span>
        </div>
        <div class="product-introduction__count">
            <div class="product-introduction__count--likes">
                <form class="likes-form" method="post" action="{{ route('items.like', ['item_id' => $item->id]) }}">
                    @csrf
                    <button class="likes-form__button" type="submit">
                        <img src="{{ asset('storage/star.png') }}" art="いいね">
                    </button>
                    <span class="product-introduction__count-number">{{ $item->likes->count() }}</span>
                </form>
            </div>
            <div class="product-introduction__count--comments">
                <!-- method,action後で記述 -->
                <form class="comments-form">
                    <button class="comments-form__button" type="submit">
                        <img src="{{ asset('storage/speech-bubble.png') }}" art="コメント">
                    </button>
                    <span class="product-introduction__count-number">{{ $item->comments->count() }}</span>
                </form>
            </div>
        </div>
    </div>
    <div class="detail-content__screen-transition">
        <a class="screen-transition__purchase" href="{{ route('purchases.show', ['item_id' => $item->id]) }}">購入手続きへ</a>
    </div>
    <div class="detail-content__description">
        <h2 class="detail-content__description-heading">商品説明</h2>
        <p class="detail-content__description-content">{{ $item->description }}</p>
    </div>
    <div class="detail-content__information">
        <h2 class="detail-content__information-heading">
            商品の情報
        </h2>
        <div class="detail-content__information-category">
            <p class="category__title">カテゴリー</p>
            <div class="category__item-wrap">
                @foreach ($item->categories as $category)
                <span class="category__item">{{ $category->category_name }}</span>
                @endforeach
            </div>
        </div>
        <div class="detail-content__information-condition">
            <p class="condition__title">商品の状態</p>
            <div class="condition__item-wrap">
                <span class="condition__item">{{ $condition }}</span>
            </div>
        </div>
    </div>
    <div class="detail-content__comment">
        <h2 class="comment__heading">コメント+count()</h2>
        <div class="comment__existing-comment">
            ここにすでにコメントされている内容が表示される
            ユーザー画像＋名前
            コメント内容
        </div>
        <div class="comment__new-comment">
            <!-- action未設定 -->
            <form class="new-comment__form" action="" method="post" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="new-comment__form-title">
                    <label class="new-comment__form-label">商品へのコメント</label>
                </div>
                <div class="new-comment__form-content">
                    <textarea name="comment_content">{{ old('comment_content') }}</textarea>
                </div>
                <div class="new-comment__form-button">
                    <button class="form-button__submit" type="submit">コメントを送信する</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection