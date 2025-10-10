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

    // ログイン前のユーザーはコメントが送信できないことを確認するテスト
    public function test_users_who_are_not_logged_in_cannot_submit_comments()
    {
        $item = Item::with('comments')->first();

        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/item/{$item->id}/comment", [
            'item_id' => $item->id,
            'comment_content' => 'コメント送信テスト'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect();

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'comment_content' => 'コメント送信テスト'
        ]);
    }

    // コメント未入力の場合、バリデーションメッセージが表示されるか
    public function test_comment_validation_message_is_displayed()
    {
        $user = User::factory()->create();
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);

        $item = Item::with('comments')->first();
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/item/{$item->id}/comment", [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment_content' => ''
        ]);

        $response->assertSessionHasErrors(['comment_content']);
        $errors = session('errors')->getBag('default');
        $this->assertEquals('コメントを入力してください', $errors->first('comment_content'));
    }

    // コメントが255文字以上の時、バリデーションメッセージが表示される
    public function test_comment_length_validation_message_is_displayed()
    {
        $user = User::factory()->create();
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);

        $item = Item::with('comments')->first();
        $response = $this->get("/item/{$item->id}");
        $response->assertStatus(200);

        $response = $this->post("/item/{$item->id}/comment", [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment_content' => 'コメント数超過時のバリデーションテストコメント数超過時のバリデーションテストコメント数超過時のバリデーションテストコメント数超過時のバリデーションテストコメント数超過時のバリデーションテストコメント数超過時のバリデーションテストコメント数超過時のバリデーションテストコメント数超過時のバリデーションテストコメント数超過時のバリデーションテストコメント数超過時のバリデーションテストコメント数超過時のバリデーションテストコメント数超過時のバリデーションテストコメント数超過時のバリデーションテストコメント数超過時のバリデーションテストコメント数超過時のバリデーションテストコメント数超過時のバリデーションテスト' // 304文字
        ]);

        $response->assertSessionHasErrors(['comment_content']);
        $errors = session('errors')->getBag('default');
        $this->assertEquals('コメントは255文字以内で入力してください', $errors->first('comment_content'));
    }
}
