@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/setting.css') }}">
@endsection


@section('content')
<div class="setting__content">
    <div class="setting__ttl">
        <h2>プロフィール画像</h2>
    </div>
    <div class="setting__form">
        <form action="/mypage/profile" class="form" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form__item">
                <input type="file" name="image" id="image">
            </div>
            <div class="form__item">
                <div class="form__item-name">
                    <p class="form__label">
                        ユーザー名
                    </p>
                </div>
                <div class="form__item-input">
                    <input type="text" name="name" value="{{ old('name',$user->name) }}" class="form__input">
                </div>
            </div>
            <div class="form__item">
                <div class="form__item-name">
                    <p class="form__label">
                        郵便番号
                    </p>
                </div>
                <div class="form__item-input">
                    <input type="text" name="post_code" value="{{ old('post__code') }}" class="form__input">
                </div>
            </div>
            <div class="form__item">
                <div class="form__item-name">
                    <p class="form__label">
                        住所
                    </p>
                </div>
                <div class="form__item-input">
                    <input type="text" name="address" value="{{ old('address') }}" class="form__input">
                </div>
            </div>
            <div class="form__item">
                <div class="form__item-name">
                    <p class="form__label">
                        建物名
                    </p>
                </div>
                <div class="form__item-input">
                    <input type="text" name="building" value="{{ old('building') }}" class="form__input">
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">更新する</button>
            </div>
        </form>
    </div>
</div>
@endsection