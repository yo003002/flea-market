<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = User::has('address')->get();
        $items = Item::where('status', 'selling')->get();

        foreach ($items as $item) {

            if (rand(1, 100) > 80) {
                continue;
            }

            $buyer = $users
                ->where('id', '!=', $item->user_id)
                ->random();

            Purchase::create([
                'user_id' => $buyer->id,
                'item_id' => $item->id,
                'address_id' => $buyer->address->id,
                'pay_method' => collect(['credit_card', 'convenience'])->random(),
            ]);

            $item->update([
                'status' => 'sold',
            ]);
        }
    }
}
