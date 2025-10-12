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
     * A basic feature test example.
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
            'email_verifield_at' => null,
        ]);
        
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );
        
        // こちらのメール認証後の遷移先確認中ー＞商品一覧に遷移するとあるが会員登録後のフローとしてどうなのか？
        $user->actingAs($user)->get($verificationUrl)->assert('/mypage/profile');
        $this->get('/email/verify')->assertStatus(200);
        
    }
}
