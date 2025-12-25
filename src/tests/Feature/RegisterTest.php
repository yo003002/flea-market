<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */


    use RefreshDatabase;

    // 名前が未入力のバリデーション
    public function test_name_is_required()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['name']);

        $response = $this->get('/register');

        $response->assertSee('お名前を入力してください');
    }

    
    // メールが未入力のバリデーション
    public function test_email_is_required()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => '山田',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);

        $response = $this->get('/register');

        $response->assertSee('メールアドレスを入力してください');
    }

    
    // パスワードが未入力のバリデーション
    public function test_password_is_required()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => '山田',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);

        $response = $this->get('/register');

        $response->assertSee('パスワードを入力してください');
    }


    // パスワードが７文字以下のバリデーション
    public function test_password_must_be_at_least_8_characters()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => '山田',
            'email' => 'test@example.com',
            'password' => '1234567',
            'password_confirmation' => '1234567',
        ]);

        $response->assertSessionHasErrors(['password']);

        $response = $this->get('/register');

        $response->assertSee('パスワードは８文字以上で入力してください');
    }

    // パスワードが一致しない時のバリデーション
    public function test_password_confirmed()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => '山田',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '87654321',
        ]);

        $response->assertSessionHasErrors(['password']);

        $response = $this->get('/register');

        $response->assertSee('パスワードと一致しません');
    }

    // 正しく入力した後正常に動くか
    public function test_user_can_register_successflly()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => '山田',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertRedirect('/mypage/profile');

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

}
