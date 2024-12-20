<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            ['name' => 'Sekretariat'],
            ['name' => 'Bidang SKPK'],
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

        foreach ($departments as $department) {
            Department::create($department);
        }

        foreach ($users as $user) {
            User::create($user);
        }

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
