@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/setting.css') }}">
@endsection


@section('content')
<div class="profile__content">
    <div class="profile__ttl">
        <h2>プロフィール設定</h2>
    </div>
    <div class="profile__form">
        <form action="/mypage/profile" class="form" method="post" enctype="multipart/form-data">
            @csrf
            <div class="profile-image-container">
                <label for="image" class="profile-image-label">
                    <img src="{{ asset('storage/img/profile/' . optional($user->profile)->image ?? 'default.png') }}" class="profile-image">
                    <span class="profile-image-text">画像を選択する</span>
                </label>
                <input type="file" name="image" id="image" class="profile-image-input" accept="image/*">
            </div>
            @error('image')
            <p class="error">{{$message}}</p>
            @enderror
            <div class="form__item">
                <div class="form__item-name">
                    <p class="form__label">
                        ユーザー名
                    </p>
                </div>
                <div class="form__item-input">
                    <input type="text" name="name" value="{{ old('name',$user->name) }}" class="form__input">
                </div>
                <div class="error">
                    @error('name')
                    <span class="error">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="form__item">
                <div class="form__item-name">
                    <p class="form__label">
                        郵便番号
                    </p>
                </div>
                <div class="form__item-input">
                    <input type="text" name="post_code" value="{{ old('post_code',$user->profile->post_code ??'') }}" class="form__input">
                </div>
                <div class="error">
                    @error('post_code')
                    <span class="error">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="form__item">
                <div class="form__item-name">
                    <p class="form__label">
                        住所
                    </p>
                </div>
                <div class="form__item-input">
                    <input type="text" name="address" value="{{ old('address',$user->profile->address ??'') }}" class="form__input">
                </div>
                <div class="error">
                    @error('address')
                    <span class="error">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="form__item">
                <div class="form__item-name">
                    <p class="form__label">
                        建物名
                    </p>
                </div>
                <div class="form__item-input">
                    <input type="text" name="building" value="{{ old('building',$user->profile->building??'')}}" class="form__input">
                </div>
                <div class="error">
                    @error('building')
                    <span class="error">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">更新する</button>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('js/profile_setting.js') }}"></script>
@endsection