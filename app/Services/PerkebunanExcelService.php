<?php

namespace App\Services;

use App\Models\Perkebunan;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PerkebunanExcelService
{
    public const DEFAULT_KOMODITAS = [
        'Kopi',
        'Cengkeh',
        'Kelapa',
        'Lada',
        'Tembakau',
        'Aren',
        'Kakao',
        'Karet',
        'Teh',
        'Pala',
    ];

    public function export(string $tahun = 'ALL'): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Perkebunan');

        $sheet->setCellValue('A1', 'DATA HISTORIS TAHUNAN PERKEBUNAN KABUPATEN KUNINGAN');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);

        $headers = ['No', 'Komoditas', 'Tahun', 'Luas Tanam (Ha)', 'Luas Panen (Ha)', 'Produktivitas (Kw/Ha)', 'Produksi (Ton)'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '3', $h);
            $col++;
        }
        $sheet->getStyle('A3:G3')->getFont()->setBold(true);
        $sheet->getStyle('A3:G3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2EFDA');

        $query = Perkebunan::query();
        if ($tahun !== 'ALL') {
            $query->where('tahun', $tahun);
        }
        $rows = $query->orderBy('komoditas')->orderBy('tahun', 'desc')->get();

        $rowIdx = 4;
        foreach ($rows as $idx => $item) {
            $sheet->setCellValue('A' . $rowIdx, $idx + 1);
            $sheet->setCellValue('B' . $rowIdx, $item->komoditas);
            $sheet->setCellValue('C' . $rowIdx, $item->tahun);
            $sheet->setCellValue('D' . $rowIdx, $item->luas_tanam);
            $sheet->setCellValue('E' . $rowIdx, $item->luas_panen);
            $sheet->setCellValue('F' . $rowIdx, $item->produktivitas);
            $sheet->setCellValue('G' . $rowIdx, $item->produksi);
            $rowIdx++;
        }

        foreach (range('A', 'G') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        return $spreadsheet;
    }

    public function import($filePath, string $targetTahun = 'ALL'): array
    {
        $realPath = is_string($filePath) ? $filePath : $filePath->getRealPath();
        $spreadsheet = IOFactory::load($realPath);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        $totalImported = 0;
        for ($row = 4; $row <= $highestRow; $row++) {
            $komoditas = trim((string)$sheet->getCell('B' . $row)->getValue());
            $thn = trim((string)$sheet->getCell('C' . $row)->getValue());
            if (empty($komoditas) || empty($thn)) continue;

            $tanam = (float)$sheet->getCell('D' . $row)->getValue();
            $panen = (float)$sheet->getCell('E' . $row)->getValue();
            $prodv = (float)$sheet->getCell('F' . $row)->getValue();
            $prod = (float)$sheet->getCell('G' . $row)->getValue();

            Perkebunan::updateOrCreate(
                [
                    'komoditas' => $komoditas,
                    'tahun' => $thn,
                ],
                [
                    'luas_tanam' => $tanam,
                    'luas_panen' => $panen,
                    'produktivitas' => $prodv,
                    'produksi' => $prod,
                    'satuan_luas' => 'Ha',
                    'satuan_produksi' => 'Ton',
                ]
            );
            $totalImported++;
        }

        return ['success' => true, 'totalRows' => $totalImported, 'message' => "Berhasil mengimpor {$totalImported} data Perkebunan Tahunan."];
    }
}
