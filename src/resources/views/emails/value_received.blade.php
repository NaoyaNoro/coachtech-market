@extends('layouts.app')

@section('navigation')
{{-- ナビゲーションを表示しない --}}
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

<!-- @section('content')
<div class="message">
    <div class="message__innner">
        <p>
        </p>
    </div>
</div>
<p>{{ $sender->name }} さんから評価を受け取りました。</p>
<p>評価: ★ {{ $value }}</p>
<p>商品: {{ $transaction->product->name }}</p>
@endsection -->

@section('content')
<p>
    こんにちわ，{{ $receiver->name }}さん
</p>
<p>{{ $sender->name }} さんから評価を受け取りました。</p>
<p>評価: ★ {{ $value }}</p>
<p>商品: {{ $transaction->product->name }}</p>
@endsection