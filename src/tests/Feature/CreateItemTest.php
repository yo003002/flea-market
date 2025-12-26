<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Category;

class CreateItemTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_logged_in_user_can_create_item()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $categories = Category::factory()->count(2)->create();

        $file1 = UploadedFile::fake()->create('test1.jpg', 100);
        $file2 = UploadedFile::fake()->create('test2.png', 100);

        $this->actingAs($user);

        $postData = [
            'title' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'テスト説明文',
            'condition' => '良好',
            'price' => '1000',
            'category_ids' => $categories->pluck('id')->toArray(),
            'images' => [$file1, $file2],
        ];

        $response = $this->post('/sell', $postData);

        $response->assertRedirect('/mypage');

        $this->assertDatabaseHas('items', [
            'title' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'テスト説明文',
            'condition' => '良好',
            'price' => '1000',
            'status' => 'selling',
            'user_id' => $user->id,
        ]);

        $item = Item::where('title', 'テスト商品')->first();

        foreach ($categories as $category) {
            $this->assertDatabaseHas('item_category', [
                'item_id' => $item->id,
                'category_id' => $category->id,
            ]);
        }

        $this->assertDatabaseCount('item_images', 2);

        Storage::disk('public')->assertExists('item_images/' . $file1->hashName());
        Storage::disk('public')->assertExists('item_images/' . $file2->hashName());
    }
}
