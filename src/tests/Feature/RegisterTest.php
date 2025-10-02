<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PhpParser\Node\Expr\PostDec;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 会員登録画面表示メソッド
    public function getRegisterPage()
    {
        return $this->get('/register')->assertStatus(200);
    }

    // 名前未入力のバリデーション確認
    public function test_registration_name_not_entered_validation_error()
    {
        $this->getRegisterPage();

        $response = $this->post('/register', [
            'name' => '',
            'email' => 'register-error@example.co.jp',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertSessionHasErrors(['name']);
    }


    // メールアドレス未入力のバリデーション確認
    public function test_registration_email_not_entered_validation_error()
    {
        $this->getRegisterPage();

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertSessionHasErrors(['email']);
    }


    // パスワード未入力のバリデーション確認
    public function test_registration_password_not_entered_validation_error()
    {
        $this->getRegisterPage();

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'register-error@example.co.jp',
            'password' => '',
            'password_confirmation' => ''
        ]);

        $response->assertSessionHasErrors(['password', 'password_confirmation']);
    }


    // パスワードが7文字以内のバリデーション確認
    public function test_registration_password_length_validation_error()
    {
        $this->getRegisterPage();

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'register-error@example.co.jp',
            'password' => 'pass',
            'password_confirmation' => 'pass'
        ]);

        $response->assertSessionHasErrors(['password', 'password_confirmation']);
    }


    // パスワードの不一致のバリデーション確認
    public function test_registration_password_mismatch_validation_error()
    {
        $this->getRegisterPage();

        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'register-error@example.co.jp',
            'password' => 'password123',
            'password_confirmation' => 'password321'
        ]);

        $response->assertSessionHasErrors(['password_confirmation']);
    }
}
