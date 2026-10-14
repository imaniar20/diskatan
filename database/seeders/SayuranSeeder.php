<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\SayuranExcelService;

class SayuranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = 'C:/Users/Admin/Documents/diskatan/sayuran/grafik SAYURAN 13 TAHUN 2025.xlsx';

        if (!file_exists($filePath)) {
            $this->command->error("File tidak ditemukan di: {$filePath}");
            return;
        }

        $service = new SayuranExcelService();
        $result = $service->import($filePath, 'ALL');

        if ($result['success']) {
            $this->command->info("Seeder Sayuran Sukses: {$result['message']} (Total: {$result['totalRows']} baris data).");
        } else {
            $this->command->error("Seeder Sayuran Gagal: {$result['message']}");
        }
    }
}
