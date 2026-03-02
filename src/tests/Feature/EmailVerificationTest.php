<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class EmailVerificationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    // 会員登録後、認証メールが送信される
    public function test_user_receives_verification_email_after_reqistration()
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertRedirect('/mypage/profile');

        // ユーザーが作成されていること
        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);

        // 通知が送信されている
        Notification::assertSentTo(
            [$user],
            VerifyEmail::class
        );
    }

    // 誘導画面が表示
    public function test_verify_email_page_is_displayed()
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/email/verify');

        $response->assertStatus(200);
        $response->assertSee('認証はこちらから');
    }

    // 「認証はこちらから」を押すと認証メールが送信される
    public function test_click_verify_button_sends_verification_email()
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->post(route('verification.send'))
            ->assertRedirect();

        Notification::assertSentTo(
            $user,
            VerifyEmail::class
        );
    }

    // メール認証サイトを表示（メール内リンクから認証サイトに還移できる）して認証完了後プロフィール設定画面へ
    public function test_user_can_verify_email_via_signed_url()
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        $response->assertRedirect('/mypage/profile');
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
