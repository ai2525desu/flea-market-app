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

        if ($tab === 'mylist' && Auth::check()) {
            $user = Auth::user()->load('likes.item.purchase');
            $items = $user->likes->pluck('item')->filter();
            return view('items.index', compact('user', 'items', 'tab'));
        } elseif ($tab === 'mylist' && !Auth::check()) {
            $items = collect();
            return view('items.index', compact('items', 'tab'));
        } else {
            $query = Item::with('user', 'likes');
            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }
            $items = $query->get();
            $tab = 'recommendation';
            return view('items.index', compact('items', 'tab'));
        }
    }

    // 商品詳細画面
    public function detail($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('items.detail', compact('item'));
    }

    // 商品出品画面
    public function showExhibition()
    {
        return view('items.exhibition');
    }
}
