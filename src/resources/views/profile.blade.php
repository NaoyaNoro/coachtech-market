@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection



@section('content')
<div class="profile__content">
    <div class="profile__wrapper">
        <div class="profile">
            <div class="profile__image-wrapper">
                <img src="{{ asset('storage/img/profile/' . optional($user->profile)->image) }}" class="profile__image">
            </div>
            <div class="profile__name">
                <p>
                    {{ $user->name }}
                </p>
            </div>
            <div class="profile__setting">
                <a href="/mypage/profile" class="profile__setting-link">
                    プロフィールを編集
                </a>
            </div>
        </div>

        <div class="tab__navigation">
            <a href="{{url('/mypage?tab=sell')}}" class="tab__button {{$activeTab==='sell'?'active':''}}">出品した商品</a>
            <a href="{{url('/mypage?tab=buy')}}" class="tab__button {{$activeTab==='buy'?'active':''}}">購入した商品</a>
        </div>
    </div>

    <!-- 出品した商品 -->
    <div class="product__list tab__content {{$activeTab==='sell'?'active':''}}" id="sell">
        <div class="sell__inner">
            <div class="product__grid">
                @foreach($mySellProducts as $product)
                <div class="product__item">
                    <img src="{{ asset('storage/img/product/' . $product->image) }}" alt="{{ $product->name }}" class="img__file">
                    <p class="product__name">{{ $product->name }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 購入した商品-->
    <div class="product__list tab__content {{$activeTab==='buy'?'active':''}}" id="purchase">
        <div class="purchase__inner">
            <div class="product__grid">
                @foreach($myPurchaseProducts as $product)
                <div class="product__item">
                    <img src="{{ asset('storage/img/product/' . $product->image) }}" alt="{{ $product->name }}" class="img__file">
                    <p class="product__name">{{ $product->name }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection