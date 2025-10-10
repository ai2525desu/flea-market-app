<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Stripe\Checkout\Session as StripeSession;
use Mockery;

class PurchaseTest extends TestCase
{
    /**
     * 商品購入機能テスト
     *
     * @return void
     */

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->user = User::factory()->create();

        Profile::create([
            'user_id' => $this->user->id,
            'image' => 'dummy.jpg'
        ]);

        Address::create([
            'user_id' => $this->user->id,
            'post_code' => '123-4567',
            'address' => '東京都テスト区1-1-1',
            'building' => 'テストビル101',
        ]);

        $this->user->load('address');
    }

    protected $item;

    // 購入処理をまとめたメソッド
    protected function completePurchase(Item $item)
    {
        $response = $this->get("/purchase/{$item->id}");
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
    }

    // 購入するボタンを押すと購入が完了する
    public function testClickThePurchaseButtonToCompleteThePurchase()
    {
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($this->user);

        $item = Item::with('purchase')->first();

        $this->completePurchase($item);
        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'shipping_post_code' => $this->user->address->post_code,
            'shipping_address' => $this->user->address->address,
            'shipping_building' => $this->user->address->building,
        ]);
    }

    // 購入した商品が商品一覧画面でSold表示されるか
    public function testConfirmThatTheWordSoldIsDisplayedOnTheProductListScreen()
    {
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($this->user);

        $item = Item::with('purchase')->first();

        $this->completePurchase($item);
        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'shipping_post_code' => $this->user->address->post_code,
            'shipping_address' => $this->user->address->address,
            'shipping_building' => $this->user->address->building,
        ]);

        $response = $this->get('/')->assertStatus(200);
        $response->assertSee($item->item_image);
        $response->assertSee($item->item_name);
        $response->assertSee('Sold');
    }

    // マイページ上で購入した商品が表示されているか
    public function testIfTheItemIsDisplayedAsAPurchasedItem()
    {
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($this->user);

        $item = Item::with('purchase')->first();

        $this->completePurchase($item);
        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'shipping_post_code' => $this->user->address->post_code,
            'shipping_address' => $this->user->address->address,
            'shipping_building' => $this->user->address->building,
        ]);

        $response = $this->get('/mypage')->assertStatus(200);
        $response = $this->get('/mypage?tab=buy')->assertStatus(200);
        $response->assertSee($this->user->profile->image);
        $response->assertSee($this->user->name);
        $response->assertSee($item->item_image);
        $response->assertSee($item->item_name);
    }
}
