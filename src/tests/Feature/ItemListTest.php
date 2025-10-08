<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotEmpty;

class ItemListTest extends TestCase
{
    /**
     * 商品一覧テスト
     *
     * @return void
     */

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // 商品一覧の取得
    public function test_get_product_information()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    // 購入済み商品は「Sold」と表示
    public function test_sold_display_for_purchased_items()
    {
        $purchasingUser = User::factory()->create();
        $purchasedItem = Item::first();

        Purchase::create([
            'user_id' => $purchasingUser->id,
            'item_id' => $purchasedItem->id,
            'payment' => 'card',
            'shipping_post_code' => '123-4567',
            'shipping_address' => '東京都テスト区1-1-1',
            'shipping_building' => 'テストビル101',
        ]);

        $response = $this->get('/')->assertStatus(200);
        $response->assertSee($purchasedItem->item_image);
        $response->assertSee($purchasedItem->item_name);
        $response->assertSee('Sold');
    }

    public function test_the_product_i_have_listed_is_not_displayed()
    {
        $exhibitionUser = User::factory()->create();


        $exhibitionItem = Item::create([
            'user_id' => $exhibitionUser->id,
            'item_name' => '出品テスト',
            'item_image' => 'dummy.jpg',
            'brand' => null,
            'price' => 1500,
            'description' => '出品テストの説明文',
            'condition' => 1,
        ]);


        $response = $this->post('/login', [
            'email' => $exhibitionUser->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($exhibitionUser);

        $response = $this->get('/')->assertStatus(200);
        $response->assertDontSee($exhibitionItem->item_image);
        $response->assertDontSee($exhibitionItem->item_name);
    }
};
