<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class LikeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    // ログインユーザーがいいねアイコン押す→いいね商品として登録→いいねカウント増加
    public function test_user_can_like_an_item()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();
        $response = $this->get("/items/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post(route('items.like', ['item_id' => $item->id]));

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(1, $item->likes()->count());
    }

    // 追加済みのいいねのアイコンは色が変化
    public function test_likes_icon_changes_color_after_liking()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();

        $response = $this->get("/items/{$item->id}");
        $response->assertStatus(200);

        $response->assertSee('heart_default.png');

        $response->assertDontSee('heart_pink.png');

        $this->post(route('items.like', ['item_id' => $item->id]));

        $response = $this->get("/items/{$item->id}");

        $response->assertSee('heart_pink.png');

        $response->assertDontSee('heart_default.png');
    }

    // いいね追加→再度いいね押す→いいねが解除されてカウントも減少
    public function test_user_can_toggle_like_on_item()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();

        // いいね前
        $response = $this->get("/items/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('heart_default.png');
        $response->assertDontSee('heart_pink.png');
        $this->assertEquals(0, $item->likes()->count());

        // いいね追加
        $this->post(route('items.like', ['item_id' => $item->id]));

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(1, $item->likes()->count());

        $response = $this->get("/items/{$item->id}");
        $response->assertSee('heart_pink.png');
        $response->assertDontSee('heart_default.png');

        // いいね解除
        $this->post(route('items.like', ['item_id' => $item->id]));

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(0, $item->likes()->count());

        $response = $this->get("/items/{$item->id}");
        $response->assertSee('heart_default.png');
        $response->assertDontSee('heart_pink.png');
    }

}
