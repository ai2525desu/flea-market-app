@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/address.css') }}">
@endsection

@section('content')
<div class="address-form__content">
    <div class="address-form__wrap">
        <h2 class="address-form__heading">
            住所の変更
        </h2>
        <form class="address-form__form" action="{{ route('purchases.address', ['item_id' => $item->id]) }}" method="post" novalidate>
            @method('patch')
            @csrf
            <div class="address-form__group">
                <div class="address-form__group--title">
                    <label for="shipping_post_code" class="address-form__group--label">
                        郵便番号
                    </label>
                </div>
                <div class="address-form__group--content">
                    <input id="shipping_post_code" type="text" name="shipping_post_code" value="{{ old('shipping_post_code') }}">
                </div>
                <div class="address-form__group--error">
                    @error('shipping_post_code')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="address-form__group">
                <div class="address-form__group--title">
                    <label for="" class="address-form__group--label">
                        住所
                    </label>
                </div>
                <div class="address-form__group--content">
                    <input id="shipping_address" type="text" name="shipping_address" value="{{ old('shipping_address') }}">
                </div>
                <div class="address-form__group--error">
                    @error('shipping_address')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="address-form__group">
                <div class="address-form__group--title">
                    <label for="shipping_building" class="address-form__group--label">
                        建物名
                    </label>
                </div>
                <div class="address-form__group--content">
                    <input id="shipping_building" type="text" name="shipping_building" value="{{ old('shipping_building') }}">
                </div>
                <div class="address-form__group--error">
                    @error('shipping_building')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="address-form__button">
                <button class="address-form__button--submit" type="submit">
                    登録する
                </button>
            </div>
        </form>
    </div>
</div>
@endsection