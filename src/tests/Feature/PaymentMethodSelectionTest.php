<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodSelectionTest extends TestCase
{
    /**
     * 支払い方法選択機能のテスト
     *
     * @return void
     */

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_check_subtotal_changes_for_purchased_content()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::with('purchase')->first();
        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $response = $this->postJson(
            route('purchases.update_payment_method', ['item_id' => $item->id]),
            ['payment_method' => 'card']
        );

        $response->assertStatus(200);
        $this->assertEquals('card', session('payment_method'));
    }
}
