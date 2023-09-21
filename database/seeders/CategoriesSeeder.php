<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            $name = $faker->word;
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name), // Generate slug from name
                'image' => $faker->imageUrl(100, 100),
            ]);
        }
    }
}

