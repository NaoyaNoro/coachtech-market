@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="detail__content">
    <div class="detail__inner">
        <div class="detail__img {{in_array($product->id,$soldOutProductIDs)?'sold-out':''}}">
            <img src="{{ asset('storage/img/product/' . $product->image) }}" alt="" class="img__file">
            @if(in_array($product->id, $soldOutProductIDs))
            <div class="sold-out-label">Sold</div>
            @endif
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
                    ¥{{ $product->price }}
                    <span class="tax">
                        (税込)
                    </span>
                </p>
            </div>

            <div class="detail__mark">
                <div class="detail__item">
                    <form action="/good_button" method="post">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <label for="favoriteCheckbox" class="favorite__toggle">
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
                    <label for="CommentCheckbox" class="comment__toggle">
                        <input type="checkbox" id="CommentCheckbox" {{ $isCommented ? 'checked' : '' }}>
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 5H4a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h4l4 4v-4h8a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2z"></path>
                        </svg>
                    </label>
                </div>
            </div>
            <div class="detail__count">
                <p class="mylist__count">
                    {{ count($mylists) }}
                </p>
                <p class="comment__count">
                    {{count($comments)}}
                </p>
            </div>

            <div class="detail-form">
                @if(in_array($product->id, $soldOutProductIDs))
                <a href="javascript:void(0);" class="detail-form__link {{in_array($product->id,$soldOutProductIDs)?'sold-out':''}}">
                    在庫切れ
                </a>
                @else
                <a href="/purchase/{{ $product->id }}" class="detail-form__link">
                    購入手続きへ
                </a>
                @if($isCompletedTransaction)
                <a href="javascript:void(0);" class="transaction-form__link {{$isCompletedTransaction ?'sold-out':''}}">
                    取引済みです
                </a>
                @elseif($isTransaction)
                <a href="javascript:void(0);" class="transaction-form__link {{$isTransaction ?'sold-out':''}}">
                    取引中です
                </a>
                @else
                <form action="/make/transaction" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $product->id }}" name="product_id">
                    <input type="hidden" value="{{ $seller_id }}" name="seller_id">
                    <button class="transaction-form__link">取引へ</button>
                </form>
                @endif
                @endif
            </div>
            <div class="detail__info">
                <h3 class="info__ttl">
                    商品の説明
                </h3>
            </div>
            <div class="detail__item">
                <p class="detail__color">
                    カラー : {{ $product->color ?? '未定義' }}
                </p>
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
            <div class="detail__item--category">
                <p class="detail__category">
                    カテゴリー
                </p>
                <div class="category__contents">
                    @foreach ($product->categories as $category)
                    <span class="category__content">
                        {{ $category->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            <div class="detail__item--status">
                <p class="detail__status">
                    商品の状態
                </p>
                <span>
                    {{$product->status}}
                </span>
            </div>
            <div class="detail__item">
                <h3 class="info__ttl">
                    コメント(
                    {{count($comments)}}
                    )
                </h3>
                @foreach ($comments as $comment)
                <div class="comment__profile">
                    <img src="{{ asset('storage/img/profile/' . optional($comment->profile)->image) }}" class="comment__profile--image">
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
                        <div class="error">
                            @error('comment')
                            <span class="error">{{$message}}</span>
                            @enderror
                        </div>
                        <button class="comment-form__button" type="submit" id="submit-comment">
                            コメントを送信する
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="{{ asset('js/detail.js') }}"></script>
@endsection