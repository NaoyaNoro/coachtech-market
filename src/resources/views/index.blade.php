@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection



@section('content')
<div class="product__nav">
    <nav class="nav__content">
        <div class="nav__item">
            <!-- おすすめリンクにactiveクラスを追加 -->
            <a href="/" class="{{ !$page || $page !== 'mylist' ? 'active' : '' }}">おすすめ</a>
        </div>
        <div class="nav__item">
            <!-- マイリストリンクにactiveクラスを追加 -->
            <a href="/?page=mylist" class="{{ $page === 'mylist' ? 'active' : '' }}">マイリスト</a>
        </div>
    </nav>
</div>
<div class="product__content">
    <div class="product__inner">
        @if($products->isEmpty())

        @else

        @foreach($products as $product)
        <a href="/item/{{ $product->id }}" class="product-detail__link {{ in_array($product->id, $soldOutProductIDs) ? 'sold-out' : '' }}">
            <div class="product__group">
                <div class="product__img">
                    <img src="{{ asset('storage/img/product/' . $product->image) }}" alt="{{ $product->name }}" class="img__file">
                    @if(in_array($product->id, $soldOutProductIDs))
                    <div class="sold-out-label">Sold</div>
                    @endif
                </div>
                <div class="product__name">
                    <p>{{ $product->name }}</p>
                </div>
            </div>
        </a>
        @endforeach
        @endif
    </div>
</div>
@endsection