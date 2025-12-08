@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.php') }}">
@endsection

@section('content')
<div class="items-content">
    <div class="items-content__link">
        <a href="/" class="items-content__link-item font-red">おすすめ</a>
        <a href="/" class="items-content__link-item font-gray">マイリスト</a>
    </div>
    <div class="items-img">
        <div class="items-img__list">
            @foreach($items as $item)
            <a href="/item/{{ $item->id }}" class="item-card">
                <div class="item-card__image-wrap">
                    <img class="item-card__image" src="{{ asset('atorage/' . $item->image) }}" alt="">
                </div>
                <div class="item-card__title">{{ $item->name }}</div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection