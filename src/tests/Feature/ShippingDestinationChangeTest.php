<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingDestinationChangeTest extends TestCase
{
    /**
     * 配送先変更機能テスト
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

        Address::create([
            'user_id' => $this->user->id,
            'post_code' => '123-4567',
            'address' => '東京都テスト区1-1-1',
            'building' => 'テストビル101',
        ]);

        $this->user->load('address');
    }

    public function 
}
