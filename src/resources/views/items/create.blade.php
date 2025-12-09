<!-- 商品出品画面  /sell -->
 @extends('layouts.app')

 @section('css')
 <link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
 @endsection

 @section('content')
 <div class="sell-content">
    <div class="sell-item__title">
        <h2>商品の出品</h2>
    </div>
    <form action="" class="sell-item__form">
        <div class="sell-item__img-title">
            <p>商品画像</p>
        </div> 
        <div class="sell-item__img">
            <p>todoここに画像を選択するのボタンを追加</p>
        </div>
        <div class="sell-item__detail">
            <h3>商品の詳細</h3>
        </div>
        <div class="sell-item__category-title">
            <p>カテゴリー</p>
        </div>
        <div class="sell-item__category">
            <p>todoここにカテゴリー一覧を入れて選択できるようにする</p>
        </div>
        <div class="sell-item__status-title">
            <p>商品の状態</p>
        </div>
        <div class="sell-item__status">
            <select name="" id="">
                <option disabled selected>選択してください</option>
            </select>
        </div>
        <div class="sell-item__name-explain-title">
            <h3>商品名と説明</h3>
        </div>
        <div class="sell-item__name-title">
            <p>商品名</p>
        </div>
        <div class="sell-item__name-input">
            <input type="text">
        </div>
        <div class="sell-item__brand-name-title">
            <p>ブランド名</p>
        </div>
        <div class="sell-item__brand-name-input">
            <input type="text">
        </div>
        <div class="sell-item__explain-title">
            <p>商品の説明</p>
        </div>
        <div class="sell-item__explain-textarea">
            <textarea name="" id=""></textarea>
        </div>
        <div class="sell-item__price-title">
            <p>販売価格</p>
        </div>
        <div class="sell-item__price-input">
            <input type="text">
        </div>
        <div class="sell-item__btn">
            <button class="sell-item__btn-submit" type="submit">出品する</button>
        </div>
    </form>
 </div>
 
 @endsection