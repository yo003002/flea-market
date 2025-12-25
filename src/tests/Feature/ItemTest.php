<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Address;
use App\Models\Purchase;
use App\Models\ItemImage;

class ItemTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    // 商品ページ（トップ）を開いて全商品表示
    public function test_items_index_page_is_displays_all_items_before_login()
    {
        $items = Item::factory()->count(3)->create();

        foreach ($items as $item) {
            ItemImage::factory()->create([
                'item_id' => $item->id,
                'image_path' => 'test_img_' . $item->id . '.png',
            ]);
        }

        $response = $this->get('/');

        $response->assertStatus(200);

        foreach ($items as $item) {
            $response->assertSee($item->title);
            $response->assertSee('storage/test_img_' . $item->id . '.png');
        }
    }



    // 購入済み商品にsoldと表示
    public function test_purchased_item_is_displayd_as_sold()
    {
        $item = Item::factory()->create([
            'title' => 'テスト商品',
            'status' => 'sold',
        ]);

        ItemImage::factory()->create([
            'item_id' => $item->id,
            'image_path' => 'test.png',
        ]);

        Purchase::factory()->create([
            'item_id' => $item->id,
            'user_id' => User::factory(),
        ]);

        $response = $this->get("/items/{$item->id}");

        $response->assertSee('sold');

        $response->assertSee('storage/test.png');
    }

    // ログインしたユーザーの商品一覧が自分の商品が出てこないこと
    public function test_logged_in_user_does_not_see_their_own_items()
    {
        $userA = User::factory()->create();

        $myItem = Item::factory()->create([
            'title' => '自分の商品',
            'user_id' => $userA->id,
        ]);

        ItemImage::factory()->create([
            'item_id' => $myItem->id,
            'image_path' => 'myItem.png',
        ]);

        $userB = User::factory()->create();

        $otherItem = Item::factory()->create([
            'title' => '他人の商品',
            'user_id' => $userB->id,
        ]);

        ItemImage::factory()->create([
            'item_id' => $otherItem->id,
            'image_path' => 'otherItem.png',
        ]);

        $this->actingAs($userA);

        $response = $this->get('/');

        $response->assertDontSee('自分の商品');
        $response->assertDontSee('storage/myItem.png');

        $response->assertSee('他人の商品');
        $response->assertSee('storage/otherItem.png');
    }

}
