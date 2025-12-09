<!-- プロフィール編集画面（設定画面） /mypage/profile　-->
 @extends('layouts.app')

 @section('css')
 <link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}">
 @endsection

 @section('content')
 <div class="edit-content">
    <div class="edit-header">
        <h2>プロフィール設定</h2>
    </div>
    <form action="" class="edit-form">
        <div class="edit-form__img-set">
            <div class="edit-form__img">
                <p>todoここに写真を入れる</p>
            </div>
            <div class="edit-form__img-select-btn">
                <p>todo画像を選択できるようにする</p>
            </div>
        </div>
        <div class="edit-form__name-title">
            <p>ユーザー名</p>
        </div>
        <div class="edit-form__name-input">
            <input type="text" value="todoここに既存の値が入力されているようにする">
        </div>
        <div class="edit-form__postal-code-title">
            <p>郵便番号</p>
        </div>
        <div class="edit-form__postal-code-input">
            <input type="text" value="todoここに既存の値が入力されているようにする">
        </div>
        <div class="edit-form__address-title">
            <p>住所</p>
        </div>
        <div class="edit-form__address-input">
            <input type="text" value="todoここに既存の値が入力されているようにする">
        </div>
        <div class="edit-form__building-title">
            <p>建物名</p>
        </div>
        <div class="edit-form__building-input">
            <input type="text" value="todoここに既存の値が入力されているようにする">
        </div>
        <div class="edit-form__update-btn">
            <button class="edit-form__update-btn-submit" type="submit">更新する</button>
        </div>
    </form>
 </div>
 @endsection