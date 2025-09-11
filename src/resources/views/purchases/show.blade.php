@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/show.css') }}">
@endsection

@section('content')
<div class="purchase-content__wrap">
    <!-- method,actionは後で記述 -->
    <form class="purchase-content__form" method="" action="" novalidate>
        @csrf
        <div class="purchase-content__form-left">
            <div class="purchase-content__information--product">
                <div class="product__image">
                    <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->item_name }}">
                </div>
                <div class="product__heading">
                    <h2 class="product__heading--name">
                        {{ $item->item_name }}
                    </h2>
                    <p class="product__heading--price">
                        ¥&nbsp;{{ number_format($item->price) }}
                    </p>
                </div>
                <div class="purchase-content__information--payment_method">
                    <label class="payment_method__heading" for="payment_method">支払い方法</label>
                    <select class="payment-method__select" name="payment_method" id="payment_method">
                        <option value="" disabled selected>選択してください</option>
                        <option value="convenience_store">コンビニ支払い</option>
                        <option value="card">カード支払い</option>
                    </select>
                </div>
                <div class="purchase-content__information--shipping-address">
                    <div class="shipping-address__header">
                        <h2 class="shipping-address__heading">
                            配送先
                        </h2>
                        <a class="shipping-address__screen-transition" href="{{ route('purchases.address', ['item_id' => $item->id]) }}">
                            変更する
                        </a>
                    </div>
                    <div class="shipping-address__content">
                        <p class="shipping-address__item">
                            〒&nbsp;{{ $user->address->post_code }}
                        </p>
                        <p class="shipping-address__item">
                            {{ $user->address->address }}{{ $user->address->building }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="purchase-content__form-rigth">
            <div class="purchase-content__confirmation">
                <div class="confirmation__item">
                    <p class="confirmation__title">
                        商品代金
                    </p>
                    <p class="confirmation__price">
                        ¥&nbsp;{{ number_format($item->price) }}
                    </p>
                </div>
                <div class="confirmation__item">
                    <p class="confirmation__title">
                        支払い方法
                    </p>
                    <p class="confirmation__price" id="payment_method-text">
                        選択してください
                    </p>
                </div>
            </div>
            <div class="purchase-content__form-button">
                <button type="submit" class="purchase-content__form-button--submit">
                    購入する
                </button>
            </div>
        </div>
    </form>
</div>
@endsection