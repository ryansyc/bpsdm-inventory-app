<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ItemCategory;
use App\Models\Item;
use App\Models\ItemEntry;
use App\Models\ItemExit;
use App\Models\Department;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [['department_id' => 1, 'name' => 'Gudang BPSDM', 'password' => Hash::make('password')]];

        $departments = [
            ['name' => 'Gudang BPSDM'],
            ['name' => 'Bidang PKM'],
            ['name' => 'Bidang PKTI'],
            ['name' => 'Bidang Sekretariat'],
            ['name' => 'Bidang SPAK'],
            ['name' => 'Cleaning Service'],
            ['name' => 'Command Center'],
            ['name' => 'Gedung Aula Utama'],
            ['name' => 'Gedung Kelas'],
            ['name' => 'Gedung Twin Tower'],
            ['name' => 'Perpustakaan']
        ];

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

        $items = [
            [
                'code' => 'ITEM001',
                'name' => 'Laptop',
                // 'quantity' => 10,
                'unit' => 'pcs',
                'unit_quantity' => 10,
                'unit_price' => 1000,
                'total_price' => 10000
            ],
            [
                'code' => 'ITEM002',
                'name' => 'Printer',
                // 'quantity' => 5,
                'unit' => 'pcs',
                'unit_quantity' => 5,
                'unit_price' => 500,
                'total_price' => 2500
            ],
            [
                'code' => 'ITEM003',
                'name' => 'Mouse',
                // 'quantity' => 20,
                'unit' => 'pcs',
                'unit_quantity' => 20,
                'unit_price' => 50,
                'total_price' => 1000
            ],
            [
                'code' => 'ITEM004',
                'name' => 'Keyboard',
                // 'quantity' => 15,
                'unit' => 'pcs',
                'unit_quantity' => 15,
                'unit_price' => 80,
                'total_price' => 1200
            ],
            [
                'code' => 'ITEM005',
                'name' => 'Monitor',
                // 'quantity' => 8,
                'unit' => 'pcs',
                'unit_quantity' => 8,
                'unit_price' => 300,
                'total_price' => 2400
            ],
        ];

        // $itemEntries = [
        //     [
        //         'category_id' => rand(1, 24),
        //         'item_id' => rand(1, 5),
        //         'date' => Carbon::now(),
        //         'unit' => 'pcs',
        //         'unit_quantity' => 10,
        //         'total_price' => 10000
        //     ],
        //     [
        //         'category_id' => rand(1, 24),
        //         'item_id' => rand(1, 5),
        //         'date' => Carbon::now(),
        //         'unit' => 'pcs',
        //         'unit_quantity' => 5,
        //         'total_price' => 2500
        //     ],
        //     [
        //         'category_id' => rand(1, 24),
        //         'item_id' => rand(1, 5),
        //         'date' => Carbon::now(),
        //         'unit' => 'pcs',
        //         'unit_quantity' => 20,
        //         'total_price' => 1000
        //     ],
        //     [
        //         'category_id' => rand(1, 24),
        //         'item_id' => rand(1, 5),
        //         'date' => Carbon::now(),
        //         'unit' => 'pcs',
        //         'unit_quantity' => 15,
        //         'total_price' => 1200
        //     ],
        //     [
        //         'category_id' => rand(1, 24),
        //         'item_id' => rand(1, 5),
        //         'date' => Carbon::now(),
        //         'unit' => 'pcs',
        //         'unit_quantity' => 8,
        //         'total_price' => 2400
        //     ],
        // ];

        // $itemExits = [
        //     [
        //         'department_id' => rand(1, 11),
        //         'category_id' => rand(1, 24),
        //         'item_id' => rand(1, 5),
        //         'date' => Carbon::now(),
        //         'unit' => 'pcs',
        //         'unit_quantity' => 2,
        //         'total_price' => 2000,
        //         'provider' => 'Warehouse A',
        //         'receiver' => 'John Doe'
        //     ],
        //     [
        //         'department_id' => rand(1, 11),
        //         'category_id' => rand(1, 24),
        //         'item_id' => rand(1, 5),
        //         'date' => Carbon::now(),
        //         'unit' => 'pcs',
        //         'unit_quantity' => 1,
        //         'total_price' => 1000,
        //         'provider' => 'Warehouse B',
        //         'receiver' => 'Jane Smith'
        //     ],
        //     [
        //         'department_id' => rand(1, 11),
        //         'category_id' => rand(1, 24),
        //         'item_id' => rand(1, 5),
        //         'date' => Carbon::now(),
        //         'unit' => 'pcs',
        //         'unit_quantity' => 3,
        //         'total_price' => 150,
        //         'provider' => 'Warehouse C',
        //         'receiver' => 'Mike Johnson'
        //     ],
        //     [
        //         'department_id' => rand(1, 11),
        //         'category_id' => rand(1, 24),
        //         'item_id' => rand(1, 5),
        //         'date' => Carbon::now(),
        //         'unit' => 'pcs',
        //         'unit_quantity' => 4,
        //         'total_price' => 320,
        //         'provider' => 'Warehouse D',
        //         'receiver' => 'Emma Brown'
        //     ],
        //     [
        //         'department_id' => rand(1, 11),
        //         'category_id' => rand(1, 24),
        //         'item_id' => rand(1, 5),
        //         'date' => Carbon::now(),
        //         'unit' => 'pcs',
        //         'unit_quantity' => 1,
        //         'total_price' => 300,
        //         'provider' => 'Warehouse E',
        //         'receiver' => 'Liam Wilson'
        //     ],
        // ];

        foreach ($departments as $department) {
            Department::create($department);
        }

        foreach ($users as $user) {
            User::create($user);
        }

        foreach ($categories as $category) {
            Category::create($category);
        }

        foreach ($items as $item) {
            Item::create($item);
        }

        // foreach ($itemEntries as $itemEntry) {
        //     ItemEntry::create($itemEntry);
        // }

        // foreach ($itemExits as $itemExit) {
        //     ItemExit::create($itemExit);
        // }
    }
}
