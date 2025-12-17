<!-- 商品詳細画面  /item/{item_id} -->

 @extends('layouts.app')

 @section('css')
 <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
 @endsection

 @section('content')
 <div class="show-content">
    <div class="show-content__item">

        <!-- 商品画像 -->
        <div class="show-content__item-img">
            @if($item->status === 'sold')
                    <div class="sold-badge">sold</div>
            @endif

            @if($item->images->isNotEmpty())
                <img src="{{ asset('storage/' . $item->images->first()->image_path) }}" alt="">
            @endif
        </div>

        <!-- 商品詳細 -->
        <div class="show-content__form" >
            <div class="item-detail">
                <div class="item-detail__title">
                    <h1>{{ $item->title }}</h1>
                </div>
                <div class="item-detail__brand">
                    <p>{{ $item->brand_name }}</p>
                </div>
                <div class="item-detail__price">
                    <p>&yen;<span class="price">{{ number_format($item->price) }}</span><span> (税込) </span></p>
                </div>

                <div class="item-detail__heart-comment">
                    <div class="item-detail__heart">
                        <form action="{{ route('items.like', 
                        ['item_id' => $item->id]) }}" method="post">
                            @csrf
                            <button class="item-detail__heart-btn" type="submit">
                                @if(auth()->check() && $item->isLikeBy(auth()->user()))
                                    <img src="{{ asset('images/ハートロゴ_ピンク.png') }}" alt="">
                                @else
                                    <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" alt="">
                                @endif
                            </button>
                        </form>
                        <p class="item-detail__like-count">{{ $item->likes->count() }}</p>
                    </div>
                    <div class="item-detail__comment">
                        <div class="item-detail__comment-logo">
                            <img src="{{ asset('images/ふきだしロゴ.png') }}" alt="コメントロゴ">
                        </div>
                        <p class="item-detail__comment-count">
                            {{ $item->comments->count() }}
                        </p>
                    </div>
                </div>

                <div class="item-detail__buy-btn">
                    <a href="{{ route('purchase.confirm', $item->id) }}}">購入手続きへ</a>
                </div>
                <div class="item-detail__explain">
                    <h2>商品説明</h2>
                </div>
                <div class="item-detail__explain-content">
                    <p>{{ $item->description }}</p>
                </div>
                <div class="item-detail__information">
                    <h2>商品の情報</h2>
                </div>
                <div class="item-detail__information-status">
                    <div class="information-status__item">
                        <div class="information-status__item-title">
                            <h4>カテゴリー</h4>
                        </div>
                        <div class="information-status__item-data">
                            @foreach($item->categories as $category)
                                <span class="category-tag">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="information-status__item">
                        <div class="information-status__item-title">
                            <h4>商品の状態</h4>
                        </div>
                        <div class="information-status__item-data">
                            <p>{{ $item->condition }}</p>
                        </div>
                    </div>
                </div>

                <div class="item-detail__user-information">
                    @if($item->comments->isEmpty())
                        <p>まだコメントはありません</p>
                    @endif

                    <p class="comment-count">
                        コメント({{ $item->comments->count() }})
                    </p>
                    
                    @if($item->latestComment)
                        <div class="comment-item">
                            <div class="comment-item-wrap">
                                <div class="comment-user">
                                    <img src="{{ $item->latestComment->user->profile_image
                                    ? asset('storage/' . $item->latestComment->user->profile_image)
                                    : asset('images/deforlt-user.png') }}" alt="プロフィール画像" class="comment-user-icon">
                                </div>
                                <div class="comment-user-name">
                                    <span>
                                        {{ $item->latestComment->user->name }}
                                    </span>
                                </div>
                             </div>
                             <div class="comment-body">
                                {{ $item->latestComment->comment }}
                             </div>
                        </div>
                    @endif

                </div>

                <div class="item-detail__comment-title">
                    <h4>商品へのコメント</h4>
                </div>
                @auth
                <form action="{{ route('items.comment', ['item_id' => $item->id]) }}" method="post">
                    @csrf
                    <div class="item-detail__comment-textarea">
                        <textarea name="comment" id="4" required></textarea>
                    </div>

                    <div class="item-detail__comment-btn">
                        <button class="item-detail__comment-btn-submit" type="submit">コメントを送信する</button>
                    </div>
                </form>
                @else
                    <div class="item-detail__comment-textarea">
                        <textarea id="comment-guest" required></textarea>
                    </div>
                    <div class="item-detail__comment-btn">
                        <a href="/login">コメントを送信する</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
 </div>
 @endsection