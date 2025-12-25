<!-- 商品購入画面 /purchase/{item_id} -->
 @extends('layouts.app')

 @section('css')
 <link rel="stylesheet" href="{{ asset('css/purchase/confirm.css') }}">
 @endsection

 @section('content')
 <form class="purchase-content" action="{{ route('purchase.checkout', $item->id) }}" method="post">
    @csrf

    <!-- 左半分 -->
    <div class="purchase-content__left">
        <div class="purchase-item__inner">
            <div class="purchase-item__img">
                @if($item->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $item->images->first()->image_path) }}" alt="">
                @endif
            </div>
            <div class="purchase-item__name-prise">
                <div class="purchase-item__name-title">
                    <p>{{ $item->title }}</p>
                </div>
                <div class="purchase-item__price price-common">
                    <p>&yen;&nbsp;<span>{{ number_format($item->price) }}</span></p>
                </div>
            </div>
        </div>
        <div class="purchase-item__payment">
            <div class="purchase-item__payment-title">
                <p>支払い方法</p>
            </div>
            <div class="purchase-item__payment-select">
                <select name="pay_method" id="pay_method" class="custom-select selected" >
                    <option value="" disabled selected hidden>選択してください</option>
                    <option value="convenience">コンビニ払い</option>
                    <option value="credit_card">カード払い</option>
                </select>
                <div class="form__error">
                    @error('pay_method')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="purchase-item__delivery">
            <div class="purchase-item__delivary-inner">
                <div class="purchase-item__deliver-title">
                    <p>配送先</p>
                </div>
                <div class="purchase-item__change-btn">
                    <a href="{{ route('purchase.address', $item->id) }}">変更する</a>
                </div>
            </div>
            <div class="purchase-item__address">
                @if($address)
                    <p>
                        &#12306;{{ $address->postal_code }}<br>
                        {{ $address->address }}<br>
                        {{ $address->building }}
                    </p>
                @else
                    <p>住所が登録されていません</p>
                @endif
            </div>
            <div class="form__error">
                @error('address')
                {{ $message }}
                @enderror
            </div>

        </div>
    </div>

    <!-- 右半分 -->
    <div class="purchase-content__right">
        <div class="purchase-table">
            <table class="purchase-table__inner">
                <tr class="purchase-table__row">
                    <td class="purchase-table__header price-header">商品代金</td>
                    <td class="purchase-table__content price-common">
                        <p>&yen;&nbsp;<span>{{ number_format($item->price) }}</span></p>
                    </td>
                </tr>
                <tr class="purchase-table__row">
                    <td class="purchase-table__header payment-header">支払い方法</td>
                    <td class="purchase-table__content" id="selected-pay-method">
                        <p>未選択</p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="purchase-item__btn">
            <button class="purchase-item__btn-submit" type="submit">購入する</button>
        </div>
    </div>
 </form>
 @endsection

 @push('scripts')
 <script>
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('pay_method');
        const display = document.getElementById('selected-pay-method');

        const payMethodLabels = {
            'convenience': 'コンビニ払い',
            'credit_card': 'カード払い'
        };

        select.addEventListener('change', function () {
            const selectedValue = select.value;
            display.textContent = payMethodLabels[selectedValue] || '未選択';
        });
    });
 </script>
 @endpush