<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    // 商品購入画面
    public function showPurchase(Request $request, $item_id)
    {
        $item = Item::with('purchase')->findOrFail($item_id);
        $user = Auth::user()->load('address');
        $purchase = Purchase::where('item_id', $item->id)->where('user_id', $user->id)->first();
        return view('purchases.show', compact('item', 'user', 'purchase'));
    }

    // 購入処理
    public function storePurchase(PurchaseRequest $request)
    {
        $user = Auth::user();
        $item = Item::findOrFail($request->input('item_id'));
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => $request->input('payment_method'),
            'shipping_post_code' => $user->address?->post_code,
            'shipping_address' => $user->address?->address,
            'shipping_building' => $user->address?->building
        ]);
        return redirect()->route('purchases.show', $item->id)->with('message', '購入が成功しました');
    }

    // 配送先変更画面
    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('purchases.address', compact('item'));
    }

    // 配送先変更処理
    public function update(AddressRequest $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);
        $user->address()->update([
            'post_code' => $request->input('post_code'),
            'address' => $request->input('address'),
            'building' => $request->input('building')
        ]);

        return redirect()->route('purchases.show', $item->id)->with('message', '配送先の変更が完了しました');
    }
}
