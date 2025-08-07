<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // ユーザー登録画面の表示
    public function register()
    {
        return view('auth.register');
    }

    // ログイン画面の表示
    public function login()
    {
        return view('auth.login');
    }
}
