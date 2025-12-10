<!-- 送付先住所変更画面  /purchase/address/{item_id} -->
 @extends('layouts.app')

 @section('css')
 <link rel="stylesheet" href="{{ asset('css/purchase/address.css') }}">
 @endsection

 @section('content')
 <div class="address-content">
    <div class="address-header">
        <h2>住所の変更</h2>
    </div>
    <form action="" class="address-form">
        <div class="address-form__inner">
            <div class="address-form__title">
                <p>郵便番号</p>
            </div>
            <div class="address-form__input">
                <input type="text">
            </div>
        </div>
        <div class="address-form__inner">
            <div class="address-form__title">
                <p>住所</p>
            </div>
            <div class="address-form__input">
                <input type="text">
            </div>
        </div>
        <div class="address-form__inner">
            <div class="address-form__title">
                <p>建物名</p>
            </div>
            <div class="address-form__input">
                <input type="text">
            </div>
        </div>
        <div class="address-form__button">
            <button class="address-form__button-submit" type="submit">更新する</button>
        </div>
    </form>
 </div>
 @endsection