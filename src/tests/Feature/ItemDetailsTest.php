<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Category;
use App\Models\Like;
use App\Models\Comment;

class ItemDetailsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;
    
    // すべての情報が商品詳細ページに表示
    public function test_item_detail_page_displays_all_infomation()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => 'profile.png',
        ]);

        $this->actingAs($user);

        $category = Category::factory()->create([
            'name' => '家電',
        ]);

        $item = Item::factory()->create([
            'title' => 'テストテレビ',
            'brand_name' => 'SONY',
            'price' => 50000,
            'description' => '高画質テレビです',
            'condition' => '新品',
        ]);

        $item->categories()->attach($category->id);

        ItemImage::factory()->create([
            'item_id' => $item->id,
            'image_path' => 'tv.png',
        ]);

        Like::factory()->count(3)->create([
            'item_id' => $item->id,
            'is_favorite' => true,
        ]);

        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' =>'とてもいい商品です',
        ]);

        $response = $this->get("/items/{$item->id}");

        $response->assertStatus(200);

        $response->assertSee('storage/tv.png');

        $response->assertSee('テストテレビ');

        $response->assertSee('SONY');

        $response->assertSee('50,000');

        $response->assertSee((string)$item->likes->count());

        $response->assertSee((string)$item->comments->count());

        $response->assertSee('高画質テレビです');

        $response->assertSee('家電');

        $response->assertSee('テストユーザー');

        $response->assertSee('とてもいい商品です');

        $response->assertSee('storage/profile.png');
    }

    // 商品詳細ページに複数のカテゴリーが表示

    public function test_item_detail_page_display_multiple_categories()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        $category1 = Category::factory()->create(['name' => '家電']);
        $category2 = Category::factory()->create(['name' => '家具']);
        $category3 = Category::factory()->create(['name' => '生活用品']);

        $item->categories()->attach([
            $category1->id,
            $category2->id,
            $category3->id,
        ]);

        $response = $this->get("/items/{$item->id}");

        $response->assertStatus(200);

        $response->assertSee('家電');
        $response->assertSee('家具');
        $response->assertSee('生活用品');
    }
}
