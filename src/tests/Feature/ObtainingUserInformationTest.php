<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObtainingUserInformationTest extends TestCase
{
    /**
     * ユーザー情報の取得テスト
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
            'image' => 'dummyicon.jpg'
        ]);

        Address::create([
            'user_id' => $this->user->id,
            'post_code' => '123-4567',
            'address' => '東京都テスト区1-1-1',
            'building' => 'テストビル101',
        ]);

        $this->user->load('address', 'profile');
    }

    public function test_get_the_information_you_need()
    {
        $this->actingAs($this->user);

        $exhibitionItem = Item::create(
            [
                'user_id' => $this->user->id,
                'item_name' => '出品商品1',
                'item_image' => 'dummy.jpg1',
                'brand' => null,
                'price' => 1500,
                'description' => '出品商品1の説明文',
                'condition' => 1,
            ]
        );

        $item = Item::first();
        $purchaseItem = Purchase::create(
            [
                'user_id' => $this->user->id,
                'item_id' => $item->id,
                'payment' => 'card',
                'shipping_post_code' => $this->user->address->post_code,
                'shipping_address' => $this->user->address->address,
                'shipping_building' => $this->user->address->building,
            ]
        );


        $response = $this->get('/mypage');
        $response->assertStatus(200);
        $response->assertSee($this->user->profile->image);
        $response->assertSee($this->user->name);
        $response->assertSee($exhibitionItem->item_name);
        $response->assertSee($exhibitionItem->item_image);

        $response = $this->actingAs($this->user)->get('/mypage?tab=buy');
        $response->assertStatus(200);
        $response->assertSee($this->user->name);
        $response->assertSee($this->user->profile->image);
        $response->assertSee($purchaseItem->item->item_name);
        $response->assertSee($purchaseItem->item->item_image);
    }
}
