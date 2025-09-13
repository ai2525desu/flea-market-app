<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // 商品購入画面
    public function showPurchase($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user()->load('address');
        return view('purchases.show', compact('item', 'user'));
    }

    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('purchases.address', compact('item'));
    }

    public function storePurchase() {}
}
