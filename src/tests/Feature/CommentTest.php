<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class CommentTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    // ログインユーザー→コメント入力・送信→DB保存→コメントカウント追加
    public function test_user_can_post_comment_and_comment_is_displayed()
    {
        // ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();

        // 商品詳細
        $response = $this->get("/items/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('comment_icon.png');
        $response->assertSee('コメント(0)');
        
        $commentText = 'テストコメント';

        $this->post("/items/{$item->id}/comment", [
            'comment' => $commentText,
        ]);

        // DBに保存
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => $commentText,
        ]);

        // 再度商品詳細ページ
        $response = $this->get("/items/{$item->id}");
        $response->assertSee('コメント(1)');
        $response->assertSee($commentText);
        $response->assertSee($user->name);
    }

    // ログイン前ユーザーはコメント送信できない
    public function test_guest_user_cannot_post_comment()
    {
        $item = Item::factory()->create();

        // ゲスト商品ページ
        $response = $this->get("/items/{$item->id}");
        $response->assertStatus(200);

        // ゲストはログインリンク
        $response->assertSee('/login');
        $response->assertSee('コメントを送信する');

        $response = $this->post("/items/{$item->id}/comment", [
            'comment' => 'ゲストコメント',
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment' => 'ゲストコメント',
        ]);
    }

    // コメントが未入力→エラーメッセージ
    public function test_comment_validation_error_when_empty()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();

        $response = $this->from("/items/{$item->id}")
                         ->post("/items/{$item->id}/comment", [
                            'comment' => '',
                         ]);

        $response->assertRedirect("/items/{$item->id}");
        $response->assertSessionHasErrors([
            'comment' => 'コメントを入力してください',
        ]);

        $response = $this->get("/items/{$item->id}");
        $response->assertSee('コメントを入力してください');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
    }


    // コメントが２５５字以上の時エラーメッセージ
    public function test_comment_validation_error_when_comment_exceeds_255_characters()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();

        $longComment = str_repeat('あ', 256);

        $response = $this->from("/items/{$item->id}")
                         ->post("/items/{$item->id}/comment", [
                            'comment' => $longComment,
                         ]);

        $response->assertRedirect("/items/{$item->id}");

        $response->assertSessionHasErrors([
            'comment' => 'コメントは２５５字以内で入力してください',
        ]);

        $response = $this->get("/items/{$item->id}");
        $response->assertSee('コメントは２５５字以内で入力してください');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
    }
}
