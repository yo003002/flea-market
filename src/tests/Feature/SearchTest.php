<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\User;
use App\Models\Like;

class SearchTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    // 部分一致検索可能か
    public function test_user_can_search_items_by_keyword()
    {
        $item1 = Item::factory()->create([
            'title' => 'テレビ',
        ]);

        ItemImage::factory()->create([
            'item_id' => $item1->id,
            'image_path' => 'tv.png',
        ]);

        $item2 = Item::factory()->create([
            'title' => '靴',
        ]);

        ItemImage::factory()->create([
            'item_id' => $item2->id,
            'image_path' => 'shoes.png',
        ]);

        $response = $this->get('/?tab=&keyword=テレ');

        $response->assertStatus(200);

        $response->assertSee('テレビ');
        $response->assertSee('storage/tv.png');

        $response->assertDontSee('靴');
        $response->assertDontSee('storage/shoes.png');
    }

    // 検索結果がマイリストでも保持
    public function test_seach_results_are_preserved_in_mylist()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item1 = Item::factory()->create([
            'title' => 'テスト商品A',
        ]);

        ItemImage::factory()->create([
            'item_id' => $item1->id,
            'image_path' => 'testA.png',
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item1->id,
            'is_favorite' => true,
        ]);

        $item2 = Item::factory()->create([
            'title' => '別の商品B',
        ]);

        ItemImage::factory()->create([
            'item_id' => $item2->id,
            'image_path' => 'testB.png',
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item2->id,
            'is_favorite' => true,
        ]);

        $response = $this->get('/?tab=mylist&keyword=テスト');

        $response->assertStatus(200);

        $response->assertSee('テスト商品A');
        $response->assertSee('storage/testA.png');

        $response->assertDontSee('別の商品B');
        $response->assertDontSee('storage/testB.png');
    }
}
