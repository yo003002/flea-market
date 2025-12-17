<!-- 商品一覧画面（トップ画面） -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
<div class="items-content">

    <div class="items-content__link">
        <a href="/" class="items-content__link-item">おすすめ</a>
        <a href="/?tab=mylist" class="items-content__link-item">マイリスト</a>
    </div>

    <div class="items-list">
        @if(request('tab') === 'mylist')
            @auth
                @forelse($mylistItems as $item)
                    <div class="item-image">
                        <a href="{{ route('items.show', ['item_id' => $item->id]) }}">

                            @if($item->status === 'sold')
                                <div class="sold-badge">sold</div>
                            @endif

                            <img src="{{ $item->images->first()
                            ? asset('storage/' . $item->images->first()->image_path)
                            : asset('images/no-image.png') }}" alt="{{ $item->name }}">

                            <p>{{ $item->name }}</p>
                        </a>
                    </div>
                @empty
                    <p>まだお気に入りはありません</p>
                @endforelse
            @endauth
        @else
            @foreach($recommendedItems as $item)
                <div class="item-image">
                    <a href="{{ route('items.show', ['item_id' => $item->id]) }}">

                        @if($item->status === 'sold')
                            <div class="sold-badge">sold</div>
                        @endif

                        <img src="{{ $item->images->first()
                            ? asset('storage/' . $item->images->first()->image_path)
                            : asset('images/no-image.png') }}" alt="{{ $item->name }}">

                        <p>{{ $item->name }}</p>
                    </a>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection