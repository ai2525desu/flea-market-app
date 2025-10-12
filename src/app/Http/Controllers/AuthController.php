<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        
        Auth::login($user);
        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice');
    }


    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')->with('errorMessage', 'メール認証が完了していません。メールを確認してください。');
            }

            $request->session()->regenerate();
            return redirect()->route('items.index');
        } else {
            return back()->with('errorMessage', 'ログイン情報が登録されていません。');
        }
    }
}
