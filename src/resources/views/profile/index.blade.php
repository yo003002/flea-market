<!-- プロフィール画面　/mypage -->
 @extends('layouts.app')
 
 @section('css')
 <link rel="stylesheet" href="{{ asset('css/profile/index.css') }}">
 @endsection

 @section('content')
 <div class="mypage-content">
    <div class="mypage-profile">
        <div class="mypage-profile__img">写真</div>
        <div class="mypage-profile__name">ユーザー名</div>
        <form action="" class="mypage-profile__btn">
            <a href="" class="mypage-profile__btn-edit">プロフィールを編集</a>
        </form>
    </div>
    <div class="mypage-item">
        <div class="mypage-sell-item">
            <a href="">出品した商品</a>
        </div>
        <div class="mypage-sold-item">
            <a href="">購入した商品</a>
        </div>
    </div>
 </div>
 @endsection