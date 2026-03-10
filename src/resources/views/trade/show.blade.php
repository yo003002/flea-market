@extends('layouts.app-login')

@section('css')
<link rel="stylesheet" href="{{ asset('css/trade/show.css') }}">
@endsection

@section('content')
<div class="trade-content">
    <div class="left">
        <div>
            <p>その他の取引</p>

            @foreach($otherPurchases as $other)
                <div class="other-trade-item">
                    <a href="{{ route('trade.show', $other->id) }}">{{ $other->item->title }}</a>
                </div>
            @endforeach

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
                        <button
                            type="submit"
                            class="trade-button-inner"
                            {{ $purchase->status === 'completed' ? 'disabled' : '' }}>
                            取引を完了する
                        </button>
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
                                <div class="message-text" id="message-text-{{ $message->id }}">
                                    {{ $message->message }}
                                </div>
                            @endif
                            @if($message->image_path)
                                <img src="{{ asset('storage/' . $message->image_path) }}"  class="{{ $message->isMe ? 'my-message-image' : 'other-message-image' }}" alt="送信画像">
                            @endif
                        </div>
                    </div>
                    <div class="button-flex">
                        <div class="button-inner">
                            @if($message->isMe)
                                <button class="update-button" data-id="{{ $message->id }}">編集</button>
                            @endif
                        </div>
                        <div class="button-inner">
                            @if($message->isMe)
                                <form action="{{ route('trade.destroy', $message) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="delete-button">削除</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p>まだメッセージはありません</p>
            @endforelse

        </div>

        <div class="form__error">
            @error('message')
            {{ $message }}
            @enderror
            @error('image')
            {{ $message }}
            @enderror
        </div>
        <form action="{{ route('trade.store', $purchase->id) }}" method="post" enctype="multipart/form-data" class="input">
        @csrf
            <input type="text" name="message" id="message-input" placeholder="取引メッセージを記入してください" class="input-item"></input>

            <label for="image" class="image-label">画像を追加</label>
            <input type="file" id="image" accept="image/*" name="image" class="image-input">

            <button type="submit" class="input-logo"><img src="{{ asset('images/e99395e98ea663a8400f40e836a71b8c4e773b01.png') }}" alt=""></button>
        </form>
    </div>
</div>
@endsection

<script>
document.addEventListener("DOMContentLoaded", function() {

    document.querySelectorAll(".update-button").forEach(button => {

        button.addEventListener("click", function() {

            const messageId = this.dataset.id;
            const messageDiv = document.getElementById("message-text-" + messageId);
            const originalText = messageDiv.textContent.trim();

            messageDiv.textContent = "";

            const textarea = document.createElement("textarea");
            textarea.id = "edit-area-" + messageId;
            textarea.rows = 3;
            textarea.value = originalText;
            textarea.classList.add("edit-textarea");

            const saveBtn = document.createElement("button");
            saveBtn.textContent = "保存";
            saveBtn.addEventListener("click", function() {
                saveMessage(messageId);
            });
            saveBtn.classList.add("edit-btn");

            const cancelBtn = document.createElement("button");
            cancelBtn.textContent = "キャンセル";
            cancelBtn.addEventListener("click", function() {
                cancelEdit(messageId, originalText);
            });
            cancelBtn.classList.add("edit-btn");

            messageDiv.appendChild(textarea);
            messageDiv.appendChild(saveBtn);
            messageDiv.appendChild(cancelBtn);
        });
    });
});

// 保存
function saveMessage(messageId) {

    const textarea = document.getElementById("edit-area-" + messageId);
    const newMessage = textarea.value;

    fetch(`/trade/message/${messageId}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify({
            message: newMessage
        })
    })
    .then(async response => {
        if (!response.ok) {
            const errorData = await response.json();
            throw errorData;
        }
        return response.json();
    })
    .then(data => {

        const messageDiv = document.getElementById("message-text-" + messageId);
        messageDiv.textContent = data.message;

    })
    .catch(error => {
        if(error.errors && error.errors.message) {
            showError(messageId, error.errors.message[0]);
        }
    });
}

// キャンセル
function cancelEdit(messageId, originalText) {

    const messageDiv = document.getElementById("message-text-" + messageId);
    messageDiv.textContent = originalText;
}

// エラー(TradeRequest適用)
function showError(messageId, errorMessage) {
    const messageDiv = document.getElementById("message-text-" + messageId);

    const oldError = messageDiv.querySelector(".form__error");
    if (oldError) oldError.remove();

    const errorDiv = document.createElement("div");
    errorDiv.className = "form__error";
    errorDiv.textContent = errorMessage;

    messageDiv.insertBefore(errorDiv, messageDiv.firstChild);
}

// メッセージ入力保持
document.addEventListener("DOMContentLoaded", function() {
    const input = document.getElementById("message-input");
    const storagekey = "trade_message_{{ $purchase->id }}";

    if (!input) return;

    if (localStorage.getItem(storagekey)) {
        input.value = localStorage.getItem(storagekey);
    }

    input.addEventListener("input", function() {
        localStorage.setItem(storagekey, input.value);
    });

    const form = input.closest("form");
    form.addEventListener("submit", function() {
        localStorage.removeItem(storagekey);
    });
});
</script>