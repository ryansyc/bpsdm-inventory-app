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

        // Insert data ke tabel item_categories
        $categories = [
            ['code' => '5.1.02.01.01', 'name' => 'Belanja Barang Pakai Habis'],
            ['code' => '5.1.02.01.0001', 'name' => 'Belanja Bahan-Bahan Bangunan dan Konstruksi'],
            ['code' => '5.1.02.01.0003', 'name' => 'Belanja Bahan-Bahan Kimia'],
            ['code' => '5.1.02.01.0004', 'name' => 'Belanja Bahan-Bahan Bakar dan Pelumas'],
            ['code' => '5.1.02.01.0008', 'name' => 'Belanja Bahan-Bahan/Bibit Tanaman'],
            ['code' => '5.1.02.01.0009', 'name' => 'Belanja Bahan-Isi Tabung Pemadam Kebakaran'],
            ['code' => '5.1.02.01.0013', 'name' => 'Belanja Bahan-Bahan Lainnya'],
            ['code' => '5.1.02.01.0015', 'name' => 'Belanja Suku Cadang-Suku Cadang Alat Angkutan'],
            ['code' => '5.1.02.01.0028', 'name' => 'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor'],
            ['code' => '5.1.02.01.0030', 'name' => 'Belanja Alat/Bahan untuk Kegiatan Kantor-Kertas dan Cover'],
            ['code' => '5.1.02.01.0031', 'name' => 'Belanja Alat/Bahan untuk Kegiatan Kantor-Bahan Cetak'],
            ['code' => '5.1.02.01.0032', 'name' => 'Belanja Alat/Bahan untuk Kegiatan Kantor-Benda Pos'],
            ['code' => '5.1.02.01.0033', 'name' => 'Belanja Alat/Bahan untuk Kegiatan Kantor-Komputer'],
            ['code' => '5.1.02.01.0034', 'name' => 'Belanja Alat/Bahan untuk Kegiatan Kantor-Perabot Kantor'],
            ['code' => '5.1.02.01.0035', 'name' => 'Belanja Alat/Bahan untuk Kegiatan Kantor-Perlengkapan Pendukung Olahraga'],
            ['code' => '5.1.02.01.0036', 'name' => 'Belanja Alat/Bahan untuk Kegiatan Kantor-Souvenir/Cendera Mata'],
            ['code' => '5.1.02.01.0037', 'name' => 'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat/Bahan untuk Kegiatan Kantor Lainnya'],
            ['code' => '5.1.02.01.0038', 'name' => 'Belanja Obat-Obatan-Obat'],
            ['code' => '5.1.02.01.0043', 'name' => 'Belanja Obat-Obatan-Obat-Obatan Lainnya'],
            ['code' => '5.1.02.01.0052', 'name' => 'Belanja Natura dan Pakan-Natura'],
            ['code' => '5.1.02.01.0055', 'name' => 'Belanja Makanan dan Minuman Rapat'],
            ['code' => '5.1.02.01.0057', 'name' => 'Belanja Makanan dan Minuman Jamuan Tamu'],
            ['code' => '5.1.02.01.0064', 'name' => 'Belanja Makanan dan Minuman pada Fasilitas Pelayanan Urusan Pendidikan'],
            ['code' => '5.1.02.01.0064', 'name' => 'Belanja Pakaian Dinas Lapangan (PDL)']
        ];

        foreach ($categories as $category) {
            ItemCategory::create($category);
        }

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
            'department' => 'Gudang 1',
            'receiver' => 'Andi',
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
            'department' => 'Gudang 1',
            'receiver' => 'Andi',
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
            'department' => 'Gudang 1',
            'receiver' => 'Andi',
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
            'department' => 'Gudang 1',
            'receiver' => 'Andi',
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
            'department' => 'Gudang 1',
            'receiver' => 'Andi',
            'user_id' => 1,
            'total_price' => 30000
        ]);
    }
}
