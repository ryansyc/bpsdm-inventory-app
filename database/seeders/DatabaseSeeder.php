<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ItemCategory;
use App\Models\Item;
use App\Models\ItemEntry;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Gudang Utama',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'super-admin',
        ]);

        User::factory()->create([
            'name' => 'Gudang 1',
            'email' => 'gudang1@gudang1.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Gudang 2',
            'email' => 'gudang2@gudang2.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        ItemCategory::create([
            'name' => 'Electronics',
        ]);

        ItemCategory::create([
            'name' => 'Furniture',
        ]);

        ItemCategory::create([
            'name' => 'Clothing',
        ]);

        ItemCategory::create([
            'name' => 'Books',
        ]);

        ItemCategory::create([
            'name' => 'Kitchenware',
        ]);

        Item::create([
            'code' => 'ABC123',
            'name' => 'Laptop',
            'category_id' => 1,
            'quantity' => 10,
            'user_id' => 1,
        ]);

        Item::create([
            'code' => 'DEF456',
            'name' => 'Sofa',
            'category_id' => 2, // Furniture
            'quantity' => 5,
            'user_id' => 1,
        ]);

        Item::create([
            'code' => 'GHI789',
            'name' => 'T-shirt',
            'category_id' => 3, // Clothing
            'quantity' => 20,
            'user_id' => 1,
        ]);

        Item::create([
            'code' => 'JKL012',
            'name' => 'Novel',
            'category_id' => 4, // Books
            'quantity' => 15,
            'user_id' => 1,
        ]);

        Item::create([
            'code' => 'MNO345',
            'name' => 'Cookware Set',
            'category_id' => 5, // Kitchenware
            'quantity' => 8,
            'user_id' => 1,
        ]);

        ItemEntry::create([
            'entry_date' => now(),
            'item_id' => 1,
            'quantity' => 10,
            'description' => 'Andi',
            'user_id' => 1
        ]);

        ItemEntry::create([
            'entry_date' => now(),
            'item_id' => 2,
            'quantity' => 5,
            'description' => 'Budi',
            'user_id' => 1
        ]);

        ItemEntry::create([
            'entry_date' => now(),
            'item_id' => 3,
            'quantity' => 20,
            'description' => 'Caca',
            'user_id' => 1
        ]);

        ItemEntry::create([
            'entry_date' => now(),
            'item_id' => 4,
            'quantity' => 15,
            'description' => 'Dedi',
            'user_id' => 1
        ]);

        ItemEntry::create([
            'entry_date' => now(),
            'item_id' => 5,
            'quantity' => 8,
            'description' => 'Eko',
            'user_id' => 1
        ]);
    }
}
