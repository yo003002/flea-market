<!-- プロフ画面　出品した商品一覧 -->

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/sell.css') }}">
@endsection

@section('content')
 <div class="mypage-content">
    <div class="mypage-profile">

        <div class="mypage-profile__img">
            @if(Auth::user() && Auth::user()->profile_image)
                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="プロフィール画像">
            @else
                <p>画像未設定</p>
            @endif
        </div>
        <div class="mypage-profile__name">
            <h2> {{ Auth::user()->name }}</h2>
        </div>

        <div class="mypage-profile__btn">
            <a href="/mypage/profile" class="mypage-profile__btn-edit">プロフィールを編集</a>
        </div>
    </div>

    <div class="mypage-item">
        <div class="mypage-sell-item">
            <a href="/mypage?page=sell">出品した商品</a>
        </div>
        <div class="mypage-sold-item">
            <a href="">購入した商品</a>
        </div>
    </div>

    <div class="sell-item">
        @if($items->isEmpty())
            <p>まだ出品した商品はありません</p>
        @else
        <ul class="sell-item-list">
            @foreach($items as $item)
            <li class="sell-item-card">
                    @if($item->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $item->images->first()->image_path) }}" alt="">
                    @else
                        <p>No Image</p>
                    @endif

                    <p>{{ $item->title }}</p>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
    
</div>
@endsection