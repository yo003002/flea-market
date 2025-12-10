<!-- 商品詳細画面  /item/{item_id} -->
 @extends('layouts.app')

 @section('css')
 <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
 @endsection

 @section('content')
 <div class="show-content">
    <div class="show-content__item">
        <div class="show-content__item-img">
            <img src="" alt="">
            <p>todoここに商品画像を入れる</p>
        </div>
        <form class="show-content__form" action="">
            <div class="item-detail">
                <div class="item-detail__title">
                    <h2>todo商品名をここに入れる</h2>
                </div>
                <div class="item-detail__brand">
                    <p>todoブランド名を入れる</p>
                </div>
                <div class="item-detail__price">
                    <p>todo金額を入れる</p>
                </div>
                <div class="item-detail__heart-comment">
                    <p>todoいいねのハートとコメントのimgを入れる</p>
                </div>
                <div class="item-detail__buy-btn">
                    <a href="/">購入手続きへ</a>
                </div>
                <div class="item-detail__explain">
                    <h3>商品説明</h3>
                </div>
                <div class="item-detail__explain-content">
                    <p>todo商品の詳細を入れる</p>
                </div>
                <div class="item-detail__information">
                    <h3>商品の情報</h3>
                </div>
                <div class="item-detail__information-status">
                    <p>todoここにカテゴリーと商品の状態を入れる</p>
                </div>
                <div class="item-detail__user-information">
                    <p>todoここにコメント数・ユーザーの名前と写真とコメント</p>
                </div>
                <div class="item-detail__comment-title">
                    <p>商品へのコメント</p>
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