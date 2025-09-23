@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/exhibition.css') }}">
@endsection

@section('content')
<div class="exhibition-content">
    <!-- method、action後で -->
    <form class="exhibition-form" method="" action="" enctype="multipart/form-data" novalidate>
        @csrf
        <h1 class="exhibition-form__heading">
            商品の出品
        </h1>
        <div class="exhibition-form__content">
            <label class="exhibition-form__item-title" for="item-image">
                商品画像
            </label>
            <div class="exhibition-form__item">
                <!-- JSを使用して、画像が選択されるとdispay切り替わる＝＞参考はprofiles.edit -->
                {{-- <!-- <img id="preview-image" src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->item_name }}"> -->--}}
                <input id="item-image" type="file" name="item_image" class="product-image__input" accept="image/*">
                <!-- divタグを点線で囲って、その上下中央にボタン配置 -->
                <label for="image" class="product-image__button">画像を選択する</label>
                <span id="selected-filename" class="filename-display"></span>
            </div>
        </div>
        <div class="exhibition-form__content">
            <h2 class="exhibition-form__title">
                商品の詳細
            </h2>
            <div class="exhibition-form__item">
                <label class="exhibition-form__item-title" for="category">
                    カテゴリー
                </label>
                <div class="exhibition-form__input--category">
                    {{--<!-- @foreach ($item->categories as $category)
                    <span class="category__item">{{ $category->category_name }}</span>
                    @endforeach -->--}}
                    @foreach ($categories as $category)
                    <input class="category-checkbox" type="checkbox" name="categories[]" id="category" value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : ''}}>
                    <span class=" category-name">{{ $category->category_name }}</span>
                    @endforeach
                </div>
            </div>
            <div class=" exhibition-form__item">
                <label class="exhibition-form__item-title" for="condition">
                    商品の状態
                </label>
                <div class="exhibition-form__item--condition">
                    <select name="condition" id="condition">
                        <option value="" disabled selected>
                            選択してください
                        </option>
                        @foreach ($conditions as $key=>$condition)
                        <option value="{{ $key }}">
                            {{ $condition }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection