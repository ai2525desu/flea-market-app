<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = DB::table('categories')->pluck('id', 'category_name')->toArray();

        // 商品１：腕時計
        $itemId = DB::table('items')->insertGetId([
            'user_id' => '1',
            'item_name' => '腕時計',
            'item_image' => 'items/Wristwatch.jpg',
            'brand' => null,
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => 1,
        ]);
        DB::table('category_item')->insert([
            ['item_id' => $itemId, 'category_id' => $categories['ファッション']],
            ['item_id' => $itemId, 'category_id' => $categories['メンズ']]
        ]);

        // 商品2：HDD
        $itemId = DB::table('items')->insertGetId([
            'user_id' => '1',
            'item_name' => 'HDD',
            'item_image' => 'items/HDD.jpg',
            'brand' => null,
            'price' => 5000,
            'description' => '高速で信頼性の高いハードディスク',
            'condition' => 2,
        ]);
        DB::table('category_item')->insert([
            ['item_id' => $itemId, 'category_id' => $categories['家電']],
            ['item_id' => $itemId, 'category_id' => $categories['ゲーム']]
        ]);

        // 商品3：玉ねぎ3束
        $itemId = DB::table('items')->insertGetId([
            'user_id' => '1',
            'item_name' => '玉ねぎ3束',
            'item_image' => 'items/Onion.jpg',
            'brand' => null,
            'price' => 300,
            'description' => '新鮮な玉ねぎ3束のセット',
            'condition' => 3,
        ]);
        DB::table('category_item')->insert([
            ['item_id' => $itemId, 'category_id' => $categories['キッチン']]
        ]);


        // 商品4：革靴
        $itemId = DB::table('items')->insertGetId([
            'user_id' => '1',
            'item_name' => '革靴',
            'item_image' => 'items/LeatherShoes.jpg',
            'brand' => null,
            'price' => 4000,
            'description' => 'クラシックなデザインの革靴',
            'condition' => 4,
        ]);
        DB::table('category_item')->insert([
            ['item_id' => $itemId, 'category_id' => $categories['ファッション']],
            ['item_id' => $itemId, 'category_id' => $categories['メンズ']]
        ]);


        // 商品5：ノートPC
        $itemId = DB::table('items')->insertGetId([
            'user_id' => '1',
            'item_name' => 'ノートPC',
            'item_image' => 'items/Laptop.jpg',
            'brand' => null,
            'price' => 45000,
            'description' => '高性能なノートパソコン',
            'condition' => 1,
        ]);
        DB::table('category_item')->insert([
            ['item_id' => $itemId, 'category_id' => $categories['家電']]
        ]);



        // 商品6：マイク
        $itemId = DB::table('items')->insertGetId([
            'user_id' => '1',
            'item_name' => 'マイク',
            'item_image' => 'items/Microphone.jpg',
            'brand' => null,
            'price' => 8000,
            'description' => '高音質のレコーディング用マイク',
            'condition' => 2,
        ]);
        DB::table('category_item')->insert([
            ['item_id' => $itemId, 'category_id' => $categories['おもちゃ']],
            ['item_id' => $itemId, 'category_id' => $categories['ベビー・キッズ']]
        ]);


        // 商品7：ショルダーバッグ
        $itemId = DB::table('items')->insertGetId([
            'user_id' => '1',
            'item_name' => 'ショルダーバッグ',
            'item_image' => 'items/ShoulderBag.jpg',
            'brand' => null,
            'price' => 3500,
            'description' => 'おしゃれなショルダーバッグ',
            'condition' => 3,
        ]);
        DB::table('category_item')->insert([
            ['item_id' => $itemId, 'category_id' => $categories['ファッション']],
            ['item_id' => $itemId, 'category_id' => $categories['レディース']]
        ]);


        // 商品8：タンブラー
        $itemId = DB::table('items')->insertGetId([
            'user_id' => '1',
            'item_name' => 'タンブラー',
            'item_image' => 'items/Tumbler.jpg',
            'brand' => null,
            'price' => 500,
            'description' => '使いやすいタンブラー',
            'condition' => 4,
        ]);
        DB::table('category_item')->insert([
            ['item_id' => $itemId, 'category_id' => $categories['キッチン']],
            ['item_id' => $itemId, 'category_id' => $categories['インテリア']]
        ]);



        // 商品9：コーヒーミル
        $itemId = DB::table('items')->insertGetId([
            'user_id' => '1',
            'item_name' => 'コーヒーミル',
            'item_image' => 'items/CoffeeMill.jpg',
            'brand' => null,
            'price' => 4000,
            'description' => '手動のコーヒーミル',
            'condition' => 1,
        ]);
        DB::table('category_item')->insert([
            ['item_id' => $itemId, 'category_id' => $categories['キッチン']],
            ['item_id' => $itemId, 'category_id' => $categories['インテリア']]
        ]);


        // 商品10：メイクセット
        $itemId = DB::table('items')->insertGetId([
            'user_id' => '1',
            'item_name' => 'メイクセット',
            'item_image' => 'items/MakeupSet.jpg',
            'brand' => null,
            'price' => 2500,
            'description' => '便利なメイクアップセット',
            'condition' => 2,
        ]);
        DB::table('category_item')->insert([
            ['item_id' => $itemId, 'category_id' => $categories['ファッション']],
            ['item_id' => $itemId, 'category_id' => $categories['レディース']],
            ['item_id' => $itemId, 'category_id' => $categories['コスメ']]
        ]);
    }
}
