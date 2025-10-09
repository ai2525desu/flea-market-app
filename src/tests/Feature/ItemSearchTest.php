<?php

namespace Tests\Feature;

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
}
