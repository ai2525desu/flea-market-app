<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Webhook;

class PurchaseController extends Controller
{

    protected $stripe;
    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    public function showPurchase(Request $request, $item_id)
    {
        $item = Item::with('purchase')->findOrFail($item_id);
        $user = Auth::user()->load('address');
        $purchase = Purchase::where('item_id', $item->id)->where('user_id', $user->id)->first();
        $isPurchased = $purchase ? true : false;
        $selectedMethod = session('payment_method', null);
        return view('purchases.show', compact('item', 'user', 'purchase', 'isPurchased', 'selectedMethod'));
    }

    public function updatePaymentMethod(Request $request, $item_id)
    {
        $request->validate([
            'payment_method' => 'required|in:card, convenience_store',
        ]);

        session(['payment_method' => $request->payment_method]);
        return response()->json(['message' => '支払い方法を更新しました']);
    }

    public function transitionToStripe(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();


        Stripe::setApiKey(config('services.stripe.secret'));
        $paymentForStripe = $request->input('payment_method') === 'convenience_store' ? 'konbini' : 'card';
        $session = $this->stripe->createSession([
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
            'success_url' => route('purchases.card.success', ['item_id' => $item->id]),
            'cancel_url' => route('purchases.show', ['item_id' => $item->id]),
            'metadata' => [
                'user_id' => $user->id,
                'item_id' => $item->id,
                'payment_method' => $request->input('payment_method'),
                'shipping_post_code' => $request->input('shipping_post_code'),
                'shipping_address' => $request->input('shipping_address'),
                'shipping_building' => $request?->input('shipping_building')
            ]
        ]);

        return redirect($session->url);
    }

    public function storeCardPurchase($item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        Purchase::firstOrCreate(
            ['user_id' => $user->id, 'item_id' => $item->id],
            [
                'payment_method' => 'card',
                'shipping_post_code' => $user->address?->post_code,
                'shipping_address' => $user->address?->address,
                'shipping_building' => $user->address?->building
            ]
        );

        return redirect()->route('purchases.show', $item->id);
    }

    public function storeConveniencePurchase(Request $request)
    {
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                $endpoint_secret
            );
        } catch (\Exception $e) {
            return response('Invalid signature', 400);
        }

        $validEvents = [
            'checkout.session.completed',
            'checkout.session.async_payment_succeeded'
        ];

        if (in_array($event->type, $validEvents)) {
            $session = $event->data->object;

            $userId = $session->metadata->user_id ?? null;
            $itemId = $session->metadata->item_id ?? null;

            if (!$userId || !$itemId) {
                return response('Missing metadata', 400);
            }

            $user = User::find($userId);
            $item = Item::find($itemId);

            if (!$user || !$item) {
                return response('Invalid user or item', 400);
            }

            $method = $session->payment_method_types[0] ?? '';
            if ($method !== 'konbini') {
                return response('Not konbini', 200);
            }

            Purchase::firstOrCreate(
                ['user_id' => $user->id, 'item_id' => $item->id],
                [
                    'payment_method'     => 'convenience_store',
                    'shipping_post_code' => $session->metadata->shipping_post_code ?? '',
                    'shipping_address'   => $session->metadata->shipping_address ?? '',
                    'shipping_building'  => $session->metadata->shipping_building ?? null,
                ]
            );
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
        $user->address()->updateOrCreate(
            [],
            [
                'post_code' => $request->input('post_code'),
                'address' => $request->input('address'),
                'building' => $request->input('building')
            ]
        );

        return redirect()->route('purchases.show', $item->id)->with('message', '配送先の変更が完了しました');
    }
}
