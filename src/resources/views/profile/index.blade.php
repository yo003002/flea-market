<!-- プロフィール画面　/mypage -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/index.css') }}">
@endsection

@section('content')
<div class="mypage-content">
    <div class="mypage-profile">

        <div class="mypage-profile__img">
            @if(Auth::user() && Auth::user()->profile_image)
                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="プロフィール画像">
            @else
                <p></p>
            @endif
        </div>
        <div class="mypage-profile__name">
            <h2> {{ Auth::user()->name }}</h2>
            @if($reviewCount > 0)
                <div class="mypage-rating">
                    <div class="star-display">
                        @for($i = 1; $i <= 5; $i++)
                            @if($roundedRating >= $i)
                                <span class="star filled">★</span>
                            @else
                                <span class="star">★</span>
                            @endif
                        @endfor
                    </div>
                </div>
            @endif
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
            <a href="/mypage?page=buy">購入した商品</a>
        </div>
        <div class="mypage-trade-item">
            <a href="/mypage?page=trade">取引中の商品</a>
            @if($unreadCount > 0)
                <span class="notification-badge">{{ $unreadCount }}</span>
            @endif
        </div>
    </div>
</div>

@endsection