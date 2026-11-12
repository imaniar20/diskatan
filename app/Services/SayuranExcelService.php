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
     * Ambil konfigurasi sheet berdasarkan Master Komoditas aktif atau default
     */
    protected function getSheetsConfig(): array
    {
        $defaultByKomoditas = collect(self::SHEET_CONFIG)->keyBy(fn($x) => strtolower($x['komoditas']));
        $activeModels = Komoditas::getBySektor('hortikultura');

        if ($activeModels->isEmpty()) {
            return self::SHEET_CONFIG;
        }

        $configs = [];
        foreach ($activeModels as $model) {
            $lower = strtolower(trim($model->nama));
            if ($defaultByKomoditas->has($lower)) {
                $cfg = $defaultByKomoditas->get($lower);
                $configs[strtolower($cfg['sheet_key'])] = $cfg;
            } else {
                $sheetKey = substr($lower, 0, 31);
                $kat = $model->kategori_sayuran ?: 'Sayuran';
                $sLuas = $model->satuan_luas ?: 'Ha';
                $sProd = $model->satuan_produksi ?: 'Kuintal';
                $configs[$sheetKey] = [
                    'sheet_key'       => $sheetKey,
                    'kategori'        => $kat,
                    'komoditas'       => $model->nama,
                    'satuan_luas'     => $sLuas,
                    'satuan_produksi' => $sProd,
                    'label_tanam'     => 'TANAM',
                    'label_panen'     => 'PANEN',
                ];
            }
        }

        return $configs;
    }

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
        $allConfigs = array_merge(self::SHEET_CONFIG, $this->getSheetsConfig());

        foreach ($sheetNames as $rawSheetName) {
            $sheetKey = strtolower(trim($rawSheetName));

            // Cocokkan sheet config
            $cfg = null;
            foreach ($allConfigs as $key => $conf) {
                if ($sheetKey === strtolower($key) || $sheetKey === strtolower($conf['komoditas']) || str_contains($sheetKey, strtolower($key))) {
                    $cfg = $conf;
                    break;
                }
            }

            if (!$cfg && strlen($sheetKey) >= 3 && !in_array($sheetKey, ['sheet1', 'sheet2', 'rekap', 'grafik'])) {
                $cfg = [
                    'sheet_key'       => $sheetKey,
                    'kategori'        => 'Sayuran',
                    'komoditas'       => ucwords(trim($rawSheetName)),
                    'satuan_luas'     => 'Ha',
                    'satuan_produksi' => 'Kuintal',
                    'label_tanam'     => 'TANAM',
                    'label_panen'     => 'PANEN',
                ];
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
     * Generate file Excel Template Kosong (XLSX multi-sheet)
     */
    public function exportTemplate(?string $filterTahun = null): Spreadsheet
    {
        return $this->export($filterTahun, true);
    }

    /**
     * Export seluruh data sayuran ke file Excel (XLSX multi-sheet)
     *
     * @param string|null $filterTahun
     * @param bool $isTemplate
     * @return Spreadsheet
     */
    public function export(?string $filterTahun = null, bool $isTemplate = false): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // Hapus default sheet

        $sheetIdx = 0;
        $sheetsConfig = $this->getSheetsConfig();

        foreach ($sheetsConfig as $key => $cfg) {
            $worksheet = $spreadsheet->createSheet($sheetIdx++);
            $worksheet->setTitle(substr($cfg['sheet_key'], 0, 31));

            // Ambil data dari database (kosongkan jika mode template)
            if ($isTemplate) {
                $items = collect();
            } else {
                $query = Sayuran::where('komoditas', $cfg['komoditas'])
                    ->orderBy('tahun', 'asc');

                if (!empty($filterTahun) && $filterTahun !== 'ALL') {
                    $query->where('tahun', $filterTahun);
                }

                $items = $query->get();
            }

            // Banner Judul dan Petunjuk
            $worksheet->mergeCells('A1:E1');
            $worksheet->setCellValue('A1', "PEMERINTAH KABUPATEN KUNINGAN - DINAS KETAHANAN PANGAN DAN PERTANIAN");
            $worksheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);

            $worksheet->mergeCells('A2:E2');
            $worksheet->setCellValue('A2', "DATA HISTORIS TAHUNAN HORTIKULTURA: " . strtoupper($cfg['komoditas']) . " (" . strtoupper($cfg['kategori']) . ")");
            $worksheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);

            $worksheet->mergeCells('A3:E3');
            $worksheet->setCellValue('A3', "* Petunjuk: Cukup isi angka pada kolom Tanam, Panen, dan Produksi. Kolom Produktivitas terhitung otomatis dengan rumus.");
            $worksheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9)->getColor()->setARGB('FF64748B');

            // Baris 4: Header Kolom
            $worksheet->setCellValue('A4', 'TAHUN');
            $worksheet->setCellValue('B4', $cfg['label_tanam'] . " ({$cfg['satuan_luas']})");
            $worksheet->setCellValue('C4', $cfg['label_panen'] . " ({$cfg['satuan_luas']})");
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

            // Baris 5 dst: Isi data tahunan
            $startRow = 5;
            $currentRow = $startRow;

            // Jika belum ada data di database atau mode template, sediakan baris default (data kosong)
            if ($items->isEmpty()) {
                $defaultYears = (!empty($filterTahun) && $filterTahun !== 'ALL')
                    ? [(int)$filterTahun]
                    : range(2013, (int)date('Y'));
                foreach ($defaultYears as $yr) {
                    $worksheet->setCellValue("A{$currentRow}", $yr);
                    $worksheet->setCellValue("B{$currentRow}", 0);
                    $worksheet->setCellValue("C{$currentRow}", 0);
                    $worksheet->setCellValue("D{$currentRow}", "=IF(C{$currentRow}>0, ROUND(E{$currentRow}/C{$currentRow}*10, 2), 0)");
                    $worksheet->setCellValue("E{$currentRow}", 0);
                    $currentRow++;
                }
            } else {
                foreach ($items as $item) {
                    $worksheet->setCellValue("A{$currentRow}", $item->tahun);
                    $worksheet->setCellValue("B{$currentRow}", (float)$item->luas_tanam);
                    $worksheet->setCellValue("C{$currentRow}", (float)$item->luas_panen);
                    $worksheet->setCellValue("D{$currentRow}", "=IF(C{$currentRow}>0, ROUND(E{$currentRow}/C{$currentRow}*10, 2), 0)");
                    $worksheet->setCellValue("E{$currentRow}", (float)$item->produksi);
                    $currentRow++;
                }
            }

            $lastDataRow = $currentRow - 1;
            $totalRow = $currentRow;

            // Baris Rata-rata
            $worksheet->setCellValue("A{$totalRow}", 'RATA-RATA');
            $worksheet->setCellValue("B{$totalRow}", "=AVERAGE(B{$startRow}:B{$lastDataRow})");
            $worksheet->setCellValue("C{$totalRow}", "=AVERAGE(C{$startRow}:C{$lastDataRow})");
            $worksheet->setCellValue("D{$totalRow}", "=IF(C{$totalRow}>0, ROUND(E{$totalRow}/C{$totalRow}*10, 2), 0)");
            $worksheet->setCellValue("E{$totalRow}", "=AVERAGE(E{$startRow}:E{$lastDataRow})");

            // Styling baris data & summary
            $worksheet->getStyle("A{$startRow}:A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle("B{$startRow}:E{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $worksheet->getStyle("B{$startRow}:C{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.##');
            $worksheet->getStyle("D{$startRow}:D{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            $worksheet->getStyle("E{$startRow}:E{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.##');

            $worksheet->getStyle("A{$totalRow}:E{$totalRow}")->getFont()->setBold(true);
            $worksheet->getStyle("A{$totalRow}:E{$totalRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');

            // Border seluruh tabel
            $tableRange = "A4:E{$totalRow}";
            $worksheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Lebar kolom rapi dan proporsional
            $colWidths = [
                'A' => 14,
                'B' => 22,
                'C' => 22,
                'D' => 24,
                'E' => 22,
            ];
            foreach ($colWidths as $col => $w) {
                $worksheet->getColumnDimension($col)->setWidth($w);
            }
        }

        $spreadsheet->setActiveSheetIndex(0);
        return $spreadsheet;
    }
}
