@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
<div class="thanks__contents">
    <div class="thanks__inner">
        <div class="thanks__ttl">
            <h2>お買い上げありがとうございました</h2>
        </div>
        <div class="thanks__link">
            <a href="/" class="home__link">HOME</a>
        </div>
    </div>
</div>
@endsection