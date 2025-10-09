<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    /**
     * 商品検索テスト
     *
     * @return void
     */
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // 商品名部分一致での検索テスト
    public function test_partial_match_search()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->get('/?item_name=腕');
        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertDontSee('玉ねぎ');
    }

    // 商品検索がマイリストでも保持されるかのテスト
    public function test_confirm_retention_of_search_data_in_mylist()
    {
        $searchUser = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $searchUser->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($searchUser);

        $likedItem = Item::first();

        Like::create([
            'user_id' => $searchUser->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->get('/?item_name=腕');
        $response->assertStatus(200)->assertSee('腕時計');

        $response = $this->get('/?tab=mylist&item_name=腕');
        $response->assertStatus(200)->assertSee('腕時計');
        $response->assertDontSee('玉ねぎ');
    }
}
