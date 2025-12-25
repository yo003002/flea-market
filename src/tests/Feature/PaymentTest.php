<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Stripe\StripeClient;
use Mockery;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;

class PaymentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    // ログインユーザーが選択した支払い方法が購入で反映されている

    public function test_user_can_select_pay_method_and_it_is_saved()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        $item = Item::factory()->create([
            'status' => 'selling',
        ]);

        $response = $this->post("/purchase/{$item->id}/checkout", [
            'pay_method' => 'convenience',
        ]);


        $response->assertRedirect();

        $this->get("/purchase/{$item->id}/success");

        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'address_id' => $address->id,
            'pay_method' => 'convenience',
        ]);
    }
}
