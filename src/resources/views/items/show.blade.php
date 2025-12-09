<!-- 商品詳細画面  /item/{item_id} -->
 @extends('layouts.app')

 @section('css')
 <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
 @endsection

 @section('content')
 <div class="show-content">
    <div class="show-content__goods">
        <div class="show-content__goods-img">
            <img src="" alt="">todoここに商品画像を入れる
        </div>
        <div class="show-content__goods-detail">
            <div class="goods-title">
                <h3>todo商品名をここに入れる</h3>
            </div>
            <div class="goods-brand">
                <p>todoブランド名を入れる</p>
            </div>
            <div class="goods-price">
                <p>todo金額を入れる</p>
            </div>
            <div class="goods-heart-comment">
                <p>todoいいねのハートとコメントのimgを入れる</p>
            </div>
            <div class="goods-buy__btn">
                <a href="/">購入手続きへ</a>
            </div>
            <div class="goods-explain">
                <h3>商品説明</h3>
            </div>
            <div class="goods-explain__content">
                <p>todo商品の詳細を入れる</p>
            </div>
            <div class="goods-information">
                <h3>商品の情報</h3>
            </div>
            <div class="goods-infomation__status">
                <p>todoここにカテゴリーと商品の状態を入れる</p>
            </div>
            <div class="user-infomation">
                <p>todoここにコメント数・ユーザーの名前と写真とコメント</p>
            </div>
            <div class="goods-comment-title">
                <p>商品へのコメント</p>
            </div>
            <div class="gooods-comment__textarea">
                <textarea name="" id=""></textarea>
            </div>
            <div class="goods-comment__btn">
                <a href="">コメントを送信する</a>
            </div>
        </div>
    </div>
 </div>
 @endsection