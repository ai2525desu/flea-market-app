<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class PurchaseTest extends TestCase
{
    /**
     * 商品購入機能テスト
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

    // 購入するボタンを押すと購入が完了する
    public function test_click_the_purchase_button_to_complete_the_purchase()
    {
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($this->user);

        $item = Item::with('purchase')->first();
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
    public function test_confirm_that_the_word_sold_is_displayed_on_the_product_list_screen()
    {
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($this->user);

        $item = Item::with('purchase')->first();
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
    public function test_if_the_item_is_displayed_as_a_purchased_item()
    {
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($this->user);

        $item = Item::with('purchase')->first();
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
