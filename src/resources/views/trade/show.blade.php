@extends('layouts.app-login')

@section('css')
<link rel="stylesheet" href="{{ asset('css/trade/show.css') }}">
@endsection

@section('content')
<div class="trade-content">
    <div class="left">
        <div>
            <p>その他の取引</p>
            todo 今は自分の出品商品だけ。購入商品も表示させる。購入者も表示させる。
            @if(auth()->id() === $purchase->item->user_id)
                @foreach($otherPurchases as $other)
                    <div class="other-trade-item">
                        <a href="{{ route('trade.show', $other->id) }}">{{ $other->item->title }}</a>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="right">
        <div class="trade-header">
            <div class="header-inner">
                <div class="header-user-image">
                    @if($otherUser->profile_image)
                        <img src="{{ asset('storage/' . $otherUser->profile_image) }}" alt="相手の画像">
                    @else
                        <p></p>
                    @endif
                </div>
                <div class="header-text">「{{ $otherUser->name }}」さんとの取引画面</div>
            </div>
            @if(!$isSeller)
                <form action="{{ route('trade.complete', $purchase) }}" method="post" class="trade-button-form">
                    @csrf
                    @method('PATCH')
                    <div class="trade-button">
                        <button type="submit" class="trade-button-inner">取引を完了する</button>
                    </div>
                </form>
            @endif
        </div>
        @if($shouldShowModal)
            <div class="modal">
                <div class="modal-inner">
                    <div class="modal-title">
                        <p>取引が完了しました。</p>
                    </div>
                    <form action="{{ route('reviews.store') }}" method="post">
                        @csrf
                        <div class="modal-star">
                            <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                            <input type="hidden" name="reviewed_id" value="{{ $otherUser->id }}">
                            <input type="hidden" name="rating" id="rating-value">
                            <div class="star-text">
                                <p>今回の取引相手はどうでしたか？</p>
                            </div>
                            <div class="modal-star-inner">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}">
                                    <label for="star{{ $i }}">★</label>
                                @endfor
                            </div>
                        </div>
                        <div class="modal-button">
                            <button type="submit" class="modal-button-inner">送信する</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- 商品 --}}
        <div class="item">
            <div class="item-image">
                @if($purchase->item->images->isNotEmpty())
                    <img src="{{ asset('storage/' . $purchase->item->images->first()->image_path) }}" alt="商品画像">
                @else
                    <p>No Image</p>
                @endif
            </div>
            <div class="item-inner">
                <div class="item-name">{{ $purchase->item->title }}</div>
                <div class="item-price">￥{{ number_format($purchase->item->price) }}</div>
            </div>
        </div>
        <div class="chat">
            @forelse($messages as $message)
                <div class="chat-message {{ $message->isMe ? 'my-message' : 'other-message' }}">
                    <div class="{{ $message->isMe ? 'my-message' : 'other-message' }}" >
                        @if($message->isMe)
                            <div class="my-message-user-inner">
                                <div class="my-message-user-inner-name">
                                    <span>{{ $message->user->name }}</span>
                                </div>
                                <div class="my-message-user-image">
                                    @if($message->user->profile_image)
                                        <img src="{{ asset('storage/' . $message->user->profile_image) }}" class="chat-message-image-item" alt="ユーザー画像">
                                    @else
                                        <p></p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="other-message-user-inner">
                                <div class="other-message-user-image">
                                    @if($message->user->profile_image)
                                        <img src="{{ asset('storage/' . $message->user->profile_image) }}" class="chat-message-image-item" alt="ユーザー画像">
                                    @else
                                        <p></p>
                                    @endif
                                </div>
                                <div class="other-message-user-inner-name">
                                    <span>{{ $message->user->name }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="{{ $message->isMe ? 'my-message-bubble' : 'other-message-bubble' }}">
                            @if($message->message)
                                {{ $message->message }}
                            @endif
                            @if($message->image_path)
                                <img src="{{ asset('storage/' . $message->image_path) }}"  class="{{ $message->isMe ? 'my-message-image' : 'other-message-image' }}" alt="送信画像">
                            @endif
                        </div>
                    </div>
                @if($message->isMe)
                    <form action="{{ route('trade.destroy', $message) }}" method="post">
                        @csrf
                        @method('DELETE')
                            <button type="submit" class="delete-button">削除</button>
                    </form>
                @endif
                </div>
            @empty
                <p>まだメッセージはありません</p>
            @endforelse

        </div>

        <div class="form__error">
            @error('message')
            {{ $message }}
            @enderror
            @error('images')
            {{ $message }}
            @enderror
        </div>
        <form action="{{ route('trade.store', $purchase->id) }}" method="post" enctype="multipart/form-data" class="input">
        @csrf
            <input type="text" name="message" placeholder="取引メッセージを記入してください" class="input-item"></input>

            <label for="image" class="image-label">画像を追加</label>
            <input type="file" id="image" accept="image/*" name="image" class="image-input">

            <button type="submit" class="input-logo"><img src="{{ asset('images/e99395e98ea663a8400f40e836a71b8c4e773b01.png') }}" alt=""></button>
        </form>
    </div>
</div>
@endsection