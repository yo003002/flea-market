<!-- プロフ画面　購入した商品一覧 -->

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/buy.css') }}">
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
            <a href="/mypage?page=buy">購入した商品</a>
        </div>
    </div>

    <div class="sold-itm-content">
        <div class="sold-item">
            @if($purchases->isEmpty())
                <p>まだ購入した商品はありません</p>
            @else
                <ul class="sold-item-list">
                    @foreach($purchases as $purchase)
                        <li class="sold-item-card">
                            <a href="{{ route('items.show', $purchase->item->id) }}">

                                @if($purchase->item->status === 'sold')
                                        <div class="sold-badge">sold</div>
                                @endif

                                @if($purchase->item->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $purchase->item->images->first()->image_path) }}" alt="">
                                @else
                                    <p>No Image</p>
                                @endif

                                <p>{{ $purchase->item->title }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    
</div>
@endsection