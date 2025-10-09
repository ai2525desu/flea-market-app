<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeTest extends TestCase
{
    /**
     * いいね機能に関するテスト
     *
     * @return void
     */

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // いいねボタン押下した商品を登録し、カウント数が増加するかのテスト
    public function test_like_registration()
    {
        $user = User::factory()->create();
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);

        $item = Item::with('likes')->first();
        $beforeCount = $item->likes->count();
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/item/{$item->id}/like", [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $item->load('likes');
        $afterCount = $item->likes->count();
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee((string) $afterCount);

        $this->assertEquals($beforeCount + 1, $afterCount);
    }

    // 追加済みのアイコンは色が変化するかに対するテスト
    public function test_added_icons_change_color()
    {
        $user = User::factory()->create();
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);

        $item = Item::with('likes')->first();
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/item/{$item->id}/like", [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $item->load('likes');
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertDontSee('storage/star.png');
        $response->assertSee('storage/yellow-star.png');
    }

    // いいねが解除されて現象表示されることを確認するテスト
    public function test_check_if_the_number_of_likes_has_decreased()
    {
        $user = User::factory()->create();
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);

        $item = Item::with('likes')->first();
        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
        $item->load('likes');
        $beforeCount = $item->likes->count();
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/item/{$item->id}/like", [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $item->load('likes');
        $afterCount = $item->likes->count();
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee((string) $afterCount);

        $this->assertEquals($beforeCount - 1, $afterCount);
    }
}
