@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/exhibition.css') }}">
@endsection

@section('content')
<div class="exhibition-content__message">
    @if (session('message'))
    <div class="exhibition-content__success-message">
        {{ session('message') }}
    </div>
    @endif
</div>
<div class="exhibition-content">
    <form class="exhibition-form" method="post" action="{{ route('items.exhibition') }}" enctype="multipart/form-data" novalidate>
        @csrf
        <h1 class="exhibition-form__heading">
            商品の出品
        </h1>
        <div class="exhibition-form__content">
            <span class="exhibition-form__content-title">
                商品画像
            </span>
            <span class="exhibition-form__error">
                @error('item_image')
                {{ $message }}
                @enderror
            </span>
            <div class="exhibition-form__item--image">
                <div class="image__wrap">
                    <img id="preview-image" src="#" class="preview-image">
                    <input class="image__input" id="item_image" type="file" name="item_image" accept="image/*">
                </div>
                <div class="image__button">
                    <label for="item_image" class="product-image__button">画像を選択する</label>
                </div>
            </div>
        </div>
        <h2 class="exhibition-form__title">
            商品の詳細
        </h2>
        <div class="exhibition-form__content">
            <span class="exhibition-form__content-title" for="category">
                カテゴリー
            </span>
            <span class="exhibition-form__error">
                @error('categories')
                {{ $message }}
                @enderror
            </span>
            <div class="exhibition-form__item--category">
                @foreach ($categories as $category)
                <label class="category-label">
                    <input
                        class="category-checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : ''}}>
                    <span class="category-name">{{ $category->category_name }}</span>
                </label>
                @endforeach
            </div>
        </div>
        <div class="exhibition-form__content">
            <label class="exhibition-form__content-title" for="condition">
                商品の状態
            </label>
            <span class="exhibition-form__error">
                @error('condition')
                {{ $message }}
                @enderror
            </span>
            <div class="exhibition-form__item">
                <select name="condition" id="condition">
                    <option value="" disabled selected>
                        選択してください
                    </option>
                    @foreach ($conditions as $key=>$condition)
                    <option value="{{ $key }}" {{ old('condition') == $key ? 'selected' : '' }}>
                        {{ $condition }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <h2 class="exhibition-form__title">
            商品名と説明
        </h2>
        <div class="exhibition-form__content">
            <label class="exhibition-form__content-title" for="item_name">
                商品名
            </label>
            <span class="exhibition-form__error">
                @error('item_name')
                {{ $message }}
                @enderror
            </span>
            <div class="exhibition-form__item">
                <input type="text" name="item_name" id="item_name" value="{{ old('item_name') }}">
            </div>
        </div>
        <div class="exhibition-form__content">
            <label class="exhibition-form__content-title" for="brand">
                ブランド名
            </label>
            <div class="exhibition-form__item">
                <input type="text" name="brand" id="brand" value="{{ old('brand') }}">
            </div>
        </div>
        <div class="exhibition-form__content">
            <label class="exhibition-form__content-title" for="description">
                商品の説明
            </label>
            <span class="exhibition-form__error">
                @error('description')
                {{ $message }}
                @enderror
            </span>
            <div class="exhibition-form__item">
                <textarea name="description" id="description">
                {{ old('description') }}
                </textarea>
            </div>
        </div>
        <div class="exhibition-form__content">
            <label class="exhibition-form__content-title" for="price">
                販売価格
            </label>
            <span class="exhibition-form__error">
                @error('price')
                {{ $message }}
                @enderror
            </span>
            <div class="exhibition-form__item--price">
                <input type="text" name="price" id="price" value="{{ old('price') }}">
            </div>
        </div>
        <div class="exhibition-form__button">
            <button class="exhibition-form__button--submit" type="submit">
                出品する
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('item_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview-image');
            preview.src = e.target.result;
            preview.style.display = 'block';

            preview.onload = function() {
                const wrap = document.querySelector('.exhibition-form__item--image');
                const maxHeight = 400;
                wrap.style.height = Math.min(preview.naturalHeight, maxHeight) + 'px';
            };
        }
        reader.readAsDataURL(file);
    });
</script>
@endsection