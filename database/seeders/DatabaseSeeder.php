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

        $electronics = Category::create([
            'name' => 'Electronics',
            'description' => 'Devices, gadgets, and accessories.',
        ]);

        $office = Category::create([
            'name' => 'Office',
            'description' => 'Work essentials for a modern workspace.',
        ]);

        Product::create([
            'name' => 'Wireless Keyboard',
            'description' => 'A slim wireless keyboard with quiet keys.',
            'price' => 59.99,
            'category_id' => $office->id,
        ]);

        Product::create([
            'name' => 'Noise-Cancelling Headphones',
            'description' => 'Comfortable headphones for focused work and travel.',
            'price' => 129.00,
            'category_id' => $electronics->id,
        ]);
    }
}
