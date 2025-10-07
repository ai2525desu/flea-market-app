<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

use function PHPUnit\Framework\assertNotEmpty;

class ItemListTest extends TestCase
{
    /**
     * 商品一覧テスト
     *
     * @return void
     */

    // トレイトという下記の記述方法
    use RefreshDatabase;

    protected $items;
    protected $loginUser;

    // コンストラクタは使用推奨されていないので、setUpメソッドにてダミーデータを取得し、それを繰り返してすとないぶで使用すること
    /*public function __construct($name = null)
    {
        // TastCaseに定義されているコンストラクタを実行してください。なので、
        parent::__construct($name);

        $this->loginUser = new User([
            'id' => 1,
            'name' => 'テストユーザー',
        ]);

        $this->items = collect([
            new Item([
                'id' => 1,
                'item_name' => 'テスト商品1',
                'item_image' => 'dummy1.jpg',
                'seller_id' => 2,
                'buyer_id' => null, // 未購入
            ]),
            new Item([
                'id' => 2,
                'item_name' => 'テスト商品2',
                'item_image' => 'dummy2.jpg',
                'seller_id' => 3,
                'buyer_id' => 5,
            ]),
            new Item([
                'id' => 3,
                'item_name' => '自分の商品',
                'item_image' => 'dummy3.jpg',
                'seller_id' => 1, // ログインユーザーが出品中
                'buyer_id' => null,
            ]),
        ]);
        }*/

    // この程度ならあえてめそっど記述しなくてよし。せれぞれのところで記述すること
    // 商品一覧画面表示メソッド
    // public function getIndexPage()
    // {
    //     return $this->get('/')->assertStatus(200);
    // }

    // 商品一覧の取得
    // 取得については、ダミーデータをsetUpメソッドに入れて取得しておけば、このテスト前にsetUpのメソッドが呼び出されるので、ただ$tresponseで返せばビューの内容も表示されるので問題なくできる
    // Storageにおける画像の保存状態についてはこの部分のテストでは不要なのでOK
    public function test_get_product_information()
    {
        $response = $this->getIndexPage();
        Storage::fake('public/items');

        foreach ($this->items as $item) {
            $response->assertSee($item->item_name);
            Storage::disk('public')->put('items/' . $item->item_image, 'dummy_content');
            $this->assertTrue(
                Storage::disk('public')->exists('items/' . $item->item_image),
                "ファイル「items/{$item->item_image}」が存在しません"
            );
            $response->assertSee("storage/items/{$item->item_image}");
        }
    }


    /*protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        Itemモデルにインスタンスを作成してDBに保存せずに進める方法
        $dummyImages = ['dummy1.jpg', 'dummy2.jpg', 'dummy3.jpg',];
        foreach ($dummyImages as $filename) {
            Storage::disk('public')->put('items/' . $filename, 'dummy_content');
        }
        ここでテスト商品１が実際のＢｌａｄｅファイル上に明記がないことが問題になている
        $this->items = collect([
            new Item(['item_name' => 'テスト商品1', 'item_image' => 'dummy1.jpg']),
            new Item(['item_name' => 'テスト商品2', 'item_image' => 'dummy2.jpg']),
            new Item(['item_name' => 'テスト商品3', 'item_image' => 'dummy3.jpg']),
        ]);


        この書き方ではItemFactoryが必須になってしまうので__constructメソッドでFactoryを使用しない書き方が有用かと思われる
        Storage::disk('public')->put('items/dummy.jpg', 'dummy_content');
        $this->items = Item::factory()->count(10)->create([
            'item_name' => 'テスト商品',
            'item_image' => 'dummy.jpg'
        ]);
    }


    // 商品一覧画面表示メソッド
    public function getIndexPage()
    {
        return $this->get('/')->assertStatus(200);
    }

    // 全商品のデータを取得して商品一覧画面を開く
    public function test_get_product_information()
    {

        $response = $this->getIndexPage();

        foreach ($this->items as $item) {
            $response->assertSee($item->item_name);
            $this->assertTrue(
                Storage::disk('public')->exists('items/' . $item->item_image),
                "ファイル「items/{$item->item_image}」が存在しません"
            );
            $response->assertSee("storage/items/{$item->item_image}");
        }
    }*/
}
