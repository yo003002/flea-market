<!-- プロフィール編集画面（設定画面） /mypage/profile　-->
 @extends('layouts.app')

 @section('css')
 <link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}">
 @endsection

 @section('content')
 <div class="edit-content">
    <div class="edit-header">
        <h2>
            @if($mode === 'first')
                プロフィール設定
            @else
                プロフィール編集
            @endif
        </h2>
    </div>

    <form action="/mypage/profile" method="post" enctype="multipart/form-data" class="edit-form">
        @csrf
        <div class="edit-form__img-set">

            <div class="edit-form__img">
                @if(Auth::user() && Auth::user()->profile_image)
                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="プロフィール画像">
                @else
                <p>画像未設定</p>
                @endif
            </div>

            <div class="edit-form__img-select-btn">
                <label for="profile_image" class="custom-label">画像を選択する</label>
                <input id="profile_image" type="file" name="profile_image" class="custum-input">
            </div>
        </div>


        <div class="edit-form__name-title">
            <p>{{ Auth::user()->name }}</p>
        </div>
        <div class="edit-form__name-input">
            <input type="text" name="name" value="{{ old('name', $user->name) }}">
        </div>

        <div class="edit-form__postal-code-title">
            <p>郵便番号</p>
        </div>
        <div class="edit-form__postal-code-input">
            <input type="text" name="postal_code" value="{{ old('postal_code', $address->postal_code ?? '') }}">
        </div>

        <div class="edit-form__address-title">
            <p>住所</p>
        </div>
        <div class="edit-form__address-input">
            <input type="text" name="address" value="{{ old('address', $address->address ?? '') }}">
        </div>

        <div class="edit-form__building-title">
            <p>建物名</p>
        </div>
        <div class="edit-form__building-input">
            <input type="text" name="building" value="{{ old('building', $address->building ?? '') }}">
        </div>

        <div class="edit-form__update-btn">
            <button class="edit-form__update-btn-submit" type="submit">更新する</button>
        </div>
    </form>
 </div>
 @endsection