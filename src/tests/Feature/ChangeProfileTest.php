<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Address;

class ChangeProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_profile_edit_page_displays_previous_user_setting()
    {
        Storage::fake('public');

        $imagePath = 'profile/test.png';
        Storage::disk('public')->put($imagePath, 'dummy');

        $user = User::factory()->create([
            'name' => '太郎',
            'profile_image' => $imagePath,
        ]);

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '111-1111',
            'address' => '新潟県新潟市',
            'building' => 'マンション',
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage/profile');

        $response->assertSee('storage/' . $imagePath);

        $response->assertSee('value="太郎"', false);

        $response->assertSee('value="111-1111"', false);

        $response->assertSee('value="新潟県新潟市"', false);

        $response->assertSee('value="マンション"', false);


    }
}
