<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $images = [
            '240_F_1019904417_Euz3ZiXU6mm5JAc9EP3fR2s2Vfwa2e5T.jpg',
            '240_F_1378983632_gz7bV0PC1RwGNZBfwtWLUIA0gQoh6kXT.jpg',
            '240_F_1480720556_qGlIAuUYlpZLNp5j4T8JEDWwlgtGXCTl.jpg',
        ];

        User::factory(20)->create()->each(function ($user) use($images) {

            if (rand(1, 100) <= 70) {
                $image = $images[array_rand($images)];

                $from = database_path('seeders/dummy_images/users/' . $image);

                Storage::disk('public')->put(
                    'users/' . $image,
                    file_get_contents($from)
                );

                $user->update([
                    'profile_image' => 'users/' . $image,
                    'is_profile_set' => true,
                ]);
            }
        });
    }
}
