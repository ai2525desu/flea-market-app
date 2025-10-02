<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    
    use RefreshDatabase;

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

        $errors = session('errors')->getBag('default');
        $this->assertEquals('お名前を入力してください', $errors->first('name'));
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

        $errors = session('errors')->getBag('default');
        $this->assertEquals('メールアドレスを入力してください', $errors->first('email'));
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

        $errors = session('errors')->getBag('default');
        $this->assertEquals('パスワードを入力してください', $errors->first('password'));
        $this->assertEquals('確認用パスワードを入力してください', $errors->first('password_confirmation'));
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

        $errors = session('errors')->getBag('default');
        $this->assertEquals('パスワードは8文字以上で入力してください', $errors->first('password'));
        $this->assertEquals('確認用パスワードは8文字以上で入力してください', $errors->first('password_confirmation'));
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

        $errors = session('errors')->getBag('default');
        $this->assertEquals('パスワードと一致しません', $errors->first('password_confirmation'));
    }
}
