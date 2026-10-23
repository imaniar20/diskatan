<?php

namespace App\Services;

use App\Models\TanamanPangan;
use App\Models\Komoditas;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TanamanPanganExcelService
{
    /**
     * Konfigurasi Sheet Komoditas Tanaman Pangan
     */
    public const SHEET_CONFIG = [
        'padi' => [
            'sheet_key'       => 'padi',
            'komoditas'       => 'Padi',
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Ton',
        ],
        'jagung' => [
            'sheet_key'       => 'jagung',
            'komoditas'       => 'Jagung',
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Ton',
        ],
        'kedele' => [
            'sheet_key'       => 'kedele',
            'komoditas'       => 'Kedelai',
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Ton',
        ],
        'k tanah' => [
            'sheet_key'       => 'k tanah',
            'komoditas'       => 'Kacang Tanah',
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Ton',
        ],
        'k hijau' => [
            'sheet_key'       => 'k hijau',
            'komoditas'       => 'Kacang Hijau',
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Ton',
        ],
        'ubi kayu' => [
            'sheet_key'       => 'ubi kayu',
            'komoditas'       => 'Ubi Kayu',
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Ton',
        ],
        'ubi jalar' => [
            'sheet_key'       => 'ubi jalar',
            'komoditas'       => 'Ubi Jalar',
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Ton',
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

            // Cari baris data tahun
            for ($r = 4; $r <= $highestRow; $r++) {
                $rawTahun = $worksheet->getCell('A' . $r)->getCalculatedValue();

                // Validasi tahun numerik 4 digit
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
                'message'   => 'Tidak ada data valid yang dapat diimpor dari file Excel Tanaman Pangan.',
                'totalRows' => 0,
                'sheets'    => [],
            ];
        }

        // Jalankan transaksi database
        DB::transaction(function () use ($targetTahun, $processedSheets, $recordsToInsert) {
            $query = TanamanPangan::whereIn('komoditas', $processedSheets);

            if (!empty($targetTahun) && $targetTahun !== 'ALL') {
                $query->where('tahun', $targetTahun);
            }

            $query->delete();

            foreach (array_chunk($recordsToInsert, 100) as $chunk) {
                TanamanPangan::insert($chunk);
            }

            // Otomatis daftarkan seluruh komoditas yang diimpor ke Master Komoditas
            Komoditas::syncFromImport('tanaman_pangan', $processedSheets, [
                'satuan_luas' => 'Ha',
                'satuan_produksi' => 'Ton',
            ]);
        });

        $tahunMsg = (!empty($targetTahun) && $targetTahun !== 'ALL') ? "Tahun {$targetTahun}" : "Semua Tahun";

        return [
            'success'   => true,
            'message'   => "Berhasil memperbarui data Tanaman Pangan ({$tahunMsg}) untuk " . count($processedSheets) . " komoditas.",
            'totalRows' => count($recordsToInsert),
            'sheets'    => $processedSheets,
        ];
    }

    /**
     * Export seluruh data tanaman pangan ke file Excel (XLSX multi-sheet)
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
            $query = TanamanPangan::where('komoditas', $cfg['komoditas'])
                ->orderBy('tahun', 'asc');

            if (!empty($filterTahun) && $filterTahun !== 'ALL') {
                $query->where('tahun', $filterTahun);
            }

            $items = $query->get();

            // Judul Header
            $worksheet->mergeCells('A1:E1');
            $worksheet->setCellValue('A1', "DATA REALISASI TANAM, PANEN, PRODUKTIVITAS DAN PRODUKSI TANAMAN PANGAN");
            $worksheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
            $worksheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $worksheet->mergeCells('A2:E2');
            $worksheet->setCellValue('A2', "TANAMAN " . strtoupper($cfg['komoditas']) . " KABUPATEN KUNINGAN");
            $worksheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
            $worksheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Baris 4: Header Kolom
            $worksheet->setCellValue('A4', 'TAHUN');
            $worksheet->setCellValue('B4', "TANAM ({$cfg['satuan_luas']})");
            $worksheet->setCellValue('C4', "PANEN ({$cfg['satuan_luas']})");
            $worksheet->setCellValue('D4', 'PRODUKTIVITAS (Kw/Ha)');
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
