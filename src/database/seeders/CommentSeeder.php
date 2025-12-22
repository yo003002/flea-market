<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Log::info('CommentSeeder start');
        //
        $items = Item::all();
        $users = User::all();

        foreach ($items as $item) {
            
            $otherUsers = $users->where('id', '!=', $item->user_id); 

            if ($otherUsers->isEmpty()) {
                 continue; 
            }

            // 各商品に必ず１件以上
            $commentCount = rand(1, min(3, $otherUsers->count()));

            $commentUsers = $otherUsers->shuffle()->take($commentCount);

            foreach ($commentUsers as $user) {
                Comment::factory()->create([
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                ]);
            }
        }
        \Log::info('CommentSeeder end');
    }
}
