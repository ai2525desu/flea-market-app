<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    /**
     * メール認証に関するテスト
     *
     * @return void
     */
    use RefreshDatabase;

    // 会員登録と認証メール送信テスト
    public function test_membership_registration_and_verification_email_sending()
    {
        Notification::fake();

        $this->get('/register')->assertStatus(200);

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/email/verify');
        $this->assertDatabaseHas('users', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('password', $user->password));

        Notification::assertSentTo(
            [$user],
            VerifyEmail::class
        );
    }

    // メール認証誘導画面でボタンを押すとメール認証サイトに遷移する
    public function test_transit_to_email_authentication_site()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);


        $response = $this->actingAs($user)->get('/email/verify');
        $response->assertStatus(200);
        $response->assertSee('認証はこちらから');
        $response->assertSee('http://localhost:8025/');
    }

    // メール認証を完了するとプロフィール設定画面に遷移する
    public function test_transition_after_completing_email_authentication()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/email/verify');
        $response->assertStatus(200);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);
        $response->assertStatus(302);
        $response->assertRedirect('/mypage/profile');

        $this->assertNotEmpty($user->fresh()->email_verified_at);
    }
}
