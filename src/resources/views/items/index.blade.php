<!-- 商品一覧画面（トップ画面） -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
<div class="items-content">
    <div class="items-content__link">
        <a href="/" class="items-content__link-item">おすすめ</a>
        <a href="/" class="items-content__link-item">マイリスト</a>
    </div>
    <div class="items-img">
        <div class="items-img__list">

        <h2>商品画面一覧</h2>

        todo商品の画像と商品名を入れる
        </div>
    </div>
</div>
@endsection