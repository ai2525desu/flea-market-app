<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Item;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ShippingDestinationChangeTest extends TestCase
{
    /**
     * 配送先変更機能テスト
     *
     * @return void
     */

    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->user = User::factory()->create();

        Address::create([
            'user_id' => $this->user->id,
            'post_code' => '123-4567',
            'address' => '東京都テスト区1-1-1',
            'building' => 'テストビル101',
        ]);

        $this->user->load('address');
    }

    // 配送先の変更が購入画面で正しく反映されるか
    public function test_changes_to_the_shipping_address_will_also_be_reflected()
    {
        $this->actingAs($this->user);

        $item = Item::with('purchase')->first();

        $response = $this->get("/purchase/address/$item->id");
        $response->assertStatus(200);

        $response = $this->patch("/purchase/address/$item->id", [
            'user_id' => $this->user->id,
            'post_code' => '765-4321',
            'address' => '千葉県変更区2-2-2',
            'building' => 'テスト201'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/purchase/$item->id");

        $this->assertDatabaseHas('addresses', [
            'user_id' => $this->user->id,
            'post_code' => '765-4321',
            'address' => '千葉県変更区2-2-2',
            'building' => 'テスト201'
        ]);

        $this->user->load('address');

        $response = $this->actingAs($this->user)->get("/purchase/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee($this->user->address->post_code);
        $response->assertSee($this->user->address->address);
        $response->assertSee($this->user->address->building);
    }

    // 購入時に配送先が紐づけられて登録されるか
    public function test_the_delivery_destination_will_be_linked_and_registered()
    {
        $this->actingAs($this->user);

        $item = Item::with('purchase')->first();

        $response = $this->get("/purchase/address/$item->id");
        $response->assertStatus(200);

        $response = $this->patch("/purchase/address/$item->id", [
            'user_id' => $this->user->id,
            'post_code' => '765-4321',
            'address' => '千葉県変更区2-2-2',
            'building' => 'テスト201'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect("/purchase/$item->id");

        $this->assertDatabaseHas('addresses', [
            'user_id' => $this->user->id,
            'post_code' => '765-4321',
            'address' => '千葉県変更区2-2-2',
            'building' => 'テスト201'
        ]);

        $this->user->load('address');

        $response = $this->actingAs($this->user)->get("/purchase/{$item->id}");
        $response->assertStatus(200);

        $mock = Mockery::mock(StripeService::class);
        $mock->shouldReceive('createSession')->once()->andReturn((object) ['url' => "/purchase/success/{$item->id}"]);
        $this->app->instance(StripeService::class, $mock);

        $response = $this->post("/purchase/{$item->id}", [
            'user_id' => $this->user->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'shipping_post_code' => $this->user->address->post_code,
            'shipping_address' => $this->user->address->address,
            'shipping_building' => $this->user->address->building,
        ]);
        $response->assertRedirect();

        $response = $this->get("/purchase/success/{$item->id}");
        $response->assertStatus(302);
        $response->assertRedirect("/purchase/{$item->id}");

        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'shipping_post_code' => $this->user->address->post_code,
            'shipping_address' => $this->user->address->address,
            'shipping_building' => $this->user->address->building,
        ]);
    }
}
