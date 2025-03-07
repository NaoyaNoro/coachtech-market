@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/change__address.css') }}">
@endsection

@section('content')
<div class="address__content">
    <div class="address__ttl">
        <h2>住所の変更</h2>
    </div>
    <div class="address__form">
        <form action="/change/address" method="post">
            @csrf
            <div class="form__group">
                <p class="form__name">
                    郵便番号
                </p>
                <input type="text" class="form__input" value="{{$profile->post_code}}" name="post_code">
            </div>
            <div class="error">
                @error('post_code')
                {{ $message }}
                @enderror
            </div>
            <div class="form__group">
                <p class="form__name">
                    住所
                </p>
                <input type="text" class="form__input" value="{{$profile->address}}" name="address">
            </div>
            <div class="error">
                @error('address')
                {{ $message }}
                @enderror
            </div>
            <div class="form__group">
                <p class="form__name">
                    建物名
                </p>
                <input type="text" class="form__input" value="{{$profile->building}}" name="building">
            </div>
            <div class="error">
                @error('building')
                {{ $message }}
                @enderror
            </div>
            <input type="hidden" class="form__input" value="{{$product_id}}" name="product_id">
            <button type="submit" class="form__button-submit">
                更新する
            </button>
        </form>
    </div>
</div>
@endsection