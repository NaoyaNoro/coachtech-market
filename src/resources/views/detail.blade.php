@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="detail__content">
    <div class="detail__inner">
        <div class="detail__img">
            <img src="{{ asset('storage/img/product/' . $product->image) }}" alt="" class="img__file">
        </div>
        <div class="detail__text">
            <div class="detail__item">
                <h2 class="detail__name">
                    {{ $product->name }}
                </h2>
            </div>
            <div class="detail__item">
                <p class="detail__brand">
                    {{ $product->brand }}
                </p>
            </div>
            <div class="detail__item">
                <p class="detail__price">
                    ¥{{ $product->price }}(税込)
                </p>
            </div>

            <div class="detail__item">
                <p class="detail__good">
                    お気に入り:{{ count($mylists) }}
                </p>
                <form action="/good_button" method="post">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <label for="favoriteCheckbox" class="favorite-toggle">
                        <input
                            type="checkbox"
                            id="favoriteCheckbox"
                            name="favorited"
                            {{ $isFavorited ? 'checked' : '' }}
                            onchange="this.form.submit()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                    </label>
                </form>
            </div>


            <div class="detail__item">
                <p class="detail__comment">
                    コメント:{{count($comments)}}
                </p>

                <label for="CommentCheckbox" class="comment-toggle">
                    <input type="checkbox" id="CommentCheckbox" {{ $isCommented ? 'checked' : '' }}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 5H4a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h4l4 4v-4h8a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2z"></path>
                    </svg>
                </label>
            </div>
            <div class="detail-form">
                <form action="/purchase/{{ $product->id }}" class="form__purchase" method="get">
                    @csrf
                    <button class="form__button" type="submit">購入手続きへ</button>
                </form>
            </div>
            <div class="detail__info">
                <h3 class="info__ttl">
                    商品の説明
                </h3>
            </div>
            <div class="detail__item">
                <p class="detail__description">
                    {{ $product->description }}
                </p>
            </div>
            <div class="detail__info">
                <h3 class="info__ttl">
                    商品の情報
                </h3>
            </div>
            <div class="detail__item">
                <p class="detail__category">
                    カテゴリー
                    @foreach ($product->categories as $category)
                    {{ $category->name }}
                    @endforeach
                </p>
            </div>
            <div class="detail__item">
                <p class="detail__status">
                    商品の状態 {{$product->status->status}}
                </p>
            </div>
            <div class="detail__item">
                <h3 class="info__ttl">
                    コメント(
                    {{count($comments)}}
                    )
                </h3>
                @foreach ($comments as $comment)
                <div class="comment__profile">
                    <img src="{{ asset('storage/img/profile/' . optional($comment->profile)->image) }}" class="comment__profile-image">
                    <span class="comment__profile-name">
                        {{ $comment->users-> name}}
                    </span>
                </div>
                <div class="comment__content">
                    <p class="comment__item">
                        {{ $comment->comment }}
                    </p>
                </div>
                @endforeach
                </p>
            </div>
            <div class="detail__item">
                <div class="comment__make">
                    <h3 class="comment__ttl">
                        商品へのコメント
                    </h3>
                </div>
                <div class="comment__make-input">
                    <form action="/comment" class="comment-form" method="post">
                        @csrf
                        <textarea name="comment" class="comment-form__input" id="comment"></textarea>
                        <input type="hidden" name="product_id" value="{{ $product->id }}" id="product_id">
                        <button class="comment-form__button" type="submit" id="submit-comment">
                            コメントを送信する
                        </button>

                        @error('comment')
                        <span class="error">{{$message}}</span>
                        @enderror
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="{{ asset('js/detail.js') }}"></script>
@endsection