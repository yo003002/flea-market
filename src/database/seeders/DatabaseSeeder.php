<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */


    public function run()
    {

        $sourceDir = database_path('seeders/dummy_images/users');
        $files = array_diff(scandir($sourceDir), ['.', '..']);

        foreach ($files as $file) {
            if (!str_contains($file, '.jpg')) {
                continue;
            }

            $sourcePath = $sourceDir . '/' . $file;

            if (!Storage::disk('public')->exists('users/' . $file)) {
                Storage::disk('public')->put(
                    'users/' . $file,
                    file_get_contents($sourcePath)
                );
            }
        }

        $this->call([
            UserSeeder::class,
            AddressSeeder::class,
            CategorySeeder::class,
            ItemSeeder::class,
            LikeSeeder::class,
            CommentSeeder::class,
            PurchaseSeeder::class,
        ]);
    }
}
