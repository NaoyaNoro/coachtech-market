@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase__content">
    <div class="purchase__inner">
        <div class="purchase__left">
            <div class="purchase__group--product">
                <div class="purchase__img">
                    <img src="{{ asset('storage/img/product/' . $product->image) }}" alt="" class="img__file">
                </div>
                <div class="purchase__detail">
                    <div class="purchase__name">
                        <h2>{{$product->name}}</h2>
                    </div>
                    <div class="purchase__price">
                        <p>¥{{$product->price}}</p>
                    </div>
                </div>
            </div>
            <div class="purchase__group">
                <div class="purchase__ttl">
                    <h3>支払い方法</h3>
                </div>
                <div class="purchase__method">
                    <select class="purchase__method--select" onchange="this.form.submit()" name="purchase__method" id="purchaseMethodSelect">
                        <option value="" hidden selected disabled>選択してください</option>
                        <option value="コンビニ払い">コンビニ払い</option>
                        <option value="カード支払い">カード支払い</option>
                    </select>
                </div>
                @error('purchase__method')
                <span class="error">{{$message}}</span>
                @enderror
            </div>
            <div class="purchase__group">
                <div class="address__change">
                    <div class="purchase__ttl">
                        <h3>配送先</h3>
                    </div>
                    <div class="address__form">
                        <form action="/purchase/address/{{ $address->user_id}}" method="post">
                            @csrf
                            <input type="hidden" value="{{$product->id}}" name="product_id">
                            <button class="form__button">
                                変更する
                            </button>
                        </form>
                    </div>
                </div>
                <div class="address__content">
                    <div class="purchase__post-code">
                        〒{{ $address->post_code }}
                    </div>
                    <div class=" purchase__address">
                        {{ $address->address.$address->building}}
                    </div>
                </div>
            </div>
        </div>
        <div class="purchase__right">
            <div class="purchase__group">
                <form action="/checkout" method="post">
                    @csrf
                    <input type="hidden" name="product_name" value="{{ $product->name }}">
                    <input type="hidden" name="product_price" value="{{ $product->price }}">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <table class="confirm__table">
                        <tr>
                            <th>商品代金</th>
                            <td>¥{{$product->price}}</td>
                        </tr>
                        <tr>
                            <th>支払い方法</th>
                            <td id="selectedPaymentMethod">
                                <input id="paymentMethodInput" name="purchase__method" class="purchase__method--confirm" readonly></input>
                            </td>
                        </tr>
                    </table>
                    <button type="submit" class="purchase__button--submit">
                        購入する
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/purchase.js') }}"></script>
@endsection