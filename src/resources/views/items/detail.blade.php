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
        <p>{{ $item->brand }}</p>
        <div class="product-introduction__count">
            <!-- ここがいいね機能になるとすれば、formでmethodPOSTかな？ -->
            <div class="product-introduction__count--likes">
                <img src="{{ asset('storage/star.png') }}" art="いいね">
                <span>いいねの数</span>
            </div>
            <div class="product-introduction__count--comments">
                <img src="{{ asset('storage/speech-bubble.png') }}" art="コメント">
                <span>コメントの数</span>
            </div>
        </div>
    </div>
    <div class="detail-content__screen-transition">
        <a class="screen-transition__purchase" href="{{ route('purchases.show', ['item_id' => $item->id]) }}">購入手続きへ</a>
    </div>
    <div class="detail-content__description">
        <h2 class="description__heading">商品説明</h2>
        <p>{{ $item->description }}</p>
    </div>
    <div class="detail-content__information">
        <div class="detail-content__information--category">
            <p>カテゴリー</p>
            @foreach ($item->categories as $category)
            <span>{{ $category->category_name }}</span>
            @endforeach
        </div>
        <div class="detail-content__information--condition">
            <p>商品の状態</p>
            <span>{{ $condition }}</span>
        </div>
    </div>
    <div class="detail-content__information--comment">
        <h2 class="comment__heading">コメント＋その数のカウント数</h2>
        <div class="comment__existing-comment">
            ここにすでにコメントされている内容が表示される
            ユーザー画像＋名前
            コメント内容
        </div>
        <div class="comment__new-comment">
            <form class="new-comment__form" action="" method="post" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="new-comment__form-title">
                    <label class="new-comment__form--label">商品へのコメント</label>
                </div>
                <div class="new-comment__form-content">
                    <textarea name="comment_content">{{ old('comment_content') }}</textarea>
                </div>
                <div class="new-comment__form-button">
                    <button class="form-button__submit">コメントを送信する</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection