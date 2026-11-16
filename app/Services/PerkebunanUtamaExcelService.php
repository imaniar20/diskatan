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

    public const DEFAULT_KATEGORI = [
        'tanam'         => 'Ha',
        'panen'         => 'Ha',
        'produksi'      => 'Ton',
        'produktivitas' => 'Kw/Ha',
    ];

    public function exportTemplate(?string $tahun = null): Spreadsheet
    {
        return $this->export($tahun ?: date('Y'), true);
    }

    protected function getKomoditasList(): array
    {
        $activeList = Komoditas::getListBySektor('perkebunan');
        if (!empty($activeList)) {
            return $activeList;
        }
        return array_keys(self::DEFAULT_SHEETS);
    }

    public function export(string $tahun, bool $isTemplate = false): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $prevYear = (int)$tahun - 1;
        $komoditasList = $this->getKomoditasList();

        foreach ($komoditasList as $sheetIndex => $komoditas) {
            $worksheet = $spreadsheet->createSheet($sheetIndex);
            $worksheet->setTitle(substr($komoditas, 0, 31));

            // Banner judul dan petunjuk
            $worksheet->mergeCells("A1:W1");
            $worksheet->setCellValue("A1", "PEMERINTAH KABUPATEN KUNINGAN - DINAS KETAHANAN PANGAN DAN PERTANIAN");
            $worksheet->getStyle("A1")->getFont()->setBold(true)->setSize(12);

            $worksheet->mergeCells("A2:W2");
            $worksheet->setCellValue("A2", "DATA UTAMA PERKEBUNAN: " . strtoupper($komoditas) . " - TAHUN {$tahun}");
            $worksheet->getStyle("A2")->getFont()->setBold(true)->setSize(11);

            $worksheet->mergeCells("A3:W3");
            $worksheet->setCellValue("A3", "* Petunjuk: Cukup isi angka pada kolom Luas Baku, Target, dan Bulan Realisasi. Kolom Total, Jumlah, dan % sudah dilengkapi rumus otomatis.");
            $worksheet->getStyle("A3")->getFont()->setItalic(true)->setSize(9)->getColor()->setARGB('FF64748B');

            $dbItems = $isTemplate
                ? collect()
                : PerkebunanUtama::where('tahun', $tahun)
                    ->where('komoditas', $komoditas)
                    ->get()
                    ->groupBy('kategori');

            $tanamItems = ($dbItems->get('tanam') ?? collect())->keyBy(fn($x) => strtoupper(trim($x->kecamatan)));
            $panenItems = ($dbItems->get('panen') ?? collect())->keyBy(fn($x) => strtoupper(trim($x->kecamatan)));
            $produksiItems = ($dbItems->get('produksi') ?? collect())->keyBy(fn($x) => strtoupper(trim($x->kecamatan)));
            $produktivitasItems = ($dbItems->get('produktivitas') ?? collect())->keyBy(fn($x) => strtoupper(trim($x->kecamatan)));
            $pusoItems = ($dbItems->get('puso') ?? collect())->keyBy(fn($x) => strtoupper(trim($x->kecamatan)));

            $currentRow = 5;

            // 1. TABEL TANAM (MH: Okt-Mar & MK: Apr-Sep)
            $tanamStartRow = $this->writeTanamTable(
                $worksheet,
                $currentRow,
                $komoditas,
                $tahun,
                $prevYear,
                $tanamItems
            );
            $currentRow = $tanamStartRow['nextRow'] + 2;

            // 2. TABEL PANEN (Sem 1: Jan-Jun & Sem 2: Jul-Des)
            $panenStartRow = $this->writeStandardTable(
                $worksheet,
                $currentRow,
                "2. TARGET DAN REALISASI PANEN " . strtoupper($komoditas) . " TAHUN {$tahun}",
                "Target Luas Panen " . $komoditas . " (Ha)",
                "REALISASI PANEN " . strtoupper($komoditas),
                "Total Panen (Ha)",
                $panenItems,
                'Ha',
                'FFEFF6FF',
                'FFDBEAFE'
            );
            $currentRow = $panenStartRow['nextRow'] + 2;

            // 3. TABEL PRODUKSI (Sem 1: Jan-Jun & Sem 2: Jul-Des)
            $produksiStartRow = $this->writeStandardTable(
                $worksheet,
                $currentRow,
                "3. TARGET DAN REALISASI PRODUKSI " . strtoupper($komoditas) . " TAHUN {$tahun}",
                "Target Produksi " . $komoditas . " (Ton)",
                "REALISASI PRODUKSI " . strtoupper($komoditas),
                "Total Produksi (Ton)",
                $produksiItems,
                'Ton',
                'FFFFFBEB',
                'FFFEF3C7'
            );
            $currentRow = $produksiStartRow['nextRow'] + 2;

            // 4. TABEL PRODUKTIVITAS (Kw/Ha) dengan rumus otomatis dari Produksi & Panen
            $produktivitasStartRow = $this->writeProduktivitasTable(
                $worksheet,
                $currentRow,
                "4. TARGET DAN REALISASI PRODUKTIVITAS " . strtoupper($komoditas) . " TAHUN {$tahun}",
                $produktivitasItems,
                $panenStartRow['dataStart'],
                $produksiStartRow['dataStart']
            );
            $currentRow = $produktivitasStartRow['nextRow'] + 2;

            // 5. TABEL PUSO (GAGAL PANEN)
            $this->writePusoTable(
                $worksheet,
                $currentRow,
                "5. DATA PUSO (GAGAL PANEN) " . strtoupper($komoditas) . " TAHUN {$tahun}",
                $pusoItems
            );

            // Set lebar kolom yang proporsional dan mudah dibaca
            $colWidths = [
                'A' => 6,
                'B' => 20,
                'C' => 15,
                'D' => 13,
                'E' => 13,
                'F' => 13,
                'G' => 10,
                'H' => 10,
                'I' => 10,
                'J' => 10,
                'K' => 10,
                'L' => 10,
                'M' => 13,
                'N' => 10,
                'O' => 10,
                'P' => 10,
                'Q' => 10,
                'R' => 10,
                'S' => 10,
                'T' => 13,
                'U' => 14,
                'V' => 11,
                'W' => 11,
            ];
            foreach ($colWidths as $col => $w) {
                $worksheet->getColumnDimension($col)->setWidth($w);
            }
        }

        $spreadsheet->setActiveSheetIndex(0);
        return $spreadsheet;
    }

    protected function writeTanamTable($ws, int $startR, string $komoditas, string $tahun, int $prevYear, $items): array
    {
        $ws->mergeCells("A{$startR}:W{$startR}");
        $ws->setCellValue("A{$startR}", "1. TARGET TANAM DAN REALISASI TANAM " . strtoupper($komoditas) . " TOTAL MT {$prevYear}/{$tahun} DAN MT {$tahun}");
        $ws->getStyle("A{$startR}")->getFont()->setBold(true)->setSize(11);
        $ws->getStyle("A{$startR}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');

        $hR = $startR + 2;
        $hR2 = $hR + 1;

        $ws->mergeCells("A{$hR}:A{$hR2}")->setCellValue("A{$hR}", 'No');
        $ws->mergeCells("B{$hR}:B{$hR2}")->setCellValue("B{$hR}", 'Kecamatan');
        $ws->mergeCells("C{$hR}:C{$hR2}")->setCellValue("C{$hR}", 'Luas Sawah (Ha)');

        $ws->mergeCells("D{$hR}:F{$hR}")->setCellValue("D{$hR}", "Target Luas Tanam (Ha)");
        $ws->setCellValue("D{$hR2}", 'okmar');
        $ws->setCellValue("E{$hR2}", 'asep');
        $ws->setCellValue("F{$hR2}", 'Total');

        $ws->mergeCells("G{$hR}:M{$hR}")->setCellValue("G{$hR}", "REALISASI TANAM MUSIM HUJAN (MH)");
        $ws->setCellValue("G{$hR2}", 'Okt');
        $ws->setCellValue("H{$hR2}", 'Nop');
        $ws->setCellValue("I{$hR2}", 'Des');
        $ws->setCellValue("J{$hR2}", 'Jan');
        $ws->setCellValue("K{$hR2}", 'Feb');
        $ws->setCellValue("L{$hR2}", 'Mar');
        $ws->setCellValue("M{$hR2}", 'Jumlah MH');

        $ws->mergeCells("N{$hR}:T{$hR}")->setCellValue("N{$hR}", "REALISASI TANAM MUSIM KEMARAU (MK)");
        $ws->setCellValue("N{$hR2}", 'Apr');
        $ws->setCellValue("O{$hR2}", 'Mei');
        $ws->setCellValue("P{$hR2}", 'Jun');
        $ws->setCellValue("Q{$hR2}", 'Jul');
        $ws->setCellValue("R{$hR2}", 'Ags');
        $ws->setCellValue("S{$hR2}", 'Sep');
        $ws->setCellValue("T{$hR2}", 'Jumlah MK');

        $ws->mergeCells("U{$hR}:U{$hR2}")->setCellValue("U{$hR}", "Total " . $komoditas);
        $ws->mergeCells("V{$hR}:V{$hR2}")->setCellValue("V{$hR}", '% MH');
        $ws->mergeCells("W{$hR}:W{$hR2}")->setCellValue("W{$hR}", '% Tahun');

        // Style header
        $ws->getStyle("A{$hR}:W{$hR2}")->getFont()->setBold(true)->setSize(9);
        $ws->getStyle("A{$hR}:W{$hR2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $ws->getStyle("A{$hR}:W{$hR2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE8F5E9');

        $dataStart = $hR2 + 1;
        $cur = $dataStart;

        foreach (PadiPalawijaExcelService::MASTER_KECAMATAN as $kec) {
            $namaKec = $kec['nama'];
            $it = $items->get($namaKec);

            $ws->setCellValue("A{$cur}", $kec['no']);
            $ws->setCellValue("B{$cur}", $namaKec);
            $ws->setCellValue("C{$cur}", $it ? (float)$it->luas_sawah : 0);
            $ws->setCellValue("D{$cur}", $it ? (float)$it->target_okmar : 0);
            $ws->setCellValue("E{$cur}", $it ? (float)$it->target_asep : 0);
            $ws->setCellValue("F{$cur}", "=SUM(D{$cur}:E{$cur})");

            $ws->setCellValue("G{$cur}", $it ? (float)$it->realisasi_okt : 0);
            $ws->setCellValue("H{$cur}", $it ? (float)$it->realisasi_nop : 0);
            $ws->setCellValue("I{$cur}", $it ? (float)$it->realisasi_des : 0);
            $ws->setCellValue("J{$cur}", $it ? (float)$it->realisasi_jan : 0);
            $ws->setCellValue("K{$cur}", $it ? (float)$it->realisasi_feb : 0);
            $ws->setCellValue("L{$cur}", $it ? (float)$it->realisasi_mar : 0);
            $ws->setCellValue("M{$cur}", "=SUM(G{$cur}:L{$cur})");

            $ws->setCellValue("N{$cur}", $it ? (float)$it->realisasi_apr : 0);
            $ws->setCellValue("O{$cur}", $it ? (float)$it->realisasi_mei : 0);
            $ws->setCellValue("P{$cur}", $it ? (float)$it->realisasi_jun : 0);
            $ws->setCellValue("Q{$cur}", $it ? (float)$it->realisasi_jul : 0);
            $ws->setCellValue("R{$cur}", $it ? (float)$it->realisasi_ags : 0);
            $ws->setCellValue("S{$cur}", $it ? (float)$it->realisasi_sep : 0);
            $ws->setCellValue("T{$cur}", "=SUM(N{$cur}:S{$cur})");

            $ws->setCellValue("U{$cur}", "=M{$cur}+T{$cur}");
            $ws->setCellValue("V{$cur}", "=IF(D{$cur}>0, M{$cur}/D{$cur}*100, 0)");
            $ws->setCellValue("W{$cur}", "=IF(F{$cur}>0, U{$cur}/F{$cur}*100, 0)");

            $ws->getStyle("A{$cur}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $ws->getStyle("C{$cur}:W{$cur}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $cur++;
        }

        $dataEnd = $cur - 1;
        $totalRow = $cur;

        $ws->mergeCells("A{$totalRow}:B{$totalRow}")->setCellValue("A{$totalRow}", 'JUMLAH KABUPATEN');
        $ws->setCellValue("C{$totalRow}", "=SUM(C{$dataStart}:C{$dataEnd})");
        $ws->setCellValue("D{$totalRow}", "=SUM(D{$dataStart}:D{$dataEnd})");
        $ws->setCellValue("E{$totalRow}", "=SUM(E{$dataStart}:E{$dataEnd})");
        $ws->setCellValue("F{$totalRow}", "=SUM(F{$dataStart}:F{$dataEnd})");

        foreach (['G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U'] as $col) {
            $ws->setCellValue("{$col}{$totalRow}", "=SUM({$col}{$dataStart}:{$col}{$dataEnd})");
        }
        $ws->setCellValue("V{$totalRow}", "=IF(D{$totalRow}>0, M{$totalRow}/D{$totalRow}*100, 0)");
        $ws->setCellValue("W{$totalRow}", "=IF(F{$totalRow}>0, U{$totalRow}/F{$totalRow}*100, 0)");

        $ws->getStyle("A{$totalRow}:W{$totalRow}")->getFont()->setBold(true);
        $ws->getStyle("A{$totalRow}:W{$totalRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');

        $range = "A{$hR}:W{$totalRow}";
        $ws->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $ws->getStyle("C{$dataStart}:U{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.##');
        $ws->getStyle("V{$dataStart}:W{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.00');

        return ['dataStart' => $dataStart, 'dataEnd' => $dataEnd, 'totalRow' => $totalRow, 'nextRow' => $totalRow + 1];
    }

    protected function writeStandardTable($ws, int $startR, string $title, string $targetTitle, string $realTitle, string $totalTitle, $items, string $unit, string $headerColor = 'FFE8F5E9', string $footerColor = 'FFDCFCE7'): array
    {
        $ws->mergeCells("A{$startR}:W{$startR}");
        $ws->setCellValue("A{$startR}", $title);
        $ws->getStyle("A{$startR}")->getFont()->setBold(true)->setSize(11);
        $ws->getStyle("A{$startR}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($footerColor);

        $hR = $startR + 2;
        $hR2 = $hR + 1;

        $ws->mergeCells("A{$hR}:A{$hR2}")->setCellValue("A{$hR}", 'No');
        $ws->mergeCells("B{$hR}:B{$hR2}")->setCellValue("B{$hR}", 'Kecamatan');
        $ws->mergeCells("C{$hR}:C{$hR2}")->setCellValue("C{$hR}", 'BAKU LAHAN (Ha)');

        $ws->mergeCells("D{$hR}:F{$hR}")->setCellValue("D{$hR}", $targetTitle);
        $ws->setCellValue("D{$hR2}", 'Jan-Jun');
        $ws->setCellValue("E{$hR2}", 'Jul-Des');
        $ws->setCellValue("F{$hR2}", 'Total');

        $ws->mergeCells("G{$hR}:M{$hR}")->setCellValue("G{$hR}", $realTitle . " SEMESTER 1 (JAN - JUN)");
        $ws->setCellValue("G{$hR2}", 'Jan');
        $ws->setCellValue("H{$hR2}", 'Feb');
        $ws->setCellValue("I{$hR2}", 'Mar');
        $ws->setCellValue("J{$hR2}", 'Apr');
        $ws->setCellValue("K{$hR2}", 'Mei');
        $ws->setCellValue("L{$hR2}", 'Jun');
        $ws->setCellValue("M{$hR2}", 'Jumlah Sem 1');

        $ws->mergeCells("N{$hR}:T{$hR}")->setCellValue("N{$hR}", $realTitle . " SEMESTER 2 (JUL - DES)");
        $ws->setCellValue("N{$hR2}", 'Jul');
        $ws->setCellValue("O{$hR2}", 'Ags');
        $ws->setCellValue("P{$hR2}", 'Sep');
        $ws->setCellValue("Q{$hR2}", 'Okt');
        $ws->setCellValue("R{$hR2}", 'Nop');
        $ws->setCellValue("S{$hR2}", 'Des');
        $ws->setCellValue("T{$hR2}", 'Jumlah Sem 2');

        $ws->mergeCells("U{$hR}:U{$hR2}")->setCellValue("U{$hR}", $totalTitle);
        $ws->mergeCells("V{$hR}:V{$hR2}")->setCellValue("V{$hR}", '% Sem 1');
        $ws->mergeCells("W{$hR}:W{$hR2}")->setCellValue("W{$hR}", '% Tahun');

        // Style Header
        $ws->getStyle("A{$hR}:W{$hR2}")->getFont()->setBold(true)->setSize(9);
        $ws->getStyle("A{$hR}:W{$hR2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $ws->getStyle("A{$hR}:W{$hR2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($headerColor);

        $dataStart = $hR2 + 1;
        $cur = $dataStart;

        foreach (PadiPalawijaExcelService::MASTER_KECAMATAN as $kec) {
            $namaKec = $kec['nama'];
            $it = $items->get($namaKec);

            $ws->setCellValue("A{$cur}", $kec['no']);
            $ws->setCellValue("B{$cur}", $namaKec);
            $ws->setCellValue("C{$cur}", $it ? (float)$it->luas_sawah : 0);
            $ws->setCellValue("D{$cur}", $it ? (float)$it->target_okmar : 0);
            $ws->setCellValue("E{$cur}", $it ? (float)$it->target_asep : 0);
            $ws->setCellValue("F{$cur}", "=SUM(D{$cur}:E{$cur})");

            $ws->setCellValue("G{$cur}", $it ? (float)$it->realisasi_jan : 0);
            $ws->setCellValue("H{$cur}", $it ? (float)$it->realisasi_feb : 0);
            $ws->setCellValue("I{$cur}", $it ? (float)$it->realisasi_mar : 0);
            $ws->setCellValue("J{$cur}", $it ? (float)$it->realisasi_apr : 0);
            $ws->setCellValue("K{$cur}", $it ? (float)$it->realisasi_mei : 0);
            $ws->setCellValue("L{$cur}", $it ? (float)$it->realisasi_jun : 0);
            $ws->setCellValue("M{$cur}", "=SUM(G{$cur}:L{$cur})");

            $ws->setCellValue("N{$cur}", $it ? (float)$it->realisasi_jul : 0);
            $ws->setCellValue("O{$cur}", $it ? (float)$it->realisasi_ags : 0);
            $ws->setCellValue("P{$cur}", $it ? (float)$it->realisasi_sep : 0);
            $ws->setCellValue("Q{$cur}", $it ? (float)$it->realisasi_okt : 0);
            $ws->setCellValue("R{$cur}", $it ? (float)$it->realisasi_nop : 0);
            $ws->setCellValue("S{$cur}", $it ? (float)$it->realisasi_des : 0);
            $ws->setCellValue("T{$cur}", "=SUM(N{$cur}:S{$cur})");

            $ws->setCellValue("U{$cur}", "=M{$cur}+T{$cur}");
            $ws->setCellValue("V{$cur}", "=IF(D{$cur}>0, M{$cur}/D{$cur}*100, 0)");
            $ws->setCellValue("W{$cur}", "=IF(F{$cur}>0, U{$cur}/F{$cur}*100, 0)");

            $ws->getStyle("A{$cur}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $ws->getStyle("C{$cur}:W{$cur}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $cur++;
        }

        $dataEnd = $cur - 1;
        $totalRow = $cur;

        $ws->mergeCells("A{$totalRow}:B{$totalRow}")->setCellValue("A{$totalRow}", 'JUMLAH KABUPATEN');
        $ws->setCellValue("C{$totalRow}", "=SUM(C{$dataStart}:C{$dataEnd})");
        $ws->setCellValue("D{$totalRow}", "=SUM(D{$dataStart}:D{$dataEnd})");
        $ws->setCellValue("E{$totalRow}", "=SUM(E{$dataStart}:E{$dataEnd})");
        $ws->setCellValue("F{$totalRow}", "=SUM(F{$dataStart}:F{$dataEnd})");

        foreach (['G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U'] as $col) {
            $ws->setCellValue("{$col}{$totalRow}", "=SUM({$col}{$dataStart}:{$col}{$dataEnd})");
        }
        $ws->setCellValue("V{$totalRow}", "=IF(D{$totalRow}>0, M{$totalRow}/D{$totalRow}*100, 0)");
        $ws->setCellValue("W{$totalRow}", "=IF(F{$totalRow}>0, U{$totalRow}/F{$totalRow}*100, 0)");

        $ws->getStyle("A{$totalRow}:W{$totalRow}")->getFont()->setBold(true);
        $ws->getStyle("A{$totalRow}:W{$totalRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($footerColor);

        $range = "A{$hR}:W{$totalRow}";
        $ws->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $ws->getStyle("C{$dataStart}:U{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.##');
        $ws->getStyle("V{$dataStart}:W{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.00');

        return ['dataStart' => $dataStart, 'dataEnd' => $dataEnd, 'totalRow' => $totalRow, 'nextRow' => $totalRow + 1];
    }

    protected function writeProduktivitasTable($ws, int $startR, string $title, $items, int $panenDataStart, int $prodDataStart): array
    {
        $ws->mergeCells("A{$startR}:W{$startR}");
        $ws->setCellValue("A{$startR}", $title);
        $ws->getStyle("A{$startR}")->getFont()->setBold(true)->setSize(11);
        $ws->getStyle("A{$startR}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF3E8FF');

        $hR = $startR + 2;
        $hR2 = $hR + 1;

        $ws->mergeCells("A{$hR}:A{$hR2}")->setCellValue("A{$hR}", 'No');
        $ws->mergeCells("B{$hR}:B{$hR2}")->setCellValue("B{$hR}", 'Kecamatan');
        $ws->mergeCells("C{$hR}:C{$hR2}")->setCellValue("C{$hR}", 'BAKU LAHAN (Ha)');

        $ws->mergeCells("D{$hR}:F{$hR}")->setCellValue("D{$hR}", "Target Produktivitas (Kw/Ha)");
        $ws->setCellValue("D{$hR2}", 'Jan-Jun');
        $ws->setCellValue("E{$hR2}", 'Jul-Des');
        $ws->setCellValue("F{$hR2}", 'Total');

        $ws->mergeCells("G{$hR}:M{$hR}")->setCellValue("G{$hR}", "REALISASI PRODUKTIVITAS SEMESTER 1");
        $ws->setCellValue("G{$hR2}", 'Jan');
        $ws->setCellValue("H{$hR2}", 'Feb');
        $ws->setCellValue("I{$hR2}", 'Mar');
        $ws->setCellValue("J{$hR2}", 'Apr');
        $ws->setCellValue("K{$hR2}", 'Mei');
        $ws->setCellValue("L{$hR2}", 'Jun');
        $ws->setCellValue("M{$hR2}", 'Rata-rata Sem 1');

        $ws->mergeCells("N{$hR}:T{$hR}")->setCellValue("N{$hR}", "REALISASI PRODUKTIVITAS SEMESTER 2");
        $ws->setCellValue("N{$hR2}", 'Jul');
        $ws->setCellValue("O{$hR2}", 'Ags');
        $ws->setCellValue("P{$hR2}", 'Sep');
        $ws->setCellValue("Q{$hR2}", 'Okt');
        $ws->setCellValue("R{$hR2}", 'Nop');
        $ws->setCellValue("S{$hR2}", 'Des');
        $ws->setCellValue("T{$hR2}", 'Rata-rata Sem 2');

        $ws->mergeCells("U{$hR}:U{$hR2}")->setCellValue("U{$hR}", "Rata-rata Tahunan");
        $ws->mergeCells("V{$hR}:V{$hR2}")->setCellValue("V{$hR}", '% Sem 1');
        $ws->mergeCells("W{$hR}:W{$hR2}")->setCellValue("W{$hR}", '% Tahun');

        $ws->getStyle("A{$hR}:W{$hR2}")->getFont()->setBold(true)->setSize(9);
        $ws->getStyle("A{$hR}:W{$hR2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $ws->getStyle("A{$hR}:W{$hR2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFAF5FF');

        $dataStart = $hR2 + 1;
        $cur = $dataStart;

        foreach (PadiPalawijaExcelService::MASTER_KECAMATAN as $idx => $kec) {
            $namaKec = $kec['nama'];
            $it = $items->get($namaKec);
            $panenRow = $panenDataStart + $idx;
            $prodRow  = $prodDataStart + $idx;

            $ws->setCellValue("A{$cur}", $kec['no']);
            $ws->setCellValue("B{$cur}", $namaKec);
            $ws->setCellValue("C{$cur}", $it ? (float)$it->luas_sawah : 0);

            // Target produktivitas = Target Produksi / Target Panen * 10
            $ws->setCellValue("D{$cur}", "=IF(D{$panenRow}>0, D{$prodRow}/D{$panenRow}*10, 0)");
            $ws->setCellValue("E{$cur}", "=IF(E{$panenRow}>0, E{$prodRow}/E{$panenRow}*10, 0)");
            $ws->setCellValue("F{$cur}", "=IF(F{$panenRow}>0, F{$prodRow}/F{$panenRow}*10, 0)");

            // Realisasi per bulan = Prod/Panen * 10
            $monthCols = ['G', 'H', 'I', 'J', 'K', 'L', 'N', 'O', 'P', 'Q', 'R', 'S'];
            foreach ($monthCols as $c) {
                $ws->setCellValue("{$c}{$cur}", "=IF({$c}{$panenRow}>0, {$c}{$prodRow}/{$c}{$panenRow}*10, 0)");
            }

            // Subtotal Sem 1 & Sem 2
            $ws->setCellValue("M{$cur}", "=IF(M{$panenRow}>0, M{$prodRow}/M{$panenRow}*10, 0)");
            $ws->setCellValue("T{$cur}", "=IF(T{$panenRow}>0, T{$prodRow}/T{$panenRow}*10, 0)");

            // Total / Rata-rata tahunan
            $ws->setCellValue("U{$cur}", "=IF(U{$panenRow}>0, U{$prodRow}/U{$panenRow}*10, 0)");
            $ws->setCellValue("V{$cur}", "=IF(D{$cur}>0, M{$cur}/D{$cur}*100, 0)");
            $ws->setCellValue("W{$cur}", "=IF(F{$cur}>0, U{$cur}/F{$cur}*100, 0)");

            $ws->getStyle("A{$cur}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $ws->getStyle("C{$cur}:W{$cur}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $cur++;
        }

        $dataEnd = $cur - 1;
        $totalRow = $cur;
        $panenTotalRow = $panenDataStart + 32;
        $prodTotalRow  = $prodDataStart + 32;

        $ws->mergeCells("A{$totalRow}:B{$totalRow}")->setCellValue("A{$totalRow}", 'RATA-RATA KABUPATEN');
        $ws->setCellValue("C{$totalRow}", "=AVERAGE(C{$dataStart}:C{$dataEnd})");
        $ws->setCellValue("D{$totalRow}", "=IF(D{$panenTotalRow}>0, D{$prodTotalRow}/D{$panenTotalRow}*10, 0)");
        $ws->setCellValue("E{$totalRow}", "=IF(E{$panenTotalRow}>0, E{$prodTotalRow}/E{$panenTotalRow}*10, 0)");
        $ws->setCellValue("F{$totalRow}", "=IF(F{$panenTotalRow}>0, F{$prodTotalRow}/F{$panenTotalRow}*10, 0)");

        foreach (['G','H','I','J','K','L','N','O','P','Q','R','S'] as $c) {
            $ws->setCellValue("{$c}{$totalRow}", "=IF({$c}{$panenTotalRow}>0, {$c}{$prodTotalRow}/{$c}{$panenTotalRow}*10, 0)");
        }
        $ws->setCellValue("M{$totalRow}", "=IF(M{$panenTotalRow}>0, M{$prodTotalRow}/M{$panenTotalRow}*10, 0)");
        $ws->setCellValue("T{$totalRow}", "=IF(T{$panenTotalRow}>0, T{$prodTotalRow}/T{$panenTotalRow}*10, 0)");
        $ws->setCellValue("U{$totalRow}", "=IF(U{$panenTotalRow}>0, U{$prodTotalRow}/U{$panenTotalRow}*10, 0)");
        $ws->setCellValue("V{$totalRow}", "=IF(D{$totalRow}>0, M{$totalRow}/D{$totalRow}*100, 0)");
        $ws->setCellValue("W{$totalRow}", "=IF(F{$totalRow}>0, U{$totalRow}/F{$totalRow}*100, 0)");

        $ws->getStyle("A{$totalRow}:W{$totalRow}")->getFont()->setBold(true);
        $ws->getStyle("A{$totalRow}:W{$totalRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF3E8FF');

        $range = "A{$hR}:W{$totalRow}";
        $ws->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $ws->getStyle("C{$dataStart}:W{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.00');

        return ['dataStart' => $dataStart, 'dataEnd' => $dataEnd, 'totalRow' => $totalRow, 'nextRow' => $totalRow + 1];
    }

    protected function writePusoTable($ws, int $startR, string $title, $items): array
    {
        $ws->mergeCells("A{$startR}:U{$startR}");
        $ws->setCellValue("A{$startR}", $title);
        $ws->getStyle("A{$startR}")->getFont()->setBold(true)->setSize(11);
        $ws->getStyle("A{$startR}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');

        $hR = $startR + 2;
        $hR2 = $hR + 1;

        $ws->mergeCells("A{$hR}:A{$hR2}")->setCellValue("A{$hR}", 'No');
        $ws->mergeCells("B{$hR}:B{$hR2}")->setCellValue("B{$hR}", 'Kecamatan');
        $ws->mergeCells("C{$hR}:C{$hR2}")->setCellValue("C{$hR}", 'BAKU LAHAN (Ha)');

        $ws->mergeCells("D{$hR}:F{$hR2}")->setCellValue("D{$hR}", 'TARGET (Ha)');

        $ws->mergeCells("G{$hR}:M{$hR}")->setCellValue("G{$hR}", "REALISASI PUSO SEMESTER 1 (Ha)");
        $ws->setCellValue("G{$hR2}", 'Jan');
        $ws->setCellValue("H{$hR2}", 'Feb');
        $ws->setCellValue("I{$hR2}", 'Mar');
        $ws->setCellValue("J{$hR2}", 'Apr');
        $ws->setCellValue("K{$hR2}", 'Mei');
        $ws->setCellValue("L{$hR2}", 'Jun');
        $ws->setCellValue("M{$hR2}", 'Jumlah Sem 1');

        $ws->mergeCells("N{$hR}:T{$hR}")->setCellValue("N{$hR}", "REALISASI PUSO SEMESTER 2 (Ha)");
        $ws->setCellValue("N{$hR2}", 'Jul');
        $ws->setCellValue("O{$hR2}", 'Ags');
        $ws->setCellValue("P{$hR2}", 'Sep');
        $ws->setCellValue("Q{$hR2}", 'Okt');
        $ws->setCellValue("R{$hR2}", 'Nop');
        $ws->setCellValue("S{$hR2}", 'Des');
        $ws->setCellValue("T{$hR2}", 'Jumlah Sem 2');

        $ws->mergeCells("U{$hR}:U{$hR2}")->setCellValue("U{$hR}", "Total Puso (Ha)");

        $ws->getStyle("A{$hR}:U{$hR2}")->getFont()->setBold(true)->setSize(9);
        $ws->getStyle("A{$hR}:U{$hR2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $ws->getStyle("A{$hR}:U{$hR2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFF1F2');

        $dataStart = $hR2 + 1;
        $cur = $dataStart;

        foreach (PadiPalawijaExcelService::MASTER_KECAMATAN as $kec) {
            $namaKec = $kec['nama'];
            $it = $items->get($namaKec);

            $ws->setCellValue("A{$cur}", $kec['no']);
            $ws->setCellValue("B{$cur}", $namaKec);
            $ws->setCellValue("C{$cur}", $it ? (float)$it->luas_sawah : 0);
            $ws->setCellValue("D{$cur}", 0);
            $ws->setCellValue("E{$cur}", 0);
            $ws->setCellValue("F{$cur}", 0);

            $ws->setCellValue("G{$cur}", $it ? (float)$it->realisasi_jan : 0);
            $ws->setCellValue("H{$cur}", $it ? (float)$it->realisasi_feb : 0);
            $ws->setCellValue("I{$cur}", $it ? (float)$it->realisasi_mar : 0);
            $ws->setCellValue("J{$cur}", $it ? (float)$it->realisasi_apr : 0);
            $ws->setCellValue("K{$cur}", $it ? (float)$it->realisasi_mei : 0);
            $ws->setCellValue("L{$cur}", $it ? (float)$it->realisasi_jun : 0);
            $ws->setCellValue("M{$cur}", "=SUM(G{$cur}:L{$cur})");

            $ws->setCellValue("N{$cur}", $it ? (float)$it->realisasi_jul : 0);
            $ws->setCellValue("O{$cur}", $it ? (float)$it->realisasi_ags : 0);
            $ws->setCellValue("P{$cur}", $it ? (float)$it->realisasi_sep : 0);
            $ws->setCellValue("Q{$cur}", $it ? (float)$it->realisasi_okt : 0);
            $ws->setCellValue("R{$cur}", $it ? (float)$it->realisasi_nop : 0);
            $ws->setCellValue("S{$cur}", $it ? (float)$it->realisasi_des : 0);
            $ws->setCellValue("T{$cur}", "=SUM(N{$cur}:S{$cur})");

            $ws->setCellValue("U{$cur}", "=M{$cur}+T{$cur}");

            $ws->getStyle("A{$cur}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $ws->getStyle("C{$cur}:U{$cur}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $cur++;
        }

        $dataEnd = $cur - 1;
        $totalRow = $cur;

        $ws->mergeCells("A{$totalRow}:B{$totalRow}")->setCellValue("A{$totalRow}", 'JUMLAH KABUPATEN');
        $ws->setCellValue("C{$totalRow}", "=SUM(C{$dataStart}:C{$dataEnd})");
        $ws->setCellValue("D{$totalRow}", 0);
        $ws->setCellValue("E{$totalRow}", 0);
        $ws->setCellValue("F{$totalRow}", 0);

        foreach (['G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U'] as $col) {
            $ws->setCellValue("{$col}{$totalRow}", "=SUM({$col}{$dataStart}:{$col}{$dataEnd})");
        }

        $ws->getStyle("A{$totalRow}:U{$totalRow}")->getFont()->setBold(true);
        $ws->getStyle("A{$totalRow}:U{$totalRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');

        $range = "A{$hR}:U{$totalRow}";
        $ws->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $ws->getStyle("C{$dataStart}:U{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.##');

        return ['dataStart' => $dataStart, 'dataEnd' => $dataEnd, 'totalRow' => $totalRow, 'nextRow' => $totalRow + 1];
    }

    public function import($filePath, string $targetTahun, ?string $targetKomoditas = null): array
    {
        $realPath = is_string($filePath) ? $filePath : $filePath->getRealPath();
        $spreadsheet = IOFactory::load($realPath);
        $sheetCount = $spreadsheet->getSheetCount();
        $totalImported = 0;
        $importedKomoditas = [];

        $ignoreList = ['rekap', 'rekapitulasi', 'cover', 'grafik', 'sheet1', 'sheet2', 'data', 'total', 'jumlah'];

        for ($i = 0; $i < $sheetCount; $i++) {
            $sheet = $spreadsheet->getSheet($i);
            $sheetTitle = trim($sheet->getTitle());
            $sheetKey = strtolower($sheetTitle);

            if (!empty($targetKomoditas) && $targetKomoditas !== 'ALL') {
                $komoditas = $targetKomoditas;
            } else {
                if (in_array($sheetKey, $ignoreList) || strlen($sheetKey) < 2) {
                    continue;
                }
                $komoditas = match ($sheetKey) {
                    'kopi', 'kopi robusta', 'kopi arabika' => 'Kopi',
                    'cengkeh'                               => 'Cengkeh',
                    'kelapa', 'kelapa dalam'                => 'Kelapa',
                    'lada'                                  => 'Lada',
                    default                                 => ucwords($sheetTitle)
                };
            }

            if (!empty($komoditas)) {
                $importedKomoditas[] = $komoditas;
            }

            $highestRow = $sheet->getHighestRow();
            $currentCategory = 'tanam';

            for ($row = 1; $row <= $highestRow; $row++) {
                $valA = trim((string)$sheet->getCell('A' . $row)->getValue());
                $valB = trim((string)$sheet->getCell('B' . $row)->getValue());
                $valC = trim((string)$sheet->getCell('C' . $row)->getValue());

                $combinedRow = strtoupper($valA . ' ' . $valB . ' ' . $valC);

                // Deteksi pergantian section tabel (Format Vertikal / Multi-Table Dinas)
                if (str_contains($combinedRow, 'TARGET') || str_contains($combinedRow, 'DATA PUSO') || str_contains($combinedRow, 'REALISASI')) {
                    if (str_contains($combinedRow, 'PUSO')) {
                        $currentCategory = 'puso';
                    } elseif (str_contains($combinedRow, 'PRODUKTIVITAS') || str_contains($combinedRow, 'PRODTIVITAS')) {
                        $currentCategory = 'produktivitas';
                    } elseif (str_contains($combinedRow, 'TANAM')) {
                        $currentCategory = 'tanam';
                    } elseif (str_contains($combinedRow, 'PANEN')) {
                        $currentCategory = 'panen';
                    } elseif (str_contains($combinedRow, 'PRODUKSI')) {
                        $currentCategory = 'produksi';
                    }
                }

                // Baris data kecamatan valid: Kolom A berupa angka 1 s/d 35 dan Kolom B adalah nama kecamatan bukan angka
                if (!is_numeric($valA) || (int)$valA < 1 || (int)$valA > 35 || empty($valB) || is_numeric($valB)) {
                    continue;
                }

                $kecUpper = strtoupper($valB);
                if ($kecUpper === 'KECAMATAN' || str_contains($kecUpper, 'JUMLAH') || str_contains($kecUpper, 'TOTAL') || str_contains($kecUpper, 'KABUPATEN')) {
                    continue;
                }

                $noUrut = (int)$valA;
                $kecamatan = $valB;

                $isFlatFormat = in_array(strtolower($valC), ['tanam', 'panen', 'produksi', 'produktivitas', 'puso']);

                if ($isFlatFormat) {
                    // Format 2: Flat Template (Col C = kategori, Col D = satuan, Col E = luas sawah)
                    $kategori = strtolower($valC);
                    $satuanRaw = trim((string)$sheet->getCell('D' . $row)->getValue());
                    $satuan = (!empty($satuanRaw) && strlen($satuanRaw) <= 15) ? $satuanRaw : (self::DEFAULT_KATEGORI[$kategori] ?? 'Ha');
                    $luasSawah = (float)$sheet->getCell('E' . $row)->getCalculatedValue();

                    $targetOkmar = (float)$sheet->getCell('F' . $row)->getCalculatedValue();
                    $targetAsep = (float)$sheet->getCell('G' . $row)->getCalculatedValue();
                    $targetTotal = (float)$sheet->getCell('H' . $row)->getCalculatedValue() ?: ($targetOkmar + $targetAsep);

                    if ($kategori === 'tanam') {
                        $rOkt = (float)$sheet->getCell('I' . $row)->getCalculatedValue();
                        $rNop = (float)$sheet->getCell('J' . $row)->getCalculatedValue();
                        $rDes = (float)$sheet->getCell('K' . $row)->getCalculatedValue();
                        $rJan = (float)$sheet->getCell('L' . $row)->getCalculatedValue();
                        $rFeb = (float)$sheet->getCell('M' . $row)->getCalculatedValue();
                        $rMar = (float)$sheet->getCell('N' . $row)->getCalculatedValue();
                        $sumMh = (float)$sheet->getCell('O' . $row)->getCalculatedValue() ?: ($rOkt + $rNop + $rDes + $rJan + $rFeb + $rMar);

                        $rApr = (float)$sheet->getCell('P' . $row)->getCalculatedValue();
                        $rMei = (float)$sheet->getCell('Q' . $row)->getCalculatedValue();
                        $rJun = (float)$sheet->getCell('R' . $row)->getCalculatedValue();
                        $rJul = (float)$sheet->getCell('S' . $row)->getCalculatedValue();
                        $rAgs = (float)$sheet->getCell('T' . $row)->getCalculatedValue();
                        $rSep = (float)$sheet->getCell('U' . $row)->getCalculatedValue();
                        $sumMk = (float)$sheet->getCell('V' . $row)->getCalculatedValue() ?: ($rApr + $rMei + $rJun + $rJul + $rAgs + $rSep);
                    } else {
                        $rJan = (float)$sheet->getCell('I' . $row)->getCalculatedValue();
                        $rFeb = (float)$sheet->getCell('J' . $row)->getCalculatedValue();
                        $rMar = (float)$sheet->getCell('K' . $row)->getCalculatedValue();
                        $rApr = (float)$sheet->getCell('L' . $row)->getCalculatedValue();
                        $rMei = (float)$sheet->getCell('M' . $row)->getCalculatedValue();
                        $rJun = (float)$sheet->getCell('N' . $row)->getCalculatedValue();
                        $sumMh = (float)$sheet->getCell('O' . $row)->getCalculatedValue();
                        if ($sumMh == 0 && $kategori !== 'produktivitas') {
                            $sumMh = $rJan + $rFeb + $rMar + $rApr + $rMei + $rJun;
                        }

                        $rJul = (float)$sheet->getCell('P' . $row)->getCalculatedValue();
                        $rAgs = (float)$sheet->getCell('Q' . $row)->getCalculatedValue();
                        $rSep = (float)$sheet->getCell('R' . $row)->getCalculatedValue();
                        $rOkt = (float)$sheet->getCell('S' . $row)->getCalculatedValue();
                        $rNop = (float)$sheet->getCell('T' . $row)->getCalculatedValue();
                        $rDes = (float)$sheet->getCell('U' . $row)->getCalculatedValue();
                        $sumMk = (float)$sheet->getCell('V' . $row)->getCalculatedValue();
                        if ($sumMk == 0 && $kategori !== 'produktivitas') {
                            $sumMk = $rJul + $rAgs + $rSep + $rOkt + $rNop + $rDes;
                        }
                    }

                    $totalReal = (float)$sheet->getCell('W' . $row)->getCalculatedValue();
                    if ($totalReal == 0 && $kategori !== 'produktivitas') {
                        $totalReal = $sumMh + $sumMk;
                    }

                    $persenTot = (float)$sheet->getCell('X' . $row)->getCalculatedValue() ?: ($targetTotal > 0 ? round(($totalReal / $targetTotal) * 100, 2) : 0);
                    $persenSub = $targetOkmar > 0 ? round(($sumMh / $targetOkmar) * 100, 2) : 0;
                } else {
                    // Format 1: Format Standar Dinas (Stacked Tables: Col C = Luas Sawah, Col D = Target 1, Col E = Target 2, Col F = Target Total)
                    $kategori = $currentCategory ?: 'tanam';
                    $satuan = self::DEFAULT_KATEGORI[$kategori] ?? 'Ha';
                    $luasSawah = (float)$sheet->getCell('C' . $row)->getCalculatedValue();

                    $targetOkmar = (float)$sheet->getCell('D' . $row)->getCalculatedValue();
                    $targetAsep = (float)$sheet->getCell('E' . $row)->getCalculatedValue();
                    $targetTotal = (float)$sheet->getCell('F' . $row)->getCalculatedValue() ?: ($targetOkmar + $targetAsep);

                    if ($kategori === 'tanam') {
                        $rOkt = (float)$sheet->getCell('G' . $row)->getCalculatedValue();
                        $rNop = (float)$sheet->getCell('H' . $row)->getCalculatedValue();
                        $rDes = (float)$sheet->getCell('I' . $row)->getCalculatedValue();
                        $rJan = (float)$sheet->getCell('J' . $row)->getCalculatedValue();
                        $rFeb = (float)$sheet->getCell('K' . $row)->getCalculatedValue();
                        $rMar = (float)$sheet->getCell('L' . $row)->getCalculatedValue();
                        $sumMh = (float)$sheet->getCell('M' . $row)->getCalculatedValue() ?: ($rOkt + $rNop + $rDes + $rJan + $rFeb + $rMar);

                        $rApr = (float)$sheet->getCell('N' . $row)->getCalculatedValue();
                        $rMei = (float)$sheet->getCell('O' . $row)->getCalculatedValue();
                        $rJun = (float)$sheet->getCell('P' . $row)->getCalculatedValue();
                        $rJul = (float)$sheet->getCell('Q' . $row)->getCalculatedValue();
                        $rAgs = (float)$sheet->getCell('R' . $row)->getCalculatedValue();
                        $rSep = (float)$sheet->getCell('S' . $row)->getCalculatedValue();
                        $sumMk = (float)$sheet->getCell('T' . $row)->getCalculatedValue() ?: ($rApr + $rMei + $rJun + $rJul + $rAgs + $rSep);
                    } else {
                        $rJan = (float)$sheet->getCell('G' . $row)->getCalculatedValue();
                        $rFeb = (float)$sheet->getCell('H' . $row)->getCalculatedValue();
                        $rMar = (float)$sheet->getCell('I' . $row)->getCalculatedValue();
                        $rApr = (float)$sheet->getCell('J' . $row)->getCalculatedValue();
                        $rMei = (float)$sheet->getCell('K' . $row)->getCalculatedValue();
                        $rJun = (float)$sheet->getCell('L' . $row)->getCalculatedValue();
                        $sumMh = (float)$sheet->getCell('M' . $row)->getCalculatedValue();
                        if ($sumMh == 0 && $kategori !== 'produktivitas') {
                            $sumMh = $rJan + $rFeb + $rMar + $rApr + $rMei + $rJun;
                        }

                        $rJul = (float)$sheet->getCell('N' . $row)->getCalculatedValue();
                        $rAgs = (float)$sheet->getCell('O' . $row)->getCalculatedValue();
                        $rSep = (float)$sheet->getCell('P' . $row)->getCalculatedValue();
                        $rOkt = (float)$sheet->getCell('Q' . $row)->getCalculatedValue();
                        $rNop = (float)$sheet->getCell('R' . $row)->getCalculatedValue();
                        $rDes = (float)$sheet->getCell('S' . $row)->getCalculatedValue();
                        $sumMk = (float)$sheet->getCell('T' . $row)->getCalculatedValue();
                        if ($sumMk == 0 && $kategori !== 'produktivitas') {
                            $sumMk = $rJul + $rAgs + $rSep + $rOkt + $rNop + $rDes;
                        }
                    }

                    $totalReal = (float)$sheet->getCell('U' . $row)->getCalculatedValue();
                    if ($totalReal == 0 && $kategori !== 'produktivitas') {
                        $totalReal = $sumMh + $sumMk;
                    }

                    $persenSub = (float)$sheet->getCell('V' . $row)->getCalculatedValue();
                    $persenTot = (float)$sheet->getCell('W' . $row)->getCalculatedValue();
                    if (is_string($persenSub) || is_nan($persenSub) || $persenSub == 0) {
                        $persenSub = $targetOkmar > 0 ? round(($sumMh / $targetOkmar) * 100, 2) : 0;
                    }
                    if (is_string($persenTot) || is_nan($persenTot) || $persenTot == 0) {
                        $persenTot = $targetTotal > 0 ? round(($totalReal / $targetTotal) * 100, 2) : 0;
                    }
                }

                $prevYear = (int)$targetTahun - 1;
                $musimTanam = $kategori === 'tanam'
                    ? "MT {$prevYear}/{$targetTahun} dan MT {$targetTahun}"
                    : "Tahun {$targetTahun}";

                PerkebunanUtama::updateOrCreate(
                    [
                        'komoditas' => $komoditas,
                        'kategori'  => $kategori,
                        'tahun'     => $targetTahun,
                        'kecamatan' => $kecamatan,
                    ],
                    [
                        'satuan'             => substr($satuan, 0, 20),
                        'musim_tanam'        => $musimTanam,
                        'no_urut'            => $noUrut,
                        'luas_sawah'         => $luasSawah,
                        'target_okmar'       => $targetOkmar,
                        'target_asep'        => $targetAsep,
                        'target_total'       => $targetTotal,
                        'realisasi_okt'      => $rOkt ?? 0,
                        'realisasi_nop'      => $rNop ?? 0,
                        'realisasi_des'      => $rDes ?? 0,
                        'realisasi_jan'      => $rJan ?? 0,
                        'realisasi_feb'      => $rFeb ?? 0,
                        'realisasi_mar'      => $rMar ?? 0,
                        'jumlah_mh'          => $sumMh,
                        'realisasi_apr'      => $rApr ?? 0,
                        'realisasi_mei'      => $rMei ?? 0,
                        'realisasi_jun'      => $rJun ?? 0,
                        'realisasi_jul'      => $rJul ?? 0,
                        'realisasi_ags'      => $rAgs ?? 0,
                        'realisasi_sep'      => $rSep ?? 0,
                        'jumlah_mk'          => $sumMk,
                        'total_realisasi'    => $totalReal,
                        'persentase_sub'     => $persenSub,
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
