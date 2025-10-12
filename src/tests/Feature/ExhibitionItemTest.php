<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ExhibitionItemTest extends TestCase
{
    /**
     * 商品出品時の情報登録テスト
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
    }

    public function test_registration_when_exhibition_items()
    {
        $this->actingAs($this->user);


        $response = $this->get('/sell');
        $response->assertStatus(200);

        Storage::fake('public');
        $dummyImage = new UploadedFile(
            base_path('tests/Fixtures/dummy.jpg'),
            'dummy.jpg',
            'image/jpeg',
            null,
            true
        );

        $categories = Category::take(2)->pluck('id')->toArray();
        $response = $this->post('/sell', [
            'user_id' => $this->user->id,
            'item_name' => '出品商品',
            'item_image' => $dummyImage,
            'brand' =>  null,
            'price' => 1500,
            'description' => '出品商品の説明文',
            'condition' => '1',
            'categories' => $categories
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/sell');

        $this->assertDatabaseHas('items', [
            'user_id' => $this->user->id,
            'item_name' => '出品商品',
            'brand' => null,
            'price' => 1500,
            'description' => '出品商品の説明文',
            'condition' => '1',
        ]);

        $item = Item::where('user_id', $this->user->id)
            ->where('item_name', '出品商品')
            ->firstOrFail();
        Storage::disk('public')->assertExists('items/' . $dummyImage->hashName());
        foreach ($categories as $categoryId) {
            $this->assertDatabaseHas('category_item', [
                'item_id' => $item->id,
                'category_id' => $categoryId
            ]);
        }
    }
}
