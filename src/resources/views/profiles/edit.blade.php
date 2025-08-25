@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profiles/edit.css') }}">
@endsection

@section('content')
<div class="profile-edit-form__content">
    <div class="profile-edit-form__form-wrap">
        <h2 class="profile-edit-form__heading">
            プロフィール設定
        </h2>
        <form class="profile-edit-form__form" action="{{ route('profiles.edit') }}" method="post" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="profile-edit-form__group">
                <div class="profile-edit-form__group--content">
                    <div class="profile-image__wrap">
                        <div class="profile-image__content">
                            @if ($user->profile?->image)
                            <img id="preview-image" src="{{  asset('storage/' . $user->profile?->image) }}">
                            <input type="hidden" name="old_image" value="{{ $user->profile?->image }}">
                            @endif
                            <!-- <img id="profile-image" src="#" class="profile-image"> -->
                            <img src="#" class="profile-image">
                            <input id="image" type="file" name="image" class="profile-image__input" accept="image/*">
                        </div>
                        <div class="profile-image__label">
                            <label for="image" class="profile-image__button">画像を選択する</label>
                            <span id="selected-filename" class="filename-display"></span>
                        </div>
                    </div>
                </div>
                <div class="profile-edit-form__group--error">
                    @error('image')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="profile-edit-form__group">
                <div class="profile-edit-form__group--title">
                    <label for="user_name" class="profile-edit-form__group--label">
                        ユーザー名
                    </label>
                </div>
                <div class="profile-edit-form__group--content">
                    <input id="user_name" type="text" name="name" value="{{ $user->name }}">
                </div>
                <div class="profile-edit-form__group--error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <!-- 郵便番号 -->
            <div class="profile-edit-form__group">
                <div class="profile-edit-form__group--title">
                    <label for="post_code" class="profile-edit-form__group--label">
                        郵便番号
                    </label>
                </div>
                <div class="profile-edit-form__group--content">
                    <input id="post_code" type="text" name="post_code" value="{{ old('post_code', $user->address?->post_code) }}">
                </div>
                <div class="profile-edit-form__group--error">
                    @error('post_code')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <!-- 住所 -->
            <div class="profile-edit-form__group">
                <div class="profile-edit-form__group--title">
                    <label for="address" class="profile-edit-form__group--label">
                        住所
                    </label>
                </div>
                <div class="profile-edit-form__group--content">
                    <input id="address" type="text" name="address" value="{{ old('address', $user->address?->address) }}">
                </div>
                <div class="profile-edit-form__group--error">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="profile-edit-form__group">
                <div class="profile-edit-form__group--title">
                    <label for="building" class="profile-edit-form__group--label">
                        建物名
                    </label>
                </div>
                <div class="profile-edit-form__group--content">
                    <input id="building" type="text" name="building" value="{{ old('building', $user->address?->building) }}">
                </div>
            </div>
            <div class="profile-edit-form__button">
                <button type="submit" class="profile-edit-form__button--submit">
                    更新する
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview-image');
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        }
        reader.readAsDataURL(file);

        document.getElementById('selected-filename').textContent = file.name;
    });
</script>
@endsection