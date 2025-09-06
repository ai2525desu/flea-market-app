<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    // 商品購入画面
    public function showPurchase($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('purchases.show', compact('item'));
    }
}
