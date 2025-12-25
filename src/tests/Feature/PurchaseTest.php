<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;
use Stripe\StripeClient;
use App\Models\Item;
use App\Models\User;
use App\Models\Address;
use App\Models\Purchase;
use App\Models\ItemImage;


class PurchaseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;


    // ログイン→商品購入画面→購入ボタン→stripe決済→購入完了
    public function test_user_can_purchase_item_from_purchase_page_to_success()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create();

        $mock = \Mockery::mock('alias:Stripe\Checkout\Session');
        $mock->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'id' => 'cs_test_123',
                'url' => 'https://stripe.test/checkout',
            ]);

        $this->app->instance(\Stripe\StripeClient::class, $mock);

        // 購入画面開く
        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee($item->title);

        // 購入ボタン押す
        $response = $this->post("/purchase/{$item->id}/checkout", [
            'pay_method' => 'credit_card',
        ]);

        // Stripe にリダイレクトされる
        $response->assertRedirect('https://stripe.test/checkout');

        $this->withSession(['pay_method' => 'card']);

        $response = $this->get("/purchase/{$item->id}/success");

        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'address_id' => $address->id,
            'pay_method' => 'card',
        ]);

        $response->assertRedirect('/');

    }


    // 購入商品にsold表示
    public function test_purchased_item_shows_sold_label()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'status' => 'sold',
        ]);

        ItemImage::factory()->create([
            'item_id' => $item->id,
            'image_path' => 'test.png',
        ]);

        Purchase::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'pay_method' => 'card',
        ]);

        $response = $this->get("/items/{$item->id}");

        $response->assertSee('sold');
        $response->assertSee('test.png');
    }

    // 購入した商品がプロフィールの購入商品一覧に追加
    public function test_purchase_item_is_listed_in_user_profile_buy_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create([
            'title' => 'テスト商品',
        ]);

        ItemImage::factory()->create([
            'item_id' => $item->id,
            'image_path' => 'test_img.png',
        ]);

        Purchase::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'pay_method' => 'card',
        ]);

        $response = $this->get('/mypage?page=buy');

        $response->assertStatus(200);

        $response->assertSee('テスト商品');

        $response->assertSee('storage/test_img.png');
    }
}
