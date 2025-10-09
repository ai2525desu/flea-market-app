<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MylistTest extends TestCase
{
    /**
     * マイリスト一覧取得テスト
     *
     * @return void
     */
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // いいねした商品だけが表示される
    public function test_mylist_show_liked_products()
    {
        $likedUser = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $likedUser->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($likedUser);


        $likedItem = Item::with('likes')->first();

        Like::create([
            'user_id' => $likedUser->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->get('/?tab=mylist')->assertStatus(200);
        $response->assertSee($likedItem->item_image);
        $response->assertSee($likedItem->item_name);
    }


    // 購入済み商品は「Sold」と表示
    public function test_mylist_sold_display_for_purchased_items()
    {

        $purchasingUser = User::factory()->create();
        $response = $this->post('/login', [
            'email' => $purchasingUser->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($purchasingUser);

        $purchasedItem = Item::with('likes', 'purchase')->first();

        Like::create([
            'user_id' => $purchasingUser->id,
            'item_id' => $purchasedItem->id,
        ]);

        Purchase::create([
            'user_id' => $purchasingUser->id,
            'item_id' => $purchasedItem->id,
            'payment' => 'card',
            'shipping_post_code' => '123-4567',
            'shipping_address' => '東京都テスト区1-1-1',
            'shipping_building' => 'テストビル101',
        ]);

        $response = $this->get('/?tab=mylist')->assertStatus(200);
        $response->assertSee($purchasedItem->item_image);
        $response->assertSee($purchasedItem->item_name);
        $response->assertSee('Sold');
    }


    public function test_mylist_the_product_i_have_listed_is_not_displayed()
    {

        $exhibitionUser = User::factory()->create();
        $response = $this->post('/login', [
            'email' => $exhibitionUser->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($exhibitionUser);

        $likedItem = Item::with('likes')->first();

        Like::create([
            'user_id' => $exhibitionUser->id,
            'item_id' => $likedItem->id,
        ]);


        $exhibitionItem = Item::create([
            'user_id' => $exhibitionUser->id,
            'item_name' => '出品テスト',
            'item_image' => 'dummy.jpg',
            'brand' => null,
            'price' => 1500,
            'description' => '出品テストの説明文',
            'condition' => 1,
        ]);


        $response = $this->get('/?tab=mylist')->assertStatus(200);
        $response->assertDontSee($exhibitionItem->item_image);
        $response->assertDontSee($exhibitionItem->item_name);
    }

    public function test_nothing_is_visible_to_unauthorized_users()
    {
        auth()->logout();
        $this->assertFalse(auth()->check());
        $response = $this->get('/?tab=mylist')->assertStatus(200);
        $items = Item::all();
        foreach ($items as $item) {
            $response->assertDontSee($item->item_image);
            $response->assertDontSee($item->item_name);
        }
    }
}
