<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    // ログアウトの確認
    public function test_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
