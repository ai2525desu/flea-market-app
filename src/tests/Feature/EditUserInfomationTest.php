<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditUserInfomationTest extends TestCase
{
    /**
     * ユーザー情報の変更テスト
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

    public function test_edit_user_information()
    {
        $this->actingAs($this->user);


        $response = $this->get('/mypage/profile');
        $response->assertStatus(200);

        $response->assertSee($this->user->profile->image);
        $response->assertSee($this->user->name);
        $response->assertSee($this->user->address->post_code);
        $response->assertSee($this->user->address->address);
        $response->assertSee($this->user->address->building);
    }
}
