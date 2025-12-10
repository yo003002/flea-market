<!-- 商品購入画面 /purchase/{item_id} -->
 @extends('layouts.app')

 @section('css')
 <link rel="stylesheet" href="{{ asset('css/purchase/confirm.css') }}">
 @endsection

 @section('content')
 <form class="purchase-content" action="">
    <div class="purchase-content__left">
        <div class="purchase-item__inner">
            <div class="purchase-item__img">
                <p>todo商品画像を入れる</p>
            </div>
            <div class="purchase-item__name-prise">
                <div class="purchase-item__name-title">
                    <p>todo商品名を入れる</p>
                </div>
                <div class="purchase-item__price">
                    <p>todo金額を入れる</p>
                </div>
            </div>
        </div>
        <div class="purchase-item__payment">
            <div class="purchase-item__payment-title">
                <p>支払い方法</p>
            </div>
            <div class="purchase-item__payment-select">
                <select name="" id="">
                    <option disabled selected>todoここに選択肢を入れる</option>
                </select>
            </div>
        </div>
        <div class="purchase-item__delivery">
            <div class="purchase-item__delivary-inner">
                <div class="purchase-item__deliver-title">
                    <p>配送先</p>
                </div>
                <div class="purchase-item__change-btn">
                    <a href="">変更する</a>
                </div>
            </div>
            <div class="purchase-item__address">
                <p>todoここに住所と建物が入る</p>
            </div>
        </div>
    </div>
    <div class="purchase-content__right">
        <form action="" class="purchase-form"></form>
            <div class="purchase-table">
                <table class="purchase-table__inner">
                    <tr class="purchase-table__row">
                        <td class="purchase-table__header price-header">商品代金</td>
                        <td class="purchase-table__content">todo金額をここに入れる</td>
                    </tr>
                    <tr class="purchase-table__row">
                        <td class="purchase-table__header payment-header">支払い方法</td>
                        <td class="purchase-table__content">todo支払い方法をここにいれる</td>
                    </tr>
                </table>
            </div>
        <div class="purchase-item__btn">
            <button class="purchase-item__btn-submit" type="submit">購入する</button>
        </div>
    </div>
 </form>
 @endsection