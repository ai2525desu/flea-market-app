@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profiles/show.css') }}">
@endsection

@section('content')
<div class="profile-show-content__wrap">
    <div class="profile-show-content__user-information">
        <div class="user-information__profile-image">
            <img src="{{  asset('storage/' . $user->profile?->image) }}" class="profile-image">
        </div>
        <p class="user-information__user-name">
            {{ $user->name }}
        </p>
        <div class="user-information__screen-transition">
            <a class="screen-transition__edit" href="{{ route('profiles.edit') }}">プロフィールを編集
            </a>
        </div>
    </div>
    <div class="profile-show-content__product-information">
        <div class="product-tab__header" id="product-tab__header">
            <ul class="product-tab__heading">
                <li class="product-tab__item is-active">出品した商品</li>
                <li class="product-tab__item">購入した商品</li>
            </ul>
        </div>
        <!-- Blade機能で`foreachを使用する+商品画像と商品名のカードリスト -->
        <div class="product-tab__body" id="product-tab__body">
            <div class="product-tab__content is-active">
                <div class="exhibition-product-card__list">
                    @foreach ($items as $item)
                    <div class="exhibition-product-card__wrap">
                        <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->item_name }}">
                        <p class="exhibition-card__title">{{ $item->item_name }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="product-tab__content">
                購入した商品の内容
                <div class="purchases-product-card__wrap">

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabItems = document.querySelectorAll('.product-tab__item');
        const tabContents = document.querySelectorAll('.product-tab__content');

        tabItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                tabItems.forEach(i => i.classList.remove('is-active'));
                tabContents.forEach(c => c.classList.remove('is-active'));
                item.classList.add('is-active');
                tabContents[index].classList.add('is-active');
            });
        });
    });
</script>
@endsection