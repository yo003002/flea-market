<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class LikeSeeder extends Seeder
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

        foreach ($users as $user) {

            $items = Item::where('user_id', '!=', $user->id)
                ->inRandomOrder()
                ->take(rand(1, 2))
                ->get();

            foreach ($items as $item) {
                Like::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'item_id' => $item->id,
                    ],
                    [
                        'is_favorite' => true,
                    ]
                    );
            }
        }
    }
}
