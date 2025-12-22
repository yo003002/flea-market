<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;

class AddressSeeder extends Seeder
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

            if ($user->address) {
                continue;
            }

            Address::create([
                'user_id' => $user->id,
                'postal_code' => sprintf('%03d-%04d', rand(100, 999), rand(1000, 9999)),
                'address' => $this->randomAddress(),
                'building' => rand(1, 100) <= 50 ? '〇〇マンション' . rand(101, 1005) . '号室' : null,
                'name' => $user->name,
            ]);
        }
    }

    private function randomAddress()
    {
        return collect([
            '新潟県新潟市西区',
            '新潟県新潟市東区',
            '大阪府大阪市北区',
            '大阪府大阪市西区',
            '新潟県新潟市中央区',
        ])->random() . ' ' . rand(1, 5) . ' ' . rand(1, 20);
    }
}
