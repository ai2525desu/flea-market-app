@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/show.css') }}">
@endsection

@section('content')
<div class="purchase-content__wrap">
    <div class="purchase-content__message">
        @if ($isPurchased)
        <div class="purchase-content__success-message">
            購入が成功しました
        </div>
        @endif
    </div>
    <form class="purchase-content__form" method="post" action="{{ route('purchases.stripe', ['item_id' => $item->id]) }}" novalidate>
        @csrf
        <input type="hidden" name="item_id" value="{{ $item->id }}">
        <input type="hidden" name="shipping_post_code" value="shipping_post_code">
        <input type="hidden" name="shipping_address" value="shipping_address">
        <input type="hidden" name="shipping_building" value="shipping_building">
        <div class="purchase-content__form-left">
            <div class="box-decoration">
                <div class="purchase-content__information--product">
                    <div class="product__image">
                        <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->item_name }}">
                    </div>
                    <div class="product__heading">
                        <h2 class="product__heading--name">
                            {{ $item->item_name }}
                        </h2>
                        @if ($item->purchase)
                        <span class="product__heading--sold-display">Sold</span>
                        @endif
                        <p class="product__heading--price">
                            ¥&nbsp;{{ number_format($item->price) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="box-decoration">
                <div class="purchase-content__information--payment_method">
                    <div class="payment_method__headinner">
                        <h2 class="payment_method__heading">
                            <label for="payment_method">支払い方法</label>
                        </h2>
                        <div class="purchase-content__error">
                            @error('payment_method')
                            {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="payment-method__select">
                        <select name="payment_method" id="payment_method">
                            <option value="" disabled selected>選択してください</option>
                            <option value="convenience_store">コンビニ支払い</option>
                            <option value="card">カード支払い</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="box-decoration">
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
                    <div class="purchase-content__error">
                        @if($errors->has('shipping_post_code') || $errors->has('shipping_address'))
                        配送先を選択してください
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="purchase-content__form-rigth">
            <div class="purchase-content__subtotal">
                <div class="subtotal__item">
                    <div class="subtotal__item--left">
                        <p class="subtotal__title">
                            商品代金
                        </p>
                    </div>
                    <div class="subtotal__item--right">
                        <p class="subtotal__content">
                            ¥&nbsp;{{ number_format($item->price) }}
                        </p>
                    </div>
                </div>
                <div class="subtotal__item">
                    <div class="subtotal__item--left">
                        <p class="subtotal__title">
                            支払い方法
                        </p>
                    </div>
                    <div class="subtotal__item--right">
                        <p class="subtotal__content" id="selected-method">
                            選択してください
                        </p>
                    </div>
                </div>
            </div>
            <div class="purchase-content__form-button">
                <button type="submit" class="purchase-content__form-button--submit {{  $isPurchased ? 'disabled' : ''}}">
                    購入する
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('payment_method').addEventListener('change', function() {
        const selectedText = this.options[this.selectedIndex].text;
        document.getElementById('selected-method').textContent = selectedText;
    });
</script>
@endsection