<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Item;
use App\Models\Like;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    /**
     * 商品詳細画面テスト
     *
     * @return void
     */
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // 商品詳細画面に必要な情報が表示される
    public function test_get_information_on_the_product_details_screen()
    {
        $user = User::factory()->create([
            'name' => '各データ登録ユーザー'
        ]);

        Profile::create([
            'user_id' => $user->id,
            'image' => 'dummy.jpg'
        ]);

        $item = Item::with('categories', 'likes', 'comments')->first();
        $condition = Item::CONDITION[$item->condition];

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        Comment::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment_content' => '商品詳細画面でのコメント確認テストのため'
        ]);

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee($item->item_image);
        $response->assertSee($item->item_name);
        $response->assertSee($item->brand);
        $response->assertSee($item->price);
        $response->assertSee($item->likes->count());
        $response->assertSee($item->description);
        $response->assertSee($condition);
        foreach ($item->categories as $category) {
            $response->assertSee($category->category_name);
        }
        $response->assertSee($item->comments->count());
        foreach ($item->comments as $comment) {
            $response->assertSee($comment->user->profile->image);
            $response->assertSee($comment->user->name);
            $response->assertSee($comment->comment_content);
        }
    }

    // 複数選択されたカテゴリーが表示されているか確認するテスト
    public function test_check_the_display_of_multiple_selected_categories()
    {

        $item = Item::with('categories')->first();

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        foreach ($item->categories as $category) {
            $response->assertSee($category->category_name);
        }
    }
}
