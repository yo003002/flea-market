<!-- 商品詳細画面  /items/{item_id} -->

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
                                    <img src="{{ asset('images/heart_pink.png') }}" alt="">
                                @else
                                    <img src="{{ asset('images/heart_default.png') }}" alt="">
                                @endif
                            </button>
                        </form>
                        <p class="item-detail__like-count">{{ $item->likes->count() }}</p>
                    </div>
                    <div class="item-detail__comment">
                        <div class="item-detail__comment-logo">
                            <img src="{{ asset('images/comment_icon.png') }}" alt="コメントロゴ">
                        </div>
                        <p class="item-detail__comment-count">
                            {{ $item->comments->count() }}
                        </p>
                    </div>
                </div>

                <div class="item-detail__buy-btn">
                    @if ($item->user_id === auth()->id())
                        <button disabled>自分の商品は購入できません</button>
                    @elseif ($item->status === 'sold')
                        <button disabled>売り切れ</button>
                    @else
                        <a href="{{ route('purchase.confirm', $item->id) }}}">購入手続きへ</a>
                    @endif
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
                            <h3>カテゴリー</h3>
                        </div>
                        <div class="information-status__item-data">
                            @foreach($item->categories as $category)
                                <span class="category-tag">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="information-status__item">
                        <div class="information-status__item-title">
                            <h3>商品の状態</h3>
                        </div>
                        <div class="information-status__item-data">
                            <p>{{ $item->condition }}</p>
                        </div>
                    </div>
                </div>

                <div class="item-detail__user-information">
                    <p class="comment-count">
                        コメント({{ $item->comments->count() }})
                    </p>
                    @foreach ($item->comments as $comment)
                        <div class="comment-item">
                            <div class="comment-item-wrap">
                                <div class="comment-user">
                                    @if ($comment->user->profile_image)
                                        <img src="{{ asset('storage/' . $comment->user->profile_image) }}" alt="プロフィール画像" class="comment-user-icon">
                                    @else
                                        <p></p>
                                    @endif
                                </div>
                                <div class="comment-user-name">
                                    <span>
                                        {{ $comment->user->name  }}
                                    </span>
                                </div>
                            </div>
                            <div class="comment-body">
                               {{ $comment->comment  }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="item-detail__comment-title">
                    <h3>商品へのコメント</h3>
                </div>
                @auth
                <form action="{{ route('items.comment', ['item_id' => $item->id]) }}" method="post">
                    @csrf
                    <div class="item-detail__comment-textarea">
                        <textarea name="comment" id="comment-textarea"></textarea>
                    </div>
                    <div class="form__error">
                        @error('comment')
                        {{ $message }}
                        @enderror
                    </div>
                    <div class="item-detail__comment-btn">
                        <button class="item-detail__comment-btn-submit" type="submit">コメントを送信する</button>
                    </div>
                </form>
                @else
                    <div class="item-detail__comment-textarea">
                        <textarea name="comment" id="comment-guest"></textarea>
                    </div>
                    <div class="form__error">
                        @error('comment')
                        {{ $message }}
                        @enderror
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