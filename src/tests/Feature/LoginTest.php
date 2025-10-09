<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * ログインに関するテスト
     *
     * @return void
     */

    use RefreshDatabase;

    // ログイン画面表示メソッド
    public function getLoginPage()
    {
        return $this->get('/login')->assertStatus(200);
    }

    // メールアドレス未入力のバリデーション確認
    public function test_login_email_not_entered_validation_error()
    {
        $this->getLoginPage();

        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors(['email']);

        $errors = session('errors')->getBag('default');
        $this->assertEquals('メールアドレスを入力してください', $errors->first('email'));
    }

    // パスワード未入力のバリデーション確認
    public function test_login_password_not_entered_validation_error()
    {
        $this->getLoginPage();

        $response = $this->post('/login', [
            'email' => 'login-error@example.co.jp',
            'password' => ''
        ]);

        $response->assertSessionHasErrors(['password']);


        $errors = session('errors')->getBag('default');
        $this->assertEquals('パスワードを入力してください', $errors->first('password'));
    }

    // ログイン情報不一致のエラーメッセージ確認
    public function test_login_information_mismatch_error()
    {
        $this->getLoginPage();

        $response = $this->post("/login", [
            'email' => 'login-error@example.co.jp',
            'password' => 'wrongpassword'
        ]);

        $response->assertSessionHas('errorMessage', 'ログイン情報が登録されていません。');
    }

    public function test_login_successful()
    {
        $this->getLoginPage();

        $user = User::factory()->create([
            'email' => 'login@example.co.jp',
            'password' => bcrypt('loginsuccessful'),
        ]);

        $response = $this->post('/login', [
            'email' => 'login@example.co.jp',
            'password' => 'loginsuccessful',
        ]);

        $response->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }
}
