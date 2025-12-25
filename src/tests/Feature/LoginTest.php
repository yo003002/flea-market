<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    // メールアドレスが未入力のバリデーション
    public function test_email_is_required_for_login()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => '',
            'password' => '12345678'
        ]);

        $response->assertSessionHasErrors(['email']);

        $response = $this->get('/login');

        $response->assertSee('メールアドレスを入力してください');
    }


    // パスワードが未入力のバリデーション
    public function test_passwird_is_required_for_login()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => ''
        ]);

        $response->assertSessionHasErrors(['password']);

        $response = $this->get('/login');

        $response->assertSee('パスワードを入力してください');
    }

    // 入力情報が間違っている場合のバリデーション
    public function test_login_fails_whis_unregistered_email()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'notfound@example.com',
            'password' => '12345678',
        ]);

        $response->assertSessionHasErrors(['email']);

        $response = $this->get('/login');

        $response->assertSee('ログイン情報が登録されていません');

        $this->assertGuest();
    }

    // 正しい情報入力後→ログインできるか
    public function test_user_can_login_succesfully()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('12345678'),
        ]);

        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '12345678',
        ]);

        $response->assertRedirect('/');

        $this->assertAuthenticatedAs($user);

    }
}
