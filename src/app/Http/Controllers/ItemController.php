<?php

namespace App\Http\Controllers;

use App\Models\Category;
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

        $keyword = $request->query('item_name');
        if ($tab === 'mylist') {
            if (Auth::check()) {
                $user = Auth::user();
                $likedItemIds = $user->likes->pluck('item_id');
                $items = Item::with('user', 'likes', 'purchase')
                    ->whereIn('id', $likedItemIds)
                    ->ItemNameSearch($keyword)
                    ->get();
            } else {
                $items = collect();
            }
        } else {
            $query = Item::with('user', 'likes', 'purchase');
            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }
            $items = $query->ItemNameSearch($keyword)->get();
            $tab = 'recommendation';
        }
        return view('items.index', compact('items', 'tab'));
    }

    // 商品詳細画面
    public function detail($item_id)
    {
        $item = Item::with('categories')->findOrFail($item_id);
        $condition = Item::CONDITION[$item->condition];
        return view('items.detail', compact('item', 'condition'));
    }

    // 商品出品画面
    public function showExhibition()
    {
        return view('items.exhibition');
    }
}
