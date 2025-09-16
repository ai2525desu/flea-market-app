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
                    <label for="post_code" class="address-form__group--label">
                        郵便番号
                    </label>
                </div>
                <div class="address-form__group--content">
                    <input id="post_code" type="text" name="post_code" value="{{ old('post_code') }}">
                </div>
                <div class="address-form__group--error">
                    @error('post_code')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="address-form__group">
                <div class="address-form__group--title">
                    <label for="address" class="address-form__group--label">
                        住所
                    </label>
                </div>
                <div class="address-form__group--content">
                    <input id="address" type="text" name="address" value="{{ old('address') }}">
                </div>
                <div class="address-form__group--error">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="address-form__group">
                <div class="address-form__group--title">
                    <label for="building" class="address-form__group--label">
                        建物名
                    </label>
                </div>
                <div class="address-form__group--content">
                    <input id="building" type="text" name="building" value="{{ old('building') }}">
                </div>
                <div class="address-form__group--error">
                    @error('building')
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