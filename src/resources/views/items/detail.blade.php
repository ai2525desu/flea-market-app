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
                    <button class="likes-form__button  @if($item->likedByCurrentUser()) liked @endif" type="submit" id="likes-button">
                        <img class="likes-form__icon" src="{{ asset('storage/star.png') }}" art="いいねボタン">
                    </button>
                    <span class="product-introduction__count-number">{{ $item->likes->count() }}</span>
                </form>
            </div>
            <div class="product-introduction__count--comments">
                <div class="comments__quantity-display" type="submit">
                    <img class="comments__icon" src="{{ asset('storage/speech-bubble.png') }}" art="コメント">
                </div>
                <span class="product-introduction__count-number">{{ $item->comments->count() }}</span>
                </form>
            </div>
        </div>
    </div>
    <div class="detail-content__screen-transition">
        <a class="screen-transition__purchase {{ $hasPurchase ? 'disabled' : ''}}" @if (!$hasPurchase) href="{{ route('purchases.show', ['item_id' => $item->id]) }}" @endif>
            購入手続きへ
        </a>
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
        <h2 class="comment__heading">コメント({{ $item->comments->count() }})</h2>
        <div class="comment__existing-comment">
            @foreach ($item->comments as $comment)
            <div class="existing-comment__user-wrap">
                <div class="existing-comment__user-wrap--image">
                    <img src="{{  asset('storage/' . $comment->user->profile->image) }}" class="profile-image">
                </div>
                <p class="existing-comment__user-wrap--name">
                    {{ $comment->user->name }}
                </p>
            </div>
            <div class="existing-comment__content">
                <p class="existing-comment__content--item">
                    {{ $comment->comment_content }}
                </p>
            </div>
            @endforeach
        </div>
        <div class="comment__new-comment">
            <form class="new-comment__form" method="post" action="{{ route('items.comment', ['item_id' => $item->id]) }}" novalidate>
                @csrf
                <div class="new-comment__form-title">
                    <label class="new-comment__form-label">商品へのコメント</label>
                </div>
                <div class="new-comment__form-content">
                    <textarea name="comment_content">{{ old('comment_content') }}</textarea>
                </div>
                <div class="new-comment__form-error">
                    @error('comment_content')
                    {{ $message }}
                    @enderror
                </div>
                <div class="new-comment__form-button">
                    <button class="form-button__submit" type="submit">コメントを送信する</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('likes-button').addEventListener('click', function() {
        this.classList.toggle('liked');
    });
</script>
@endsection