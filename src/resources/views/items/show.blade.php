<!-- 商品詳細画面  /item/{item_id} -->

 @extends('layouts.app')

 @section('css')
 <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
 @endsection

 @section('content')
 <div class="show-content">
    <div class="show-content__item">
        
        <!-- 商品画像 -->
        <div class="show-content__item-img">
            @if($item->images->isNotEmpty())
                <img src="{{ asset('storage/' . $item->images->first()->image_path) }}" alt="">
            @endif
        </div>

        <!-- 商品詳細 -->
        <form class="show-content__form" action="">
            <div class="item-detail">
                <div class="item-detail__title">
                    <h1>{{ $item->title }}</h1>
                </div>
                <div class="item-detail__brand">
                    <p>{{ $item->brand_name }}</p>
                </div>
                <div class="item-detail__price">
                    <p>￥<span class="price">{{ number_format($item->price) }}</span><span> (税込) </span></p>
                </div>
                <div class="item-detail__heart-comment">
                    <div class="icon">
                        <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" alt="">
                    </div>
                    <div class="icon">
                        <img src="{{ asset('/images/ふきだしロゴ.png') }}" alt="">
                    </div>
                </div>
                <div class="item-detail__buy-btn">
                    <a href="/">購入手続きへ</a>
                </div>
                <div class="item-detail__explain">
                    <h2>商品説明</h2>
                </div>
                <div class="item-detail__explain-content">
                    <p>{{ $item->description }}</p>
                </div>
                <div class="item-detail__information">
                    <h2>商品の情報</h2>
                </div>
                <div class="item-detail__information-status">
                    <div class="information-status__item">
                        <div class="information-status__item-title">
                            <h4>カテゴリー</h4>
                        </div>
                        <div class="information-status__item-data">
                            @foreach($item->categories as $category)
                                <span class="category-tag">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="information-status__item">
                        <div class="information-status__item-title">
                            <h4>商品の状態</h4>
                        </div>
                        <div class="information-status__item-data">
                            <p>{{ $item->condition }}</p>
                        </div>
                    </div>
                </div>
                <div class="item-detail__user-information">
                    <p>todoここにコメント数・ユーザーの名前と写真とコメント</p>
                </div>
                <div class="item-detail__comment-title">
                    <h4>商品へのコメント</h4>
                </div>
                <div class="item-detail__comment-textarea">
                    <textarea name="" id=""></textarea>
                </div>
                <div class="item-detail__comment-btn">
                    <button class="item-detail__comment-btn-submit" type="submit">コメントを送信する</button>
                </div>
            </div>
        </form>
    </div>
 </div>
 @endsection