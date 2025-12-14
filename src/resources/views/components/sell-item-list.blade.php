
<div class="sell-item">
    @if($items->isEmpty())
        <p>まだ出品した商品はありません</p>
    @else
        <ul class="sell-item-list">
            @foreach($items as $item)
                <li class="sell-item-card">
                    <a href="{{ route('items.show', $item->id) }}">
                        @if($item->images->isNotEmpty())
                            <img src="{{ asset('storage/' . $item->images->first()->image_path) }}" alt="">
                        @else
                            <p>No Image</p>
                        @endif

                        <p>{{ $item->title }}</p>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>