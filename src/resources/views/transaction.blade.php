@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/transaction.css') }}">
@endsection

@section('navigation')
{{-- ナビゲーションを表示しない --}}
@endsection

@section('content')
<div class="transaction__content">
    <aside class="transaction__sidbar">
        <h2>その他の取引</h2>
        @if($isSeller)
        @foreach($anotherTransactions as $anotherTransaction)
        <a href="/chat/{{$anotherTransaction->id}}" class="another__transaction-link">
            <p class="another__transaction">
                {{ $anotherTransaction->product->name }}
            </p>
        </a>
        @endforeach
        @endif
    </aside>
    <div class="transaction__inner">
        <div class="transaction__top">
            <div class="profile__image-wrapper">
                <img src="{{ asset('storage/img/profile/' . optional($anotherUser->profile)->image) }}" class="profile__image">
                <h1 class="transaction__ttl">
                    {{ $anotherUser->name}}さんとの取引画面
                </h1>
            </div>
            <div>
                @if(!$isSeller)
                @if($isCompleted)
                <a class="transaction__but completed">
                    取引を終了しました
                </a>
                @else
                <a href="#modal-{{$transaction->id}}" class="transaction__but">
                    取引を完了する
                </a>
                @endif
                @endif
            </div>
        </div>
        <div class="transaction_product">
            <div class="transaction__img">
                <img src="{{ asset('storage/img/product/' . $product->image) }}" alt="" class="img__file">
            </div>
            <div class="transaction__item">
                <h2 class="transaction__name">
                    {{ $product->name }}
                </h2>
                <p class="transaction__price">
                    ¥{{ $product->price }}
                    <span class="tax">
                        (税込)
                    </span>
                </p>
            </div>
        </div>
        @if($chats)
        <div class="transaction__chat">
            @foreach($chats as $chat)
            <div class="chat__item {{ $chat->isSender ? 'active':'' }}">
                <div class="chat__profile {{ $chat->isSender ? 'active':'' }}">
                    <div class="chat__image-wrapper">
                        <img src="{{ asset('storage/img/profile/' . optional($chat->user->profile)->image) }}" class="chat__profile-image">
                    </div>
                    <div class="chat__name">
                        <p>
                            {{ $chat->user->name }}
                        </p>
                    </div>
                </div>
                <div class="chat__content {{ $chat->isSender ? 'active':'' }}">
                    @if($chat->image)
                    <img src="{{ asset('storage/img/chat/' . optional($chat)->image) }}" class="chat__image">
                    @endif
                    <!-- 編集 -->
                    <form action="/chat/action/{{$chat->id}}" method="POST">
                        @csrf
                        @method('POST')
                        <textarea name="message" class="chat__edit-textarea" {{ $chat->isSender ? '' : 'readonly' }}>{{ $chat->message}}</textarea>

                        <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

                        @if($chat->isSender)
                        <div class="chat__action-buttons">
                            <button type="submit" name="action" value="edit" class="edit__chat-button">編集</button>
                            <button type="submit" name="action" value="delete" class="delete__chat-button">削除</button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        <div class="send__chat">
            <form action="/send/chat" class="chat__form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="error">
                    @error('message')
                    <span class="error">{{$message}}</span>
                    @enderror
                </div>
                <input type="text" name="message" class="send__chat-input" placeholder="{{ $isCompleted ? '取引は終了しました' : '取引メッセージを記入してください' }}" value="{{ old('message') }}" id="messageInput" {{ $isCompleted ? 'readonly' : '' }}
                    data-transaction-id="{{ $transaction->id }}"
                    data-user-id="{{$user_id}}>

                <div class=" error">
                @error('image')
                <span class="error">{{$message}}</span>
                @enderror
        </div>
        <div class="send__chat--contents">
            <div class="send__chat--contents-image">
                <label for="image" class="image__label">
                    <!-- <img class="send-chat__image"> -->
                    <p class="image__text">画像を追加</p>
                </label>
                <img class="send-chat__image">
                <input type="file" name="image" id="image" class="image__input" accept="image/*">
            </div>
            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

            @if(!$isCompleted)
            <button class="send__chat-button" type="submit">
                <img src="{{ asset('storage/img/send-button.jpg') }}" alt="send-button">
            </button>
            @endif
        </div>
        </form>
    </div>
</div>
<!-- モーダル -->
<div class="modal" id="modal-{{$transaction->id}}" {{ $isValued && $isCompleted ? 'data-auto-open-modal' : '' }}>
    <a href="#!" class="modal-overlay"></a>
    <div class="modal__inner">
        <div class="modal__content">
            <div class="modal__ttl">
                <h3>取引が完了しました。</h3>
            </div>
            <form action="/value" class="modal__form" method="post">
                @csrf
                <p class="modal__value--ttl">
                    今回の取引相手はどうでしたか？
                </p>
                <div class="modal__value">
                    <input type="radio" id="star1" name="value" value="5"><label for="star1">★</label>
                    <input type="radio" id="star2" name="value" value="4"><label for="star2">★</label>
                    <input type="radio" id="star3" name="value" value="3"><label for="star3">★</label>
                    <input type="radio" id="star4" name="value" value="2"><label for="star4">★</label>
                    <input type="radio" id="star5" name="value" value="1"><label for="star5">★</label>
                    <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                </div>
                <div class="modal__btn--parent">
                    <button class="modal__btn">
                        送信する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@if($isValued && $isCompleted)
<script src="{{ asset('js/auto-open-modal.js') }}"></script>
@endif
</div>
<script src="{{ asset('js/transaction.js') }}"></script>
@endsection