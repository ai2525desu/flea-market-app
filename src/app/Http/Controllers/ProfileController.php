<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profiles.show');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profiles.edit', compact('user'));
    }

    public function store(ProfileRequest $request)
    {
        // プロフィール画像はProfileのモデルを通してマイグレーションファイルへ
        // 住所はUserモデルのリレーションaddressを経由してマイグレーションファイルに保存？それとも、Addressモデルを経由して保存？ビューの表示は、Userモデルからリレーションで取得できると思う
        // ユーザー名は、すでに登録済みなので不要。user_idから情報を持ってきてあらかじめ入力される仕組みではないか？
        // $profile = Profile::create(['image' => $request->image]);
        // // この部分、user_idを経由して保存？
        // $addresses = Address::create([
        //     'post_code' => $request->post_code,
        //     'address' => $request->address,
        //     'building' => $request->building
        // ]);

    }
}
