<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ItemCategory;
use App\Models\Item;
use App\Models\ItemEntry;
use App\Models\ItemExit;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Gudang Utama',
            'password' => Hash::make('password'),
            'role' => 'super-admin',
        ]);

        User::create([
            'name' => 'Gudang 1',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Gudang 2',
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
            'price' => 10000,
            'total_price' => 100000
        ]);

        ItemEntry::create([
            'entry_date' => now(),
            'item_id' => 1,
            'quantity' => 20,
            'description' => 'Andi',
            'user_id' => 1,
            'total_price' => 200000
        ]);

        ItemExit::create([
            'exit_date' => now(),
            'item_id' => 1,
            'quantity' => 10,
            'description' => 'Andi',
            'user_id' => 1,
            'total_price' => 100000
        ]);

        Item::create([
            'code' => 'XYZ456',
            'name' => 'Monitor',
            'category_id' => 2,
            'quantity' => 15,
            'user_id' => 1,
            'price' => 5000,
            'total_price' => 75000
        ]);

        ItemEntry::create([
            'entry_date' => now(),
            'item_id' => 2,
            'quantity' => 20,
            'description' => 'Andi',
            'user_id' => 1,
            'total_price' => 100000
        ]);

        ItemExit::create([
            'exit_date' => now(),
            'item_id' => 2,
            'quantity' => 5,
            'description' => 'Andi',
            'user_id' => 1,
            'total_price' => 25000
        ]);

        Item::create([
            'code' => 'DEF789',
            'name' => 'Keyboard',
            'category_id' => 3,
            'quantity' => 50,
            'user_id' => 1,
            'price' => 3000,
            'total_price' => 150000
        ]);

        ItemEntry::create([
            'entry_date' => now(),
            'item_id' => 3,
            'quantity' => 70,
            'description' => 'Andi',
            'user_id' => 1,
            'total_price' => 210000
        ]);

        ItemExit::create([
            'exit_date' => now(),
            'item_id' => 3,
            'quantity' => 20,
            'description' => 'Andi',
            'user_id' => 1,
            'total_price' => 60000
        ]);

        Item::create([
            'code' => 'GHI101',
            'name' => 'Mouse',
            'category_id' => 1,
            'quantity' => 100,
            'user_id' => 1,
            'price' => 2000,
            'total_price' => 200000
        ]);

        ItemEntry::create([
            'entry_date' => now(),
            'item_id' => 4,
            'quantity' => 120,
            'description' => 'Andi',
            'user_id' => 1,
            'total_price' => 240000
        ]);

        ItemExit::create([
            'exit_date' => now(),
            'item_id' => 4,
            'quantity' => 20,
            'description' => 'Andi',
            'user_id' => 1,
            'total_price' => 40000
        ]);

        Item::create([
            'code' => 'JKL102',
            'name' => 'Printer',
            'category_id' => 2,
            'quantity' => 5,
            'user_id' => 1,
            'price' => 6000,
            'total_price' => 30000
        ]);

        ItemEntry::create([
            'entry_date' => now(),
            'item_id' => 4,
            'quantity' => 10,
            'description' => 'Andi',
            'user_id' => 1,
            'total_price' => 60000
        ]);

        ItemExit::create([
            'exit_date' => now(),
            'item_id' => 4,
            'quantity' => 5,
            'description' => 'Andi',
            'user_id' => 1,
            'total_price' => 30000
        ]);
    }
}
