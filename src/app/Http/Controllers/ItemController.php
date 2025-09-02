<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    // 商品一覧画面
    public function index(Request $request)
    {
        $tab = $request->query('tab');

        if (!$tab) {
            $tab = Auth::check() ? 'mylist' : 'recommendation';
        }

        // tabがmylist、ログアウトの時は内容が真っ白の空の状態になりたい。できてない
        if ($tab === 'mylist' && Auth::check()) {
            $user = Auth::user()->load('likes');
            $items = $user->likes->pluck('item')->filter();
            return view('items.index', compact('user', 'items', 'tab'));
        } else {
            $items = Item::with('user', 'likes')->get();
            $tab = 'recommendation';
            return view('items.index', compact('items', 'tab'));
        }
    }

    // 商品出品画面
    public function showExhibition()
    {
        return view('items.exhibition');
    }
}
