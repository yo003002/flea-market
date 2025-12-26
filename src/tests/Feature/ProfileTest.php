<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

use App\Models\User;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Purchase;

class ProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    // ログインユーザー・マイページで名前とプロフ画像表示
    public function test_mypage_displays_profile_image_and_name()
    {
        Storage::fake('public');

        $imagePath = 'profile/test.png';
        Storage::disk('public')->put($imagePath, 'dummy');

        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'profile_image' => $imagePath,
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage');

        $response->assertSee('テストユーザー');

        $response->assertSee('storage/' . $imagePath);
    }

    // ログインユーザー・出品商品一覧表示
    public function test_logged_in_user_can_sell_items_on_mypage()
    {
        Storage::fake('public');

        $imagePath = 'items/test.png';
        Storage::disk('public')->put($imagePath, 'dummy');

        $user = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'title' => 'テスト商品',
            'status' => 'sold',
        ]);

        ItemImage::factory()->create([
            'item_id' => $item->id,
            'image_path' => $imagePath,
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage?page=sell');

        $response->assertStatus(200);

        $response->assertSee('テスト商品');

        $response->assertSee('sold');

        $response->assertSee('storage/' . $imagePath);
    }


    // ログインユーザー・購入商品一覧表示
    public function test_logged_in_user_can_see_buy_items_on_mypage()
    {
        Storage::fake('public');

        $imagePath = 'items/test.png';
        Storage::disk('public')->put($imagePath, 'dummy');

        $user = User::factory()->create();

        $item = Item::factory()->create([
            'title' => '購入した商品',
            'status' => 'sold',
        ]);

        ItemImage::factory()->create([
            'item_id' => $item->id,
            'image_path' => $imagePath,
        ]);

        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage?page=buy');

        $response->assertStatus(200);

        $response->assertSee('購入した商品');

        $response->assertSee('sold');

        $response->assertSee('storage/' . $imagePath);
    }

}
