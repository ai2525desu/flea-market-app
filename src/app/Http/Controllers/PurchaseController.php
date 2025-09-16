<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PurchaseController extends Controller
{
    // 商品購入画面
    public function showPurchase(Request $request, $item_id)
    {
        $item = Item::with('purchase')->findOrFail($item_id);
        $user = Auth::user()->load('address');
        $purchase = Purchase::where('item_id', $item->id)->where('user_id', $user->id)->first();
        $isPurchased = $purchase ? true : false;
        return view('purchases.show', compact('item', 'user', 'purchase', 'isPurchased'));
    }

    // Stripe画面への遷移
    public function transitionToStripe(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();


        Stripe::setApiKey(config('services.stripe.secret'));
        $paymentForStripe = $request->input('payment_method') === 'convenience_store' ? 'konbini' : 'card';
        $session = Session::create([
            'payment_method_types' => [$paymentForStripe],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->item_name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchases.store', ['item_id' => $item->id]),
            // 'success_url' => route('purchases.success', ['item_id' => $item->id]),
            'cancel_url' => route('purchases.show', ['item_id' => $item->id]),
            'metadata' => [
                'user_id' => $user->id,
                'item_id' => $item->id,
                // 追加
                'payment_method' => $request->input('payment_method')
            ]
        ]);

        return redirect($session->url);
    }
    // Stripeでの決済後にこのURLを呼び出すのは不適切？

    // 購入処理
    public function storePurchase(Request $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);
        $purchase = Purchase::firstOrCreate(
            ['user_id' => $user->id, 'item_id' => $item->id],
            [
                'payment_method' => $request->input('payment_method', 'card'),
                'shipping_post_code' => $user->address?->post_code,
                'shipping_address' => $user->address?->address,
                'shipping_building' => $user->address?->building
            ]
        );
        return redirect()->route('purchases.show', $item->id)->with('message', '購入が成功しました');
    }
    // Stripe決済成功後に呼び出される
    // public function handleSuccess(Request $request, $item_id)
    // {
    //     $item = Item::findOrFail($item_id);
    //     $user = Auth::user();

    //     // すでに購入済みでない場合のみ保存
    //     $purchase = Purchase::firstOrCreate(
    //         ['user_id' => $user->id, 'item_id' => $item->id],
    //         [
    //             'payment_method' => $request->input('payment_method', 'card'),
    //             'shipping_post_code' => $user->address?->post_code,
    //             'shipping_address' => $user->address?->address,
    //             'shipping_building' => $user->address?->building
    //         ]
    //     );

    //     return redirect()->route('purchases.show', $item->id)
    //         ->with('message', '購入が成功しました');
    // }


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
