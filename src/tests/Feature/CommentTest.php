<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\CssSelector\XPath\Extension\FunctionExtension;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /**
     * コメント送信機能に関するテスト
     *
     * @return void
     */

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    // ログイン済みのユーザーがコメントを送信でき、カウント数が増加するかのテスト
    public function test_logged_in_users_can_submit_comments()
    {
        $user = User::factory()->create();
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);

        $item = Item::with('comments')->first();
        $beforeCount = $item->comments->count();

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/item/{$item->id}/comment", [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment_content' => 'コメント送信テスト'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment_content' => 'コメント送信テスト'
        ]);

        $item->load('comments');
        $afterCount = $item->comments->count();
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('コメント送信テスト');
        $response->assertSee((string) $afterCount);
        $this->assertEquals($beforeCount + 1, $afterCount);
    }
}
