<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Webhook;

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
            'success_url' => route('purchases.show', ['item_id' => $item->id]),
            'cancel_url' => route('purchases.show', ['item_id' => $item->id]),
            'metadata' => [
                'user_id' => $user->id,
                'item_id' => $item->id,
                'payment_method' => $request->input('payment_method')
            ]
        ]);

        return redirect($session->url);
    }

    // Webhookを使用してDBにStripe決済完了後のデータをDBへ保存する処理
    public function storePurchase(Request $request)
    {
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                $endpoint_secret
            );
        } catch (\Exception) {
            return response('Invalid', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $user = User::find($session->metadata->user_id ?? null);
            $item = Item::find($session->metadata->item_id ?? null);

            if ($user && $item) {
                $method = $session->payment_method_types[0] ?? 'card';
                $method = $method === 'konbini' ? 'convenience_store' : 'card';

                Purchase::firstOrCreate(
                    ['user_id' => $user->id, 'item_id' => $item->id],
                    [
                        'payment_method' => $method,
                        'shipping_post_code' => $user->address?->post_code,
                        'shipping_address' => $user->address?->address,
                        'shipping_building' => $user->address?->building
                    ]
                );
            }
        }
        return response('OK', 200);
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
