<?php

namespace App\Services;

use App\Models\PerkebunanUtama;
use App\Models\Komoditas;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PerkebunanUtamaExcelService
{
    public const DEFAULT_SHEETS = [
        'Kopi'    => ['label' => 'Kopi'],
        'Cengkeh' => ['label' => 'Cengkeh'],
        'Kelapa'  => ['label' => 'Kelapa'],
        'Lada'    => ['label' => 'Lada'],
    ];

    public function export(string $tahun): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        foreach (self::DEFAULT_SHEETS as $komoditas => $cfg) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle(substr($komoditas, 0, 31));

            // Header info
            $sheet->setCellValue('A1', 'DATA UTAMA PERKEBUNAN - ' . strtoupper($komoditas));
            $sheet->setCellValue('A2', 'KABUPATEN KUNINGAN - TAHUN ' . $tahun);
            $sheet->mergeCells('A1:X1');
            $sheet->mergeCells('A2:X2');
            $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(12);

            // Table headers
            $headers = [
                'No', 'Kecamatan', 'Kategori', 'Satuan', 'Luas Sawah/Lahan (Ha)',
                'Target MH', 'Target MK', 'Total Target',
                'Okt', 'Nop', 'Des', 'Jan', 'Feb', 'Mar', 'Jumlah MH',
                'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Jumlah MK',
                'Total Realisasi', 'Capaian (%)'
            ];

            $col = 'A';
            foreach ($headers as $h) {
                $sheet->setCellValue($col . '4', $h);
                $col++;
            }

            $sheet->getStyle('A4:X4')->getFont()->setBold(true);
            $sheet->getStyle('A4:X4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2EFDA');

            $rows = PerkebunanUtama::where('komoditas', $komoditas)
                ->where('tahun', $tahun)
                ->orderBy('kategori')
                ->orderBy('no_urut')
                ->get();

            $rowIdx = 5;
            foreach ($rows as $item) {
                $sheet->setCellValue('A' . $rowIdx, $item->no_urut);
                $sheet->setCellValue('B' . $rowIdx, $item->kecamatan);
                $sheet->setCellValue('C' . $rowIdx, $item->kategori);
                $sheet->setCellValue('D' . $rowIdx, $item->satuan);
                $sheet->setCellValue('E' . $rowIdx, $item->luas_sawah);
                $sheet->setCellValue('F' . $rowIdx, $item->target_okmar);
                $sheet->setCellValue('G' . $rowIdx, $item->target_asep);
                $sheet->setCellValue('H' . $rowIdx, $item->target_total);
                $sheet->setCellValue('I' . $rowIdx, $item->realisasi_okt);
                $sheet->setCellValue('J' . $rowIdx, $item->realisasi_nop);
                $sheet->setCellValue('K' . $rowIdx, $item->realisasi_des);
                $sheet->setCellValue('L' . $rowIdx, $item->realisasi_jan);
                $sheet->setCellValue('M' . $rowIdx, $item->realisasi_feb);
                $sheet->setCellValue('N' . $rowIdx, $item->realisasi_mar);
                $sheet->setCellValue('O' . $rowIdx, $item->jumlah_mh);
                $sheet->setCellValue('P' . $rowIdx, $item->realisasi_apr);
                $sheet->setCellValue('Q' . $rowIdx, $item->realisasi_mei);
                $sheet->setCellValue('R' . $rowIdx, $item->realisasi_jun);
                $sheet->setCellValue('S' . $rowIdx, $item->realisasi_jul);
                $sheet->setCellValue('T' . $rowIdx, $item->realisasi_ags);
                $sheet->setCellValue('U' . $rowIdx, $item->realisasi_sep);
                $sheet->setCellValue('V' . $rowIdx, $item->jumlah_mk);
                $sheet->setCellValue('W' . $rowIdx, $item->total_realisasi);
                $sheet->setCellValue('X' . $rowIdx, $item->persentase_capaian);
                $rowIdx++;
            }

            foreach (range('A', 'X') as $c) {
                $sheet->getColumnDimension($c)->setAutoSize(true);
            }
        }

        $spreadsheet->setActiveSheetIndex(0);
        return $spreadsheet;
    }

    public function import($filePath, string $targetTahun, string $targetKomoditas = null): array
    {
        $realPath = is_string($filePath) ? $filePath : $filePath->getRealPath();
        $spreadsheet = IOFactory::load($realPath);
        $sheetCount = $spreadsheet->getSheetCount();
        $totalImported = 0;
        $importedKomoditas = [];

        for ($i = 0; $i < $sheetCount; $i++) {
            $sheet = $spreadsheet->getSheet($i);
            $sheetTitle = trim($sheet->getTitle());
            $komoditas = $targetKomoditas ?: $sheetTitle;
            if (!empty($komoditas)) {
                $importedKomoditas[] = $komoditas;
            }

            $highestRow = $sheet->getHighestRow();
            if ($highestRow < 5) continue;

            for ($row = 5; $row <= $highestRow; $row++) {
                $kecamatan = trim((string)$sheet->getCell('B' . $row)->getValue());
                if (empty($kecamatan)) continue;

                $kategori = strtolower(trim((string)$sheet->getCell('C' . $row)->getValue())) ?: 'tanam';
                $satuan = trim((string)$sheet->getCell('D' . $row)->getValue()) ?: 'Ha';
                $noUrut = (int)$sheet->getCell('A' . $row)->getValue() ?: ($row - 4);
                $luasSawah = (float)$sheet->getCell('E' . $row)->getValue();

                $targetOkmar = (float)$sheet->getCell('F' . $row)->getValue();
                $targetAsep = (float)$sheet->getCell('G' . $row)->getValue();
                $targetTotal = (float)$sheet->getCell('H' . $row)->getValue() ?: ($targetOkmar + $targetAsep);

                $rOkt = (float)$sheet->getCell('I' . $row)->getValue();
                $rNop = (float)$sheet->getCell('J' . $row)->getValue();
                $rDes = (float)$sheet->getCell('K' . $row)->getValue();
                $rJan = (float)$sheet->getCell('L' . $row)->getValue();
                $rFeb = (float)$sheet->getCell('M' . $row)->getValue();
                $rMar = (float)$sheet->getCell('N' . $row)->getValue();
                $sumMh = (float)$sheet->getCell('O' . $row)->getValue() ?: ($rOkt + $rNop + $rDes + $rJan + $rFeb + $rMar);

                $rApr = (float)$sheet->getCell('P' . $row)->getValue();
                $rMei = (float)$sheet->getCell('Q' . $row)->getValue();
                $rJun = (float)$sheet->getCell('R' . $row)->getValue();
                $rJul = (float)$sheet->getCell('S' . $row)->getValue();
                $rAgs = (float)$sheet->getCell('T' . $row)->getValue();
                $rSep = (float)$sheet->getCell('U' . $row)->getValue();
                $sumMk = (float)$sheet->getCell('V' . $row)->getValue() ?: ($rApr + $rMei + $rJun + $rJul + $rAgs + $rSep);

                $totalReal = (float)$sheet->getCell('W' . $row)->getValue() ?: ($sumMh + $sumMk);
                $persenTot = (float)$sheet->getCell('X' . $row)->getValue() ?: ($targetTotal > 0 ? round(($totalReal / $targetTotal) * 100, 2) : 0);
                $persenSub = $targetOkmar > 0 ? round(($sumMh / $targetOkmar) * 100, 2) : 0;

                PerkebunanUtama::updateOrCreate(
                    [
                        'komoditas' => $komoditas,
                        'kategori' => $kategori,
                        'tahun' => $targetTahun,
                        'kecamatan' => $kecamatan,
                    ],
                    [
                        'satuan' => $satuan,
                        'no_urut' => $noUrut,
                        'luas_sawah' => $luasSawah,
                        'target_okmar' => $targetOkmar,
                        'target_asep' => $targetAsep,
                        'target_total' => $targetTotal,
                        'realisasi_okt' => $rOkt,
                        'realisasi_nop' => $rNop,
                        'realisasi_des' => $rDes,
                        'realisasi_jan' => $rJan,
                        'realisasi_feb' => $rFeb,
                        'realisasi_mar' => $rMar,
                        'jumlah_mh' => $sumMh,
                        'realisasi_apr' => $rApr,
                        'realisasi_mei' => $rMei,
                        'realisasi_jun' => $rJun,
                        'realisasi_jul' => $rJul,
                        'realisasi_ags' => $rAgs,
                        'realisasi_sep' => $rSep,
                        'jumlah_mk' => $sumMk,
                        'total_realisasi' => $totalReal,
                        'persentase_sub' => $persenSub,
                        'persentase_capaian' => $persenTot,
                    ]
                );
                $totalImported++;
            }
        }

        if (!empty($importedKomoditas)) {
            Komoditas::syncFromImport('perkebunan', array_unique($importedKomoditas), [
                'satuan_luas' => 'Ha',
                'satuan_produksi' => 'Ton',
            ]);
        }

        return ['success' => true, 'totalRows' => $totalImported, 'message' => "Berhasil mengimpor {$totalImported} data Perkebunan Utama."];
    }
}
