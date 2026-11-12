<?php

namespace App\Services;

use App\Models\Perkebunan;
use App\Models\Komoditas;
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

    public function exportTemplate(string $tahun = 'ALL'): Spreadsheet
    {
        return $this->export($tahun, true);
    }

    protected function getKomoditasList(): array
    {
        $activeList = Komoditas::getListBySektor('perkebunan');
        if (!empty($activeList)) {
            return $activeList;
        }
        return self::DEFAULT_KOMODITAS;
    }

    public function export(string $tahun = 'ALL', bool $isTemplate = false): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Perkebunan');

        // Banner Judul dan Petunjuk
        $sheet->setCellValue('A1', 'PEMERINTAH KABUPATEN KUNINGAN - DINAS KETAHANAN PANGAN DAN PERTANIAN');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);

        $sheet->setCellValue('A2', 'DATA HISTORIS TAHUNAN PERKEBUNAN KABUPATEN KUNINGAN');
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);

        $sheet->setCellValue('A3', '* Petunjuk: Cukup isi angka pada kolom Luas Tanam, Luas Panen, dan Produksi. Kolom Produktivitas terhitung otomatis.');
        $sheet->mergeCells('A3:G3');
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9)->getColor()->setARGB('FF64748B');

        // Header Kolom
        $headers = ['No', 'Komoditas', 'Tahun', 'Luas Tanam (Ha)', 'Luas Panen (Ha)', 'Produktivitas (Kw/Ha)', 'Produksi (Ton)'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '4', $h);
            $col++;
        }
        $sheet->getStyle('A4:G4')->getFont()->setBold(true);
        $sheet->getStyle('A4:G4')->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A4:G4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2EFDA');

        if ($isTemplate) {
            $rows = collect();
        } else {
            $query = Perkebunan::query();
            if ($tahun !== 'ALL') {
                $query->where('tahun', $tahun);
            }
            $rows = $query->orderBy('komoditas')->orderBy('tahun', 'desc')->get();
        }

        $rowIdx = 5;
        if ($rows->isEmpty()) {
            // Sediakan template data kosong lengkap Komoditas x Tahun dengan rumus otomatis
            $komoditasList = $this->getKomoditasList();
            $defaultYears = ($tahun !== 'ALL' && !empty($tahun))
                ? [(int)$tahun]
                : range(2015, (int)date('Y'));

            $no = 1;
            foreach ($komoditasList as $kmd) {
                foreach ($defaultYears as $yr) {
                    $sheet->setCellValue('A' . $rowIdx, $no++);
                    $sheet->setCellValue('B' . $rowIdx, $kmd);
                    $sheet->setCellValue('C' . $rowIdx, $yr);
                    $sheet->setCellValue('D' . $rowIdx, 0);
                    $sheet->setCellValue('E' . $rowIdx, 0);
                    $sheet->setCellValue('F' . $rowIdx, "=IF(E{$rowIdx}>0, ROUND(G{$rowIdx}/E{$rowIdx}*10, 2), 0)");
                    $sheet->setCellValue('G' . $rowIdx, 0);
                    $rowIdx++;
                }
            }
        } else {
            foreach ($rows as $idx => $item) {
                $sheet->setCellValue('A' . $rowIdx, $idx + 1);
                $sheet->setCellValue('B' . $rowIdx, $item->komoditas);
                $sheet->setCellValue('C' . $rowIdx, $item->tahun);
                $sheet->setCellValue('D' . $rowIdx, (float)$item->luas_tanam);
                $sheet->setCellValue('E' . $rowIdx, (float)$item->luas_panen);
                $sheet->setCellValue('F' . $rowIdx, "=IF(E{$rowIdx}>0, ROUND(G{$rowIdx}/E{$rowIdx}*10, 2), 0)");
                $sheet->setCellValue('G' . $rowIdx, (float)$item->produksi);
                $rowIdx++;
            }
        }

        $lastDataRow = $rowIdx - 1;
        if ($lastDataRow >= 5) {
            $sheet->getStyle("A4:G{$lastDataRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("A5:A{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B5:B{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("C5:C{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D5:G{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("D5:E{$lastDataRow}")->getNumberFormat()->setFormatCode('#,##0.##');
            $sheet->getStyle("F5:F{$lastDataRow}")->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle("G5:G{$lastDataRow}")->getNumberFormat()->setFormatCode('#,##0.##');
        }

        $colWidths = [
            'A' => 6,
            'B' => 22,
            'C' => 12,
            'D' => 18,
            'E' => 18,
            'F' => 22,
            'G' => 18,
        ];
        foreach ($colWidths as $c => $w) {
            $sheet->getColumnDimension($c)->setWidth($w);
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
        $importedKomoditas = [];
        for ($row = 4; $row <= $highestRow; $row++) {
            $komoditas = trim((string)$sheet->getCell('B' . $row)->getValue());
            $thn = trim((string)$sheet->getCell('C' . $row)->getCalculatedValue());
            if (empty($komoditas) || empty($thn) || !is_numeric($thn)) continue;

            if ($targetTahun !== 'ALL' && !empty($targetTahun) && $thn !== $targetTahun) {
                continue;
            }

            $importedKomoditas[] = $komoditas;

            $tanam = (float)$sheet->getCell('D' . $row)->getCalculatedValue();
            $panen = (float)$sheet->getCell('E' . $row)->getCalculatedValue();
            $prodv = (float)$sheet->getCell('F' . $row)->getCalculatedValue();
            $prod = (float)$sheet->getCell('G' . $row)->getCalculatedValue();

            if ($prodv == 0 && $panen > 0 && $prod > 0) {
                $prodv = round(($prod / $panen) * 10, 2);
            }

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

        if (!empty($importedKomoditas)) {
            Komoditas::syncFromImport('perkebunan', array_unique($importedKomoditas), [
                'satuan_luas' => 'Ha',
                'satuan_produksi' => 'Ton',
            ]);
        }

        return ['success' => true, 'totalRows' => $totalImported, 'message' => "Berhasil mengimpor {$totalImported} data Perkebunan Tahunan."];
    }
}
