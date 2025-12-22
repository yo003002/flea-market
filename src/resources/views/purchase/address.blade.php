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
    <form action="{{ route('purchase.address.update', $item_id) }}" method="post" class="address-form">
        @csrf
        <div class="address-form__inner">
            <div class="address-form__title">
                <p>郵便番号</p>
            </div>
            <div class="address-form__input">
                <input type="text" name="postal_code" value="{{ old('address', $address->postal_code ?? '') }}">
            </div>
        </div>
        <div class="form__error">
            @error('postal_code')
            {{ $message }}
            @enderror
        </div>

        <div class="address-form__inner">
            <div class="address-form__title">
                <p>住所</p>
            </div>
            <div class="address-form__input">
                <input type="text" name="address" value="{{ old('address', $address ?? '') }}">
            </div>
        </div>
        <div class="form__error">
            @error('address')
            {{ $message }}
            @enderror
        </div>

        <div class="address-form__inner">
            <div class="address-form__title">
                <p>建物名</p>
            </div>
            <div class="address-form__input">
                <input type="text" name="building" value="{{ old('building', $address->building ?? '') }}">
            </div>
        </div>
        <div class="address-form__button">
            <button class="address-form__button-submit" type="submit">更新する</button>
        </div>
    </form>
 </div>
 @endsection