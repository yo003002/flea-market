@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/trade.css') }}">
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
        </div>
    </div>

    <div class="trade-itm-content">
        <div class="trade-item">
            @if($trades->isEmpty())
                <p>取引中の商品はありません</p>
            @else
                <ul class="trade-item-list">
                    @foreach($trades as $trade)
                        <li class="trade-item-card">
                            <a href="{{ route('trade.show', $trade->id) }}">

                                @if($trade->item->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $trade->item->images->first()->image_path) }}" alt="">
                                @else
                                    <p>No Image</p>
                                @endif

                                <p>{{ $trade->item->title }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection