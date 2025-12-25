<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\Item;
use App\Models\ItemImage;

class MylistTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    // ログインユーザーのみいいねした商品がマイリストに表示
    public function test_logged_in_user_can_view_mylist_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $item = Item::factory()->create([
            'title' => 'いいねした商品',
        ]);

        ItemImage::factory()->create([
            'item_id' => $item->id,
            'image_path' => 'liked_img.png',
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'is_favorite' => true,
        ]);

        $otherItem = Item::factory()->create([
            'title' => '他人のいいね商品',
        ]);

        Like::factory()->create([
            'user_id' => User::factory(),
            'item_id' => $otherItem->id,
            'is_favorite' => true,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertSee('いいねした商品');
        $response->assertSee('storage/liked_img.png');

        $response->assertDontSee('他人のいいね商品');
    }

    // いいねした商品の購入済み商品にsold表示
    public function test_sold_item_in_mylist_shows_sold_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'title' =>  '売り切れ商品',
            'status' => 'sold',
        ]);

        ItemImage::factory()->create([
            'item_id' => $item->id,
            'image_path' => 'sold_img.png',
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'is_favorite' => true,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertSee('sold');

        $response->assertSee('売り切れ商品');

        $response->assertSee('storage/sold_img.png');
    }


    // 未承認のユーザーはマイリスト何も表示されない
    public function test_guest_user_cannot_view_mylist_items()
    {
        $user = User::factory()->create();

        $item = Item::factory()->create([
            'title' => 'いいねした商品',
        ]);

        ItemImage::factory()->create([
            'item_id' => $item->id,
            'image_path' => 'liked_img.png',
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'is_favorite' => true,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertDontSee('いいねした商品');
        $response->assertDontSee('storage/liked_img.png');

        $response->assertDontSee('まだお気に入りはありません');
    }
}
