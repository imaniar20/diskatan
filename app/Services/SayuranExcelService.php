<?php

namespace App\Services;

use App\Models\Sayuran;
use App\Models\Komoditas;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SayuranExcelService
{
    /**
     * Konfigurasi Sheet Komoditas Sayuran, Buah, dan Biofarmaka
     */
    public const SHEET_CONFIG = [
        'bawang merah' => [
            'sheet_key'       => 'bawang merah',
            'kategori'        => 'Sayuran',
            'komoditas'       => 'Bawang Merah',
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Kuintal',
            'label_tanam'     => 'TANAM',
            'label_panen'     => 'PANEN',
        ],
        'bawang daun' => [
            'sheet_key'       => 'Bawang daun',
            'kategori'        => 'Sayuran',
            'komoditas'       => 'Bawang Daun',
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Kuintal',
            'label_tanam'     => 'TANAM',
            'label_panen'     => 'PANEN',
        ],
        'cabe besar' => [
            'sheet_key'       => 'cabe besar',
            'kategori'        => 'Sayuran',
            'komoditas'       => 'Cabe Besar',
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Kuintal',
            'label_tanam'     => 'TANAM',
            'label_panen'     => 'PANEN',
        ],
        'cabe rawit' => [
            'sheet_key'       => 'cabe rawit',
            'kategori'        => 'Sayuran',
            'komoditas'       => 'Cabe Rawit',
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Kuintal',
            'label_tanam'     => 'TANAM',
            'label_panen'     => 'PANEN',
        ],
        'tomat' => [
            'sheet_key'       => 'tomat',
            'kategori'        => 'Sayuran',
            'komoditas'       => 'Tomat',
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Kuintal',
            'label_tanam'     => 'TANAM',
            'label_panen'     => 'PANEN',
        ],
        'wortel' => [
            'sheet_key'       => 'wortel',
            'kategori'        => 'Sayuran',
            'komoditas'       => 'Wortel',
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Kuintal',
            'label_tanam'     => 'TANAM',
            'label_panen'     => 'PANEN',
        ],
        'kubis' => [
            'sheet_key'       => 'kubis',
            'kategori'        => 'Sayuran',
            'komoditas'       => 'Kubis',
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Kuintal',
            'label_tanam'     => 'TANAM',
            'label_panen'     => 'PANEN',
        ],
        'petsai' => [
            'sheet_key'       => 'PETSAI',
            'kategori'        => 'Sayuran',
            'komoditas'       => 'Petsai / Sawi',
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Kuintal',
            'label_tanam'     => 'TANAM',
            'label_panen'     => 'PANEN',
        ],
        'rambutan' => [
            'sheet_key'       => 'rambutan',
            'kategori'        => 'Buah-buahan',
            'komoditas'       => 'Rambutan',
            'satuan_luas'     => 'Pohon',
            'satuan_produksi' => 'Kuintal',
            'label_tanam'     => 'TANAMAN AKHIR (PHN)',
            'label_panen'     => 'TANAMAN MENGHASILKAN (PHN)',
        ],
        'mangga' => [
            'sheet_key'       => 'Mangga',
            'kategori'        => 'Buah-buahan',
            'komoditas'       => 'Mangga',
            'satuan_luas'     => 'Pohon',
            'satuan_produksi' => 'Kuintal',
            'label_tanam'     => 'TANAMAN AKHIR (PHN)',
            'label_panen'     => 'TANAMAN MENGHASILKAN (PHN)',
        ],
        'durian' => [
            'sheet_key'       => 'DURIAN',
            'kategori'        => 'Buah-buahan',
            'komoditas'       => 'Durian',
            'satuan_luas'     => 'Pohon',
            'satuan_produksi' => 'Kuintal',
            'label_tanam'     => 'TANAMAN AKHIR (PHN)',
            'label_panen'     => 'TANAMAN MENGHASILKAN (PHN)',
        ],
        'jahe' => [
            'sheet_key'       => 'jahe',
            'kategori'        => 'Biofarmaka',
            'komoditas'       => 'Jahe',
            'satuan_luas'     => 'M²',
            'satuan_produksi' => 'Kg',
            'label_tanam'     => 'TANAM (M2)',
            'label_panen'     => 'PANEN (M2)',
        ],
        'kapulaga' => [
            'sheet_key'       => 'KAPULAGA',
            'kategori'        => 'Biofarmaka',
            'komoditas'       => 'Kapulaga',
            'satuan_luas'     => 'M²',
            'satuan_produksi' => 'Kg',
            'label_tanam'     => 'TANAM (M2)',
            'label_panen'     => 'PANEN (M2)',
        ],
    ];

    /**
     * Import data dari file Excel (.xlsx / .xls)
     *
     * @param UploadedFile|string $fileOrPath
     * @param string|null $targetTahun Jika null atau 'ALL', import seluruh tahun dari file
     * @return array
     */
    public function import($fileOrPath, ?string $targetTahun = null): array
    {
        $filePath = ($fileOrPath instanceof UploadedFile) ? $fileOrPath->getRealPath() : $fileOrPath;
        $spreadsheet = IOFactory::load($filePath);
        $sheetNames = $spreadsheet->getSheetNames();

        $recordsToInsert = [];
        $processedSheets = [];

        foreach ($sheetNames as $rawSheetName) {
            $sheetKey = strtolower(trim($rawSheetName));

            // Cocokkan sheet config
            $cfg = null;
            foreach (self::SHEET_CONFIG as $key => $conf) {
                if ($sheetKey === $key || str_contains($sheetKey, $key)) {
                    $cfg = $conf;
                    break;
                }
            }

            if (!$cfg) {
                continue;
            }

            $worksheet = $spreadsheet->getSheetByName($rawSheetName);
            $highestRow = min($worksheet->getHighestDataRow(), 40);

            // Baris data biasanya mulai baris 5 atau 6
            for ($r = 5; $r <= $highestRow; $r++) {
                $rawTahun = $worksheet->getCell('A' . $r)->getCalculatedValue();

                // Validasi tahun
                if (!is_numeric($rawTahun) || (int)$rawTahun < 2000 || (int)$rawTahun > 2060) {
                    continue;
                }

                $tahun = (string) (int)$rawTahun;

                // Jika user menentukan target tahun spesifik dan berbeda dengan baris ini, lewati
                if (!empty($targetTahun) && $targetTahun !== 'ALL' && $tahun !== $targetTahun) {
                    continue;
                }

                $tanam = (float) $worksheet->getCell('B' . $r)->getCalculatedValue();
                $panen = (float) $worksheet->getCell('C' . $r)->getCalculatedValue();
                $prodv = (float) $worksheet->getCell('D' . $r)->getCalculatedValue();
                $prod  = (float) $worksheet->getCell('E' . $r)->getCalculatedValue();

                // Hitung produktivitas otomatis jika 0
                if ($prodv == 0 && $panen > 0 && $prod > 0) {
                    $prodv = round(($prod / $panen) * 10, 2);
                }

                $recordsToInsert[] = [
                    'kategori'        => $cfg['kategori'],
                    'komoditas'       => $cfg['komoditas'],
                    'tahun'           => $tahun,
                    'luas_tanam'      => $tanam,
                    'luas_panen'      => $panen,
                    'produktivitas'   => $prodv,
                    'produksi'        => $prod,
                    'satuan_luas'     => $cfg['satuan_luas'],
                    'satuan_produksi' => $cfg['satuan_produksi'],
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }

            if (!in_array($cfg['komoditas'], $processedSheets)) {
                $processedSheets[] = $cfg['komoditas'];
            }
        }

        if (empty($recordsToInsert)) {
            return [
                'success'   => false,
                'message'   => 'Tidak ada data valid yang dapat diimpor dari file Excel.',
                'totalRows' => 0,
                'sheets'    => [],
            ];
        }

        // Jalankan transaksi database (replace data yang sesuai)
        DB::transaction(function () use ($targetTahun, $processedSheets, $recordsToInsert) {
            $query = Sayuran::whereIn('komoditas', $processedSheets);

            if (!empty($targetTahun) && $targetTahun !== 'ALL') {
                $query->where('tahun', $targetTahun);
            }

            $query->delete();

            foreach (array_chunk($recordsToInsert, 100) as $chunk) {
                Sayuran::insert($chunk);
            }

            // Otomatis daftarkan seluruh komoditas yang diimpor ke Master Komoditas
            Komoditas::syncFromImport('hortikultura', $processedSheets, [
                'satuan_luas' => 'Ha',
                'satuan_produksi' => 'Kuintal',
            ]);
        });

        $tahunMsg = (!empty($targetTahun) && $targetTahun !== 'ALL') ? "Tahun {$targetTahun}" : "Semua Tahun";

        return [
            'success'   => true,
            'message'   => "Berhasil memperbarui data Sayuran & Hortikultura ({$tahunMsg}) untuk " . count($processedSheets) . " komoditas.",
            'totalRows' => count($recordsToInsert),
            'sheets'    => $processedSheets,
        ];
    }

    /**
     * Export seluruh data sayuran ke file Excel (XLSX multi-sheet)
     *
     * @param string|null $filterTahun
     * @return Spreadsheet
     */
    public function export(?string $filterTahun = null): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // Hapus default sheet

        $sheetIdx = 0;
        foreach (self::SHEET_CONFIG as $key => $cfg) {
            $worksheet = $spreadsheet->createSheet($sheetIdx++);
            $worksheet->setTitle(substr($cfg['sheet_key'], 0, 31));

            // Ambil data dari database
            $query = Sayuran::where('komoditas', $cfg['komoditas'])
                ->orderBy('tahun', 'asc');

            if (!empty($filterTahun) && $filterTahun !== 'ALL') {
                $query->where('tahun', $filterTahun);
            }

            $items = $query->get();

            // Judul Header
            $worksheet->mergeCells('A1:E1');
            $worksheet->setCellValue('A1', "DATA REALISASI TANAM, PANEN, PRODUKTIVITAS DAN PRODUKSI");
            $worksheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
            $worksheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $worksheet->mergeCells('A2:E2');
            $worksheet->setCellValue('A2', "TANAMAN " . strtoupper($cfg['komoditas']) . " ({$cfg['kategori']})");
            $worksheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
            $worksheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Baris 4: Header Kolom
            $worksheet->setCellValue('A4', 'TAHUN');
            $worksheet->setCellValue('B4', $cfg['label_tanam'] . " ({$cfg['satuan_luas']})");
            $worksheet->setCellValue('C4', $cfg['label_panen'] . " ({$cfg['satuan_luas']})");
            $worksheet->setCellValue('D4', 'PRODUKTIVITAS');
            $worksheet->setCellValue('E4', "PRODUKSI ({$cfg['satuan_produksi']})");

            $headerRange = 'A4:E4';
            $worksheet->getStyle($headerRange)->getFont()->setBold(true);
            $worksheet->getStyle($headerRange)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);
            $worksheet->getStyle($headerRange)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE8F5E9');

            // Baris 6 dst: Isi data tahunan
            $startRow = 6;
            $currentRow = $startRow;

            // Jika belum ada data di database untuk komoditas ini, sediakan baris default
            if ($items->isEmpty()) {
                $defaultYears = range(2013, (int)date('Y'));
                foreach ($defaultYears as $yr) {
                    $worksheet->setCellValue("A{$currentRow}", $yr);
                    $worksheet->setCellValue("B{$currentRow}", 0);
                    $worksheet->setCellValue("C{$currentRow}", 0);
                    $worksheet->setCellValue("D{$currentRow}", 0);
                    $worksheet->setCellValue("E{$currentRow}", 0);
                    $currentRow++;
                }
            } else {
                foreach ($items as $item) {
                    $worksheet->setCellValue("A{$currentRow}", $item->tahun);
                    $worksheet->setCellValue("B{$currentRow}", (float)$item->luas_tanam);
                    $worksheet->setCellValue("C{$currentRow}", (float)$item->luas_panen);
                    $worksheet->setCellValue("D{$currentRow}", (float)$item->produktivitas);
                    $worksheet->setCellValue("E{$currentRow}", (float)$item->produksi);
                    $currentRow++;
                }
            }

            $lastDataRow = $currentRow - 1;

            // Styling baris data
            $worksheet->getStyle("A{$startRow}:A{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle("B{$startRow}:E{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("B{$startRow}:E{$lastDataRow}")->getNumberFormat()->setFormatCode('#,##0.##');

            // Border seluruh tabel
            $tableRange = "A4:E{$lastDataRow}";
            $worksheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            foreach (range('A', 'E') as $col) {
                $worksheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        $spreadsheet->setActiveSheetIndex(0);
        return $spreadsheet;
    }
}
