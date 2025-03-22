@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell__content">
    <form action="/sell" method="post" enctype="multipart/form-data">
        @csrf
        <div class="sell__head">
            <h2>商品の出品</h2>
        </div>
        <div class="sell__group">
            <div class="item">
                <p class="item__name">
                    商品画像
                </p>
            </div>
            <div class="image__container">
                <label for="image" class="image__label">
                    <img class="sell__image">
                    <p class="image__text">画像を選択する</p>
                </label>
                <input type="file" name="image" id="image" class="image__input" accept="image/*">
            </div>
            <div class="error">
                @error('image')
                <span class="error">{{$message}}</span>
                @enderror
            </div>
        </div>
        <div class="sell__ttl">
            <h3>商品の詳細</h3>
        </div>
        <div class="sell__group">
            <div class="item">
                <p class="item__name">
                    カテゴリー
                </p>
            </div>

            <div class="sell__item">
                @foreach($categories as $category)
                <label class="category__label">
                    <input type="checkbox" name="category[]" value="{{$category->id}}"
                        {{ in_array($category->id, old('category', [])) ? 'checked' : '' }}
                        class="category__input">
                    <span class="category__tag">{{$category->name}}</span>
                </label>
                @endforeach
            </div>

            <div class="error">
                @error('category')
                <span class="error">{{$message}}</span>
                @enderror
            </div>
        </div>
        <div class="sell__group">
            <div class="item">
                <p class="item__name">
                    カラー
                </p>
            </div>
            <div class="sell__item">
                <input type="text" class="item__input" name="color" value="{{old('color')}}">
            </div>
        </div>
        <div class="sell__group">
            <div class="item">
                <p class="item__name">
                    商品の状態
                </p>
            </div>
            <div class="sell__item">
                <select class="item__input" name="status" value="{{old('status')}}">
                    <option value="" hidden selected disabled>選択してください</option>
                    <option value="良好" {{ old('status') =='良好'?'selected':''}}>良好</option>
                    <option value="目立った傷や汚れなし" {{ old('status') =='目立った傷や汚れなし'?'selected':''}}>目立った傷や汚れなし</option>
                    <option value="やや傷や汚れあり" {{ old('status') =='やや傷や汚れあり'?'selected':''}}>やや傷や汚れあり</option>
                    <option value="状態が悪い" {{ old('status') =='状態が悪い'?'selected':''}}>状態が悪い</option>

                </select>
            </div>
            <div class="error">
                @error('status')
                <span class="error">{{$message}}</span>
                @enderror
            </div>
        </div>
        <div class="sell__ttl">
            <h3>商品名と説明</h3>
        </div>
        <div class="sell__group">
            <div class="item">
                <p class="item__name">
                    商品名
                </p>
            </div>
            <div class="sell__item">
                <input type="text" name="name" class="item__input" value="{{old('name')}}">
            </div>
            <div class="error">
                @error('name')
                <span class="error">{{$message}}</span>
                @enderror
            </div>
        </div>
        <div class="sell__group">
            <div class="item">
                <p class="item__name">
                    ブランド
                </p>
            </div>
            <div class="sell__item">
                <input type="text" name="brand" class="item__input" value="{{old('brand')}}">
            </div>
            <div class="error">
                @error('brand')
                <span class="error">{{$message}}</span>
                @enderror
            </div>
        </div>
        <div class="sell__group">
            <div class="item">
                <p class="item__name">
                    商品の説明
                </p>
            </div>
            <div class="sell__item">
                <textarea name="description" class="item__description">{{old('description')}}</textarea>
            </div>
            <div class="error">
                @error('description')
                <span class="error">{{$message}}</span>
                @enderror
            </div>
        </div>
        <div class="sell__group">
            <div class="item">
                <p class="item__name">
                    販売価格
                </p>
            </div>
            <div class="sell__item--price">
                <input type="text" class="item__input--price" name="price" value="{{old('price')}}">
            </div>
            <div class="error">
                @error('price')
                <span class="error">{{$message}}</span>
                @enderror
            </div>
        </div>
        <div class="button">
            <button class="sell__button--submit">出品する</button>
        </div>
    </form>
</div>
<script src="{{ asset('js/sell.js') }}"></script>
@endsection