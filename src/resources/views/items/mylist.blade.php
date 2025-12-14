<!-- トップ　（マイリスト表示） -->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/mylist.css') }}">
@endsection

@section('content')
<div class="items-content">
    <div class="items-content__link">
        <a href="/" class="items-content__link-item {{ request('tab') !== 'mylist' ? 'active' : '' }}">おすすめ</a>
        <a href="/?tab=mylist" class="items-content__link-item {{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>
    <div class="items-list">
        @include('components.sell-item-list', ['items' => $items])
    </div>
</div>
@endsection