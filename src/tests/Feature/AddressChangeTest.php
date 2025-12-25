<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;


class AddressChangeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_user_can_update_address_and_see_updated_address_on_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '111-1111',
            'address' => '新潟県新潟市',
            'building' => '旧マンション',
        ]);

        $item = Item::factory()->create();

        $this->get("/purchase/{$item->id}/address")
            ->assertStatus(200)
            ->assertSee('111-1111')
            ->assertSee('新潟県新潟市')
            ->assertSee('旧マンション');

        $this->post("/purchase/{$item->id}/address", [
            'postal_code' => '222-2222',
            'address' => '新潟県新潟市',
            'building' => '新マンション',
        ])->assertRedirect();

        $this->get("/purchase/{$item->id}/address")
            ->assertStatus(200)
            ->assertSee('222-2222')
            ->assertSee('新潟県新潟市')
            ->assertSee('新マンション');

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'postal_code' => '222-2222',
            'address' => '新潟県新潟市',
            'building' => '新マンション'
        ]);
    }

    // 変更した住所で購入した時に、購入した商品に変更後の住所が紐づいているか
    public function test_purchase_users_updated_address()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '111-1111',
            'address' => '新潟県新潟市',
            'building' => '旧マンション',
        ]);

        $item = Item::factory()->create([
            'status' => 'selling',
        ]);

        $this->post("/purchase/{$item->id}/address", [
            'postal_code' => '222-2222',
            'address' => '新潟県新潟市',
            'building' => '新マンション',
        ])->assertRedirect();

        $updatedAddress = Address::where('user_id', $user->id)->first();

        session(['pay_method' => 'convenience']);

        $this->get("/purchase/{$item->id}/success")->assertRedirect('/');

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $updatedAddress->id,
            'pay_method' => 'convenience',
        ]);
    }
}
