@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/exhibition.css') }}">
@endsection

@section('content')
<div class="exhibition-content">
    <form class="exhibition-form">
        <h2 class="exhibition-form__heading">
            商品の出品
        </h2>
        <div class="exhibition-form__product-image">
            <!-- JSを使用して、画像が選択されるとdispay切り替わる＝＞参考はprofiles.edit -->
            <div class="product-image__image">
                {{-- <!-- <img id="preview-image" src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->item_name }}"> -->--}}
                <input id="image" type="file" name="item_image" class="product-image__input" accept="image/*">
            </div>
            <!-- divタグを点線で囲って、その上下中央にボタン配置 -->
            <div class="product-image__label">
                <label for="image" class="product-image__button">画像を選択する</label>
                <span id="selected-filename" class="filename-display"></span>
            </div>
        </div>
    </form>
</div>
@endsection