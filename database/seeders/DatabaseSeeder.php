<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Demo Admin',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Loop to create 20 categories
        for ($i = 1; $i <= 20; $i++) {
            $category = Category::create([
                'name' => "Category {$i}",
                'description' => "Description for category number {$i}.",
            ]);

            // 3. For each category, loop to create 5 products
            for ($j = 1; $j <= 5; $j++) {
                Product::create([
                    'name' => "Product {$j} of Category {$i}",
                    'description' => "This is the description for product {$j}.",
                    'price' => rand(10, 200) + 0.99, // Generates a random price like 45.99
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
