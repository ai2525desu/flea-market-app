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
            'item_name' => '腕時計',
            'item_image' => 'items/Wristwatch.jpg',
            'brand' => null,
            'price' => 15000,
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => '良好',
        ]);
        DB::table('category_item')->insert([
            ['item_id' => $itemId, 'category_id' => $categories['ファッション']],
            ['item_id' => $itemId, 'category_id' => $categories['メンズ']]
        ]);

        // 商品2：
        $itemId = DB::table('items')->insertGetId([
            'item_name' => '',
            'item_image' => '',
            'brand' => '',
            'price' => '',
            'description' => '',
            'condition' => '',
        ]);
        DB::table('category_item')->insert([
            ['item_id' => $itemId, 'category_id' => $categories['']],
            ['item_id' => $itemId, 'category_id' => $categories['']]
        ]);
    }
}
