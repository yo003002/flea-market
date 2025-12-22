<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        //
        $users = User::all();

        $items = [
            [
                'title' => '腕時計',
                'price' => 15000,
                'brand_name' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition' => '良好',
                'image' => 'Armani+Mens+Clock.jpg',
                'categories' => ['ファッション', 'メンズ']
            ],
            [
                'title' => 'HDD',
                'price' => 5000,
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'condition' => '目立った傷や汚れなし',
                'image' => 'HDD+Hard+Disk.jpg',
                'categories' => ['家電'],
            ],
            [
                'title' => '玉ねぎ３束',
                'price' => 300,
                'brand_name' => 'なし',
                'description' => '新鮮な玉ねぎ３束のセット',
                'condition' => 'やや傷や汚れあり',
                'image' => 'iLoveIMG+d.jpg',
                'categories' => ['キッチン'],
            ],
            [
                'title' => '革靴',
                'price' => 4000,
                'brand_name' => '',
                'description' => 'クラシックなデザインの革靴',
                'condition' => '状態が悪い',
                'image' => 'Leather+Shoes+Product+Photo.jpg',
                'categories' => ['ファッション'],
            ],
            [
                'title' => 'ノートPC',
                'price' => 45000,
                'brand_name' => '',
                'description' => '高性能なノートパソコン',
                'condition' => '良好',
                'image' => 'Living+Room+Laptop.jpg',
                'categories' => ['家電'],
            ],
            [
                'title' => 'マイク',
                'price' => 8000,
                'brand_name' => 'なし',
                'description' => '高性能のレコーディング用マイク',
                'condition' => '目立った傷や汚れなし',
                'image' => 'Music+Mic+4632231.jpg',
                'categories' => ['家電'],
            ],
            [
                'title' => 'ショルダーバッグ',
                'price' => 3500,
                'brand_name' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'condition' => 'やや傷や汚れあり',
                'image' => 'Purse+fashion+pocket.jpg',
                'categories' => ['ファッション'],
            ],
            [
                'title' => 'タンブラー',
                'price' => 500,
                'brand_name' => 'なし',
                'description' => '使いやすいタンブラー',
                'condition' => '状態が悪い',
                'image' => 'Tumbler+souvenir.jpg',
                'categories' => ['キッチン'],
            ],
            [
                'title' => 'コーヒーミル',
                'price' => 4000,
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'condition' => '良好',
                'image' => 'Waitress+with+Coffee+Grinder.jpg',
                'categories' => ['キッチン'],
            ],
            [
                'title' => 'メイクセット',
                'price' => 2500,
                'brand_name' => '',
                'description' => '便利なメイクアップセット',
                'condition' => '目立った傷や汚れなし',
                'image' => '外出メイクアップセット.jpg',
                'categories' => ['ファッション'],
            ],
        ];

        foreach ($users as $user) {

            // 1ユーザーあたり2〜3商品
            $count = rand(2, 3);

            for ($i = 0; $i < $count; $i++) {

                try {
                    $data = $items[array_rand($items)];
                    $status = 'selling';

                    $item = Item::create([
                        'user_id' => $user->id,
                        'title' => $data['title'],
                        'price' => $data['price'],
                        'brand_name' => $data['brand_name'],
                        'description' => $data['description'],
                        'condition' => $data['condition'],
                        'status' => $status,
                    ]);

                    // カテゴリー紐づけ
                    $categoryIds = Category::whereIn('name', $data['categories'])->pluck('id');
                    $item->categories()->syncWithoutDetaching($categoryIds);

                    // 画像コピー処理
                    $imagePath = database_path('seeders/dummy_images/' . $data['image']);

                    if (file_exists($imagePath)) {

                        
                        $filename = uniqid() . '_' . $data['image'];

                        Storage::disk('public')->put(
                            'items/' . $filename,
                            file_get_contents($imagePath)
                        );

                        ItemImage::create([
                            'item_id' => $item->id,
                            'image_path' => 'items/' . $filename,
                        ]);
                    }

                } catch (\Throwable $e) {
                    dd($e->getMessage(), $e->getFile(), $e->getLine());
                }
            }
        }
    }
}
