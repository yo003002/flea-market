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
    <form action="/sell" class="sell-item__form" method="post" enctype="multipart/form-data">
        @csrf
        <div class="sell-item__img-title">
            <p>商品画像</p>
        </div> 

        <div class="sell-item__img-select-btn">
            <label for="images" class="sell-item-label">画像を選択する</label>
            <input id="images" type="file" name="images[]" multiple accept="image/*" class="sell-item-input">
        </div>
        <div class="form__error">
            @error('images')
            {{ $message }}
            @enderror
            @error('images.*')
            {{ $message }}
            @enderror
        </div>

        <div id="preview-area" class="preview-area"></div>

        <div class="sell-item__detail">
            <h3>商品の詳細</h3>
        </div>

        <div class="sell-item__category-title">
            <p>カテゴリー</p>
        </div>
        <div class="sell-item__category">
            <p>
                @foreach($categories as $category)
                    <input class="category-checkbox" type="checkbox" id="category{{ $category->id }}" name="category_ids[]" value="{{ $category->id }}">
                    <label class="category-label" for="category{{ $category->id }}">{{ $category->name }}</label>
                @endforeach
            </p>
        </div>
        <div class="form__error">
            @error('category_ids[]')
            {{ $message }}
            @enderror
        </div>

        <div class="sell-item__status-title">
            <p>商品の状態</p>
        </div>
        <div class="sell-item__status">
            <select name="condition" id="condition" class="custom-select selected">
                <option value="" disabled selected hidden>選択してください</option>
                <option value="良好">良好</option>
                <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                <option value="状態が悪い">状態が悪い</option>
            </select>
            <p>todo選択中のカラーを調整する</p>
        </div>
        <div class="form__error">
            @error('condition')
            {{ $message }}
            @enderror
        </div>

        <div class="sell-item__name-explain-title">
            <h3>商品名と説明</h3>
        </div>

        <div class="sell-item__name-title">
            <p>商品名</p>
        </div>
        <div class="sell-item__name-input">
            <input type="text" name="title">
        </div>
        <div class="form__error">
            @error('title')
            {{ $message }}
            @enderror
        </div>

        <div class="sell-item__brand-name-title">
            <p>ブランド名</p>
        </div>
        <div class="sell-item__brand-name-input">
            <input type="text" name="brand_name">
        </div>

        <div class="sell-item__explain-title">
            <p>商品の説明</p>
        </div>
        <div class="sell-item__explain-textarea">
            <textarea name="description" id=""></textarea>
        </div>
        <div class="form__error">
            @error('description')
            {{ $message }}
            @enderror
        </div>

        <div class="sell-item__price-title">
            <p>販売価格</p>
        </div>
        <div class="sell-item__price-input">
            <input type="text" name="price" id="price" value="￥">
        </div>
        <div class="form__error">
            @error('price')
            {{ $message }}
            @enderror
        </div>

        <div class="sell-item__btn">
            <button class="sell-item__btn-submit" type="submit">出品する</button>
        </div>
    </form>
 </div>
 @endsection

 @push('scripts')
 <script>
    const priceInput = document.getElementById('price');

    priceInput.addEventListener('click', function () {
        
        if (this.selectionStart < 1) {
            this.setSelectionRange(1, 1);
        }
    });

     priceInput.addEventListener('input', function (e) {

        let value = e.target.value;

        if (!value.startsWith('￥')) {
            value = '￥' + value.replace(/[^\d]/g, '');
        }
         
        value = value.replace(/[^\d]/g, '');
        
        if (value === '') {
         e.traget.value = '￥';
            return;
        }

        let formatted = Number(value).toLocaleString();

        e.target.value = '￥' + formatted;

        if (e.target.selectionStart < 1) {
            e.target.setSelectionRange(1, 1);
        }
    });
 </script>

 <script>
    document.getElementById('images').addEventListener('change', function(e) {
        const previewArea = document.getElementById('preview-area');
        previewArea.innerHTML = ""; 

        const files = e.target.files;

        Array.from(files).forEach(file => {
            if (!file.type.match('image.*')) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                previewArea.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
 </script>

 @endpush