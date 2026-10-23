<?php

namespace App\Services;

use App\Models\PadiPalawija;
use App\Models\Komoditas;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PadiPalawijaExcelService
{
    /**
     * Pemetaan nama sheet Excel ke nama komoditas standar
     */
    public const SHEET_MAPPING = [
        'padi sawah'   => 'Padi Sawah',
        'padi ladang'  => 'Padi Ladang',
        'padi'         => 'Padi',
        'jagung'       => 'Jagung',
        'kedele'       => 'Kedelai',
        'kedelai'      => 'Kedelai',
        'k.tanah'      => 'Kacang Tanah',
        'kacang tanah' => 'Kacang Tanah',
        'k.hijau'      => 'Kacang Hijau',
        'kacang hijau' => 'Kacang Hijau',
        'u.kayu'       => 'Ubi Kayu',
        'ubi kayu'     => 'Ubi Kayu',
        'u.jalar'      => 'Ubi Jalar',
        'ubi jalar'    => 'Ubi Jalar',
    ];

    /**
     * Daftar komoditas dan kode sheet default untuk export
     */
    public const DEFAULT_SHEETS = [
        ['sheet' => 'padi',    'name' => 'Padi'],
        ['sheet' => 'jagung',  'name' => 'Jagung'],
        ['sheet' => 'kedele',  'name' => 'Kedelai'],
        ['sheet' => 'k.tanah', 'name' => 'Kacang Tanah'],
        ['sheet' => 'k.hijau', 'name' => 'Kacang Hijau'],
        ['sheet' => 'u.kayu',  'name' => 'Ubi Kayu'],
        ['sheet' => 'u.jalar', 'name' => 'Ubi Jalar'],
    ];

    /**
     * Daftar default 32 Kecamatan Kabupaten Kuningan
     */
    public const MASTER_KECAMATAN = [
        ['no' => 1,  'nama' => 'KUNINGAN'],
        ['no' => 2,  'nama' => 'KRAMATMULYA'],
        ['no' => 3,  'nama' => 'CIGUGUR'],
        ['no' => 4,  'nama' => 'KADUGEDE'],
        ['no' => 5,  'nama' => 'DARMA'],
        ['no' => 6,  'nama' => 'CILIMUS'],
        ['no' => 7,  'nama' => 'JALAKSANA'],
        ['no' => 8,  'nama' => 'MANDIRANCAN'],
        ['no' => 9,  'nama' => 'PASAWAHAN'],
        ['no' => 10, 'nama' => 'GARAWANGI'],
        ['no' => 11, 'nama' => 'LEBAKWANGI'],
        ['no' => 12, 'nama' => 'CINIRU'],
        ['no' => 13, 'nama' => 'CIAWIGEBANG'],
        ['no' => 14, 'nama' => 'CIDAHU'],
        ['no' => 15, 'nama' => 'LURAGUNG'],
        ['no' => 16, 'nama' => 'CIWARU'],
        ['no' => 17, 'nama' => 'CIBINGBIN'],
        ['no' => 18, 'nama' => 'SUBANG'],
        ['no' => 19, 'nama' => 'SELAJAMBE'],
        ['no' => 20, 'nama' => 'NUSAHERANG'],
        ['no' => 21, 'nama' => 'PANCALANG'],
        ['no' => 22, 'nama' => 'CIPICUNG'],
        ['no' => 23, 'nama' => 'JAPARA'],
        ['no' => 24, 'nama' => 'HANTARA'],
        ['no' => 25, 'nama' => 'KALIMANGGIS'],
        ['no' => 26, 'nama' => 'CIMAHI'],
        ['no' => 27, 'nama' => 'KRKANCANA'],
        ['no' => 28, 'nama' => 'CIBEUREM'],
        ['no' => 29, 'nama' => 'CILEBAK'],
        ['no' => 30, 'nama' => 'CIGANDAMEKAR'],
        ['no' => 31, 'nama' => 'SINDANGAGUNG'],
        ['no' => 32, 'nama' => 'MALEBER'],
    ];

    /**
     * Generate file Excel (XLSX) lengkap dengan 7 sheet dan semua tabel (Tanam, Panen, Produksi, Produktivitas, Puso)
     */
    public function export(string $tahun): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // Buang sheet default

        $prevYear = (int)$tahun - 1;

        foreach (self::DEFAULT_SHEETS as $sheetIndex => $cfg) {
            $sheetKey = $cfg['sheet'];
            $komoditasName = $cfg['name'];

            $worksheet = $spreadsheet->createSheet($sheetIndex);
            $worksheet->setTitle($sheetKey);

            // Ambil semua data komoditas ini per kategori
            $dbItems = PadiPalawija::where('tahun', $tahun)
                ->where('komoditas', $komoditasName)
                ->get()
                ->groupBy('kategori');

            $tanamItems = ($dbItems->get('tanam') ?? collect())->keyBy(fn($x) => strtoupper(trim($x->kecamatan)));
            $panenItems = ($dbItems->get('panen') ?? collect())->keyBy(fn($x) => strtoupper(trim($x->kecamatan)));
            $produksiItems = ($dbItems->get('produksi') ?? collect())->keyBy(fn($x) => strtoupper(trim($x->kecamatan)));
            $produktivitasItems = ($dbItems->get('produktivitas') ?? collect())->keyBy(fn($x) => strtoupper(trim($x->kecamatan)));
            $pusoItems = ($dbItems->get('puso') ?? collect())->keyBy(fn($x) => strtoupper(trim($x->kecamatan)));

            $currentRow = 1;

            // 1. TABEL TANAM
            $tanamStartRow = $this->writeTanamTable(
                $worksheet,
                $currentRow,
                $komoditasName,
                $tahun,
                $prevYear,
                $tanamItems
            );
            $currentRow = $tanamStartRow['nextRow'] + 2;

            // 2. TABEL PANEN
            $panenStartRow = $this->writeStandardTable(
                $worksheet,
                $currentRow,
                "TARGET PANEN DAN REALISASI PANEN " . strtoupper($komoditasName) . " TAHUN {$tahun}",
                "Target Luas Panen " . $komoditasName . " (Ha)",
                "REALISASI PANEN " . strtoupper($komoditasName),
                "Total " . $komoditasName,
                $panenItems,
                'Ha'
            );
            $currentRow = $panenStartRow['nextRow'] + 2;

            // 3. TABEL PRODUKSI
            $produksiStartRow = $this->writeStandardTable(
                $worksheet,
                $currentRow,
                "TARGET PRODUKSI DAN REALISASI PRODUKSI " . strtoupper($komoditasName) . " TAHUN {$tahun}",
                "Target Produksi " . $komoditasName . " (Ton)",
                "REALISASI Produksi " . strtoupper($komoditasName),
                "Total " . $komoditasName,
                $produksiItems,
                'Ton'
            );
            $currentRow = $produksiStartRow['nextRow'] + 2;

            // 4. TABEL PRODUKTIVITAS
            $produktivitasStartRow = $this->writeProduktivitasTable(
                $worksheet,
                $currentRow,
                "TARGET PRODUKTIVITAS " . strtoupper($komoditasName) . " TAHUN {$tahun}",
                $produktivitasItems,
                $panenStartRow['dataStart'],
                $produksiStartRow['dataStart']
            );
            $currentRow = $produktivitasStartRow['nextRow'] + 2;

            // 5. TABEL PUSO (Khusus Padi atau bila ada data)
            if ($sheetKey === 'padi' || $pusoItems->count() > 0) {
                $this->writePusoTable(
                    $worksheet,
                    $currentRow,
                    "DATA PUSO {$tahun}",
                    $pusoItems
                );
            }

            // Auto-size kolom A s/d W
            foreach (range('A', 'W') as $col) {
                $worksheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        $spreadsheet->setActiveSheetIndex(0);
        return $spreadsheet;
    }

    /**
     * Tulis Tabel 1: Tanam
     */
    protected function writeTanamTable($ws, int $startR, string $komoditas, string $tahun, int $prevYear, $items): array
    {
        $ws->mergeCells("A{$startR}:W{$startR}");
        $ws->setCellValue("A{$startR}", "TARGET TANAM DAN REALISASI TANAM " . strtoupper($komoditas) . " TOTAL MT {$prevYear}/{$tahun} DAN MT {$tahun}");
        $ws->getStyle("A{$startR}")->getFont()->setBold(true)->setSize(11);

        $hR = $startR + 4; // baris header
        $hR2 = $hR + 1;
        $hR3 = $hR2 + 1;

        $ws->mergeCells("A{$hR}:A{$hR2}")->setCellValue("A{$hR}", 'No');
        $ws->mergeCells("B{$hR}:B{$hR2}")->setCellValue("B{$hR}", 'Kecamatan');
        $ws->mergeCells("C{$hR}:C{$hR2}")->setCellValue("C{$hR}", 'Luas Sawah (Ha)');

        $ws->mergeCells("D{$hR}:F{$hR}")->setCellValue("D{$hR}", "Target Luas Tanam " . strtoupper($komoditas) . " (Ha)");
        $ws->setCellValue("D{$hR2}", 'okmar');
        $ws->setCellValue("E{$hR2}", 'asep');
        $ws->setCellValue("F{$hR2}", 'Total');

        $ws->mergeCells("G{$hR}:M{$hR}")->setCellValue("G{$hR}", "REALISASI TANAM " . strtoupper($komoditas) . " MT {$prevYear}/{$tahun}");
        $ws->setCellValue("G{$hR2}", 'Okt');
        $ws->setCellValue("H{$hR2}", 'Nop');
        $ws->setCellValue("I{$hR2}", 'Des');
        $ws->setCellValue("J{$hR2}", 'Jan');
        $ws->setCellValue("K{$hR2}", 'Feb');
        $ws->setCellValue("L{$hR2}", 'Mar');
        $ws->setCellValue("M{$hR2}", 'Jumlah MH');

        $ws->mergeCells("N{$hR}:T{$hR}")->setCellValue("N{$hR}", "MT {$tahun}");
        $ws->setCellValue("N{$hR2}", 'Aprl');
        $ws->setCellValue("O{$hR2}", 'Mei');
        $ws->setCellValue("P{$hR2}", 'Juni');
        $ws->setCellValue("Q{$hR2}", 'Juli');
        $ws->setCellValue("R{$hR2}", 'Ags');
        $ws->setCellValue("S{$hR2}", 'Sep');
        $ws->setCellValue("T{$hR2}", 'Jumlah MK');

        $ws->mergeCells("U{$hR}:U{$hR2}")->setCellValue("U{$hR}", "Total " . $komoditas);
        $ws->mergeCells("V{$hR}:V{$hR2}")->setCellValue("V{$hR}", '% Okt-Mar');
        $ws->mergeCells("W{$hR}:W{$hR2}")->setCellValue("W{$hR}", '%tahun');

        // Style header
        $ws->getStyle("A{$hR}:W{$hR2}")->getFont()->setBold(true)->setSize(9);
        $ws->getStyle("A{$hR}:W{$hR2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $ws->getStyle("A{$hR}:W{$hR2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE8F5E9');

        $dataStart = $hR2 + 1;
        $cur = $dataStart;

        foreach (self::MASTER_KECAMATAN as $kec) {
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

        $ws->mergeCells("A{$totalRow}:B{$totalRow}")->setCellValue("A{$totalRow}", 'JUMLAH');
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

    /**
     * Tulis Tabel Standar: Panen atau Produksi (Semester 1 Jan-Jun & Semester 2 Jul-Des)
     */
    protected function writeStandardTable($ws, int $startR, string $title, string $targetTitle, string $realTitle, string $totalTitle, $items, string $unit): array
    {
        $ws->mergeCells("A{$startR}:W{$startR}");
        $ws->setCellValue("A{$startR}", $title);
        $ws->getStyle("A{$startR}")->getFont()->setBold(true)->setSize(11);

        $hR = $startR + 4;
        $hR2 = $hR + 1;

        $ws->mergeCells("A{$hR}:A{$hR2}")->setCellValue("A{$hR}", 'No');
        $ws->mergeCells("B{$hR}:B{$hR2}")->setCellValue("B{$hR}", 'Kecamatan');
        $ws->mergeCells("C{$hR}:C{$hR2}")->setCellValue("C{$hR}", 'BAKU LAHAN');

        $ws->mergeCells("D{$hR}:F{$hR}")->setCellValue("D{$hR}", $targetTitle);
        $ws->setCellValue("D{$hR2}", 'Januari-juni');
        $ws->setCellValue("E{$hR2}", 'Juli-Desember');
        $ws->setCellValue("F{$hR2}", 'Total');

        $ws->mergeCells("G{$hR}:M{$hR}")->setCellValue("G{$hR}", $realTitle . " JAN-JUN");
        $ws->setCellValue("G{$hR2}", 'JAN');
        $ws->setCellValue("H{$hR2}", 'PEB');
        $ws->setCellValue("I{$hR2}", 'MARET');
        $ws->setCellValue("J{$hR2}", 'APRIL');
        $ws->setCellValue("K{$hR2}", 'MEI');
        $ws->setCellValue("L{$hR2}", 'JUNI');
        $ws->setCellValue("M{$hR2}", 'Jumlah');

        $ws->mergeCells("N{$hR}:T{$hR}")->setCellValue("N{$hR}", "JUL-DES");
        $ws->setCellValue("N{$hR2}", 'JULI');
        $ws->setCellValue("O{$hR2}", 'AGST');
        $ws->setCellValue("P{$hR2}", 'SEPT');
        $ws->setCellValue("Q{$hR2}", 'OKT');
        $ws->setCellValue("R{$hR2}", 'NOV');
        $ws->setCellValue("S{$hR2}", 'DES');
        $ws->setCellValue("T{$hR2}", 'Jumlah');

        $ws->mergeCells("U{$hR}:U{$hR2}")->setCellValue("U{$hR}", $totalTitle);
        $ws->mergeCells("V{$hR}:V{$hR2}")->setCellValue("V{$hR}", '% Jan-jun');
        $ws->mergeCells("W{$hR}:W{$hR2}")->setCellValue("W{$hR}", '%tahun');

        // Style Header
        $ws->getStyle("A{$hR}:W{$hR2}")->getFont()->setBold(true)->setSize(9);
        $ws->getStyle("A{$hR}:W{$hR2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $ws->getStyle("A{$hR}:W{$hR2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE8F5E9');

        $dataStart = $hR2 + 1;
        $cur = $dataStart;

        foreach (self::MASTER_KECAMATAN as $kec) {
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

        $ws->mergeCells("A{$totalRow}:B{$totalRow}")->setCellValue("A{$totalRow}", 'JUMLAH');
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

    /**
     * Tulis Tabel 4: Produktivitas (Kw/Ha) dengan rumus =Produksi/Panen*10
     */
    protected function writeProduktivitasTable($ws, int $startR, string $title, $items, int $panenDataStart, int $prodDataStart): array
    {
        $ws->mergeCells("A{$startR}:W{$startR}");
        $ws->setCellValue("A{$startR}", $title);
        $ws->getStyle("A{$startR}")->getFont()->setBold(true)->setSize(11);

        $hR = $startR + 4;
        $hR2 = $hR + 1;

        $ws->mergeCells("A{$hR}:A{$hR2}")->setCellValue("A{$hR}", 'No');
        $ws->mergeCells("B{$hR}:B{$hR2}")->setCellValue("B{$hR}", 'Kecamatan');
        $ws->mergeCells("C{$hR}:C{$hR2}")->setCellValue("C{$hR}", 'BAKU LAHAN');

        $ws->mergeCells("D{$hR}:F{$hR}")->setCellValue("D{$hR}", "Target Produktivitas (Kw)");
        $ws->setCellValue("D{$hR2}", 'Januari-juni');
        $ws->setCellValue("E{$hR2}", 'Juli-Desember');
        $ws->setCellValue("F{$hR2}", 'Total');

        $ws->mergeCells("G{$hR}:M{$hR}")->setCellValue("G{$hR}", "REALISASI PRODUKTIVITAS JAN-JUN");
        $ws->setCellValue("G{$hR2}", 'JAN');
        $ws->setCellValue("H{$hR2}", 'PEB');
        $ws->setCellValue("I{$hR2}", 'MARET');
        $ws->setCellValue("J{$hR2}", 'APRIL');
        $ws->setCellValue("K{$hR2}", 'MEI');
        $ws->setCellValue("L{$hR2}", 'JUNI');
        $ws->setCellValue("M{$hR2}", 'Rata-rata');

        $ws->mergeCells("N{$hR}:T{$hR}")->setCellValue("N{$hR}", "JUL-DES");
        $ws->setCellValue("N{$hR2}", 'JULI');
        $ws->setCellValue("O{$hR2}", 'AGST');
        $ws->setCellValue("P{$hR2}", 'SEPT');
        $ws->setCellValue("Q{$hR2}", 'OKT');
        $ws->setCellValue("R{$hR2}", 'NOV');
        $ws->setCellValue("S{$hR2}", 'DES');
        $ws->setCellValue("T{$hR2}", 'Rata-rata');

        $ws->mergeCells("U{$hR}:U{$hR2}")->setCellValue("U{$hR}", "Rata-rata");
        $ws->mergeCells("V{$hR}:V{$hR2}")->setCellValue("V{$hR}", '% Jan-jun');
        $ws->mergeCells("W{$hR}:W{$hR2}")->setCellValue("W{$hR}", '%tahun');

        $ws->getStyle("A{$hR}:W{$hR2}")->getFont()->setBold(true)->setSize(9);
        $ws->getStyle("A{$hR}:W{$hR2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $ws->getStyle("A{$hR}:W{$hR2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE8F5E9');

        $dataStart = $hR2 + 1;
        $cur = $dataStart;

        foreach (self::MASTER_KECAMATAN as $idx => $kec) {
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

        $ws->mergeCells("A{$totalRow}:B{$totalRow}")->setCellValue("A{$totalRow}", 'RATA-RATA');
        $ws->setCellValue("C{$totalRow}", "=AVERAGE(C{$dataStart}:C{$dataEnd})");
        $ws->setCellValue("D{$totalRow}", "=IF(D{$panenTotalRow}>0, D{$prodTotalRow}/D{$panenTotalRow}*10, 0)");
        $ws->setCellValue("E{$totalRow}", "=IF(E{$panenTotalRow}>0, E{$prodTotalRow}/E{$panenTotalRow}*10, 0)");
        $ws->setCellValue("F{$totalRow}", "=IF(F{$panenTotalRow}>0, F{$prodTotalRow}/F{$panenTotalRow}*10, 0)");

        foreach ($monthCols as $c) {
            $ws->setCellValue("{$c}{$totalRow}", "=IF({$c}{$panenTotalRow}>0, {$c}{$prodTotalRow}/{$c}{$panenTotalRow}*10, 0)");
        }
        $ws->setCellValue("M{$totalRow}", "=IF(M{$panenTotalRow}>0, M{$prodTotalRow}/M{$panenTotalRow}*10, 0)");
        $ws->setCellValue("T{$totalRow}", "=IF(T{$panenTotalRow}>0, T{$prodTotalRow}/T{$panenTotalRow}*10, 0)");
        $ws->setCellValue("U{$totalRow}", "=IF(U{$panenTotalRow}>0, U{$prodTotalRow}/U{$panenTotalRow}*10, 0)");
        $ws->setCellValue("V{$totalRow}", "=IF(D{$totalRow}>0, M{$totalRow}/D{$totalRow}*100, 0)");
        $ws->setCellValue("W{$totalRow}", "=IF(F{$totalRow}>0, U{$totalRow}/F{$totalRow}*100, 0)");

        $ws->getStyle("A{$totalRow}:W{$totalRow}")->getFont()->setBold(true);
        $ws->getStyle("A{$totalRow}:W{$totalRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDCFCE7');

        $range = "A{$hR}:W{$totalRow}";
        $ws->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $ws->getStyle("C{$dataStart}:W{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.00');

        return ['dataStart' => $dataStart, 'dataEnd' => $dataEnd, 'totalRow' => $totalRow, 'nextRow' => $totalRow + 1];
    }

    /**
     * Tulis Tabel 5: Puso
     */
    protected function writePusoTable($ws, int $startR, string $title, $items): array
    {
        $ws->mergeCells("A{$startR}:U{$startR}");
        $ws->setCellValue("A{$startR}", $title);
        $ws->getStyle("A{$startR}")->getFont()->setBold(true)->setSize(11);

        $hR = $startR + 4;
        $hR2 = $hR + 1;

        $ws->mergeCells("A{$hR}:A{$hR2}")->setCellValue("A{$hR}", 'No');
        $ws->mergeCells("B{$hR}:B{$hR2}")->setCellValue("B{$hR}", 'Kecamatan');
        $ws->mergeCells("C{$hR}:C{$hR2}")->setCellValue("C{$hR}", 'BAKU LAHAN');

        $ws->mergeCells("D{$hR}:F{$hR2}")->setCellValue("D{$hR}", 'TARGET (Ha)');

        $ws->mergeCells("G{$hR}:M{$hR}")->setCellValue("G{$hR}", "REALISASI PUSO JAN-JUN (Ha)");
        $ws->setCellValue("G{$hR2}", 'JAN');
        $ws->setCellValue("H{$hR2}", 'PEB');
        $ws->setCellValue("I{$hR2}", 'MARET');
        $ws->setCellValue("J{$hR2}", 'APRIL');
        $ws->setCellValue("K{$hR2}", 'MEI');
        $ws->setCellValue("L{$hR2}", 'JUNI');
        $ws->setCellValue("M{$hR2}", 'Jumlah');

        $ws->mergeCells("N{$hR}:T{$hR}")->setCellValue("N{$hR}", "JUL-DES (Ha)");
        $ws->setCellValue("N{$hR2}", 'JULI');
        $ws->setCellValue("O{$hR2}", 'AGST');
        $ws->setCellValue("P{$hR2}", 'SEPT');
        $ws->setCellValue("Q{$hR2}", 'OKT');
        $ws->setCellValue("R{$hR2}", 'NOV');
        $ws->setCellValue("S{$hR2}", 'DES');
        $ws->setCellValue("T{$hR2}", 'Jumlah');

        $ws->mergeCells("U{$hR}:U{$hR2}")->setCellValue("U{$hR}", "Total Puso");

        $ws->getStyle("A{$hR}:U{$hR2}")->getFont()->setBold(true)->setSize(9);
        $ws->getStyle("A{$hR}:U{$hR2}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $ws->getStyle("A{$hR}:U{$hR2}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFEE2E2');

        $dataStart = $hR2 + 1;
        $cur = $dataStart;

        foreach (self::MASTER_KECAMATAN as $kec) {
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

        $ws->mergeCells("A{$totalRow}:B{$totalRow}")->setCellValue("A{$totalRow}", 'JUMLAH');
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

    /**
     * Import file Excel (XLS / XLSX) dan menggantikan data lengkap semua komoditas & kategori untuk tahun terpilih
     *
     * @return array [ 'success' => bool, 'message' => string, 'totalRows' => int, 'sheets' => array, 'kategoriCounts' => array ]
     */
    public function import(UploadedFile $file, string $tahun): array
    {
        $filePath = $file->getRealPath();
        $spreadsheet = IOFactory::load($filePath);
        $sheetNames = $spreadsheet->getSheetNames();

        $processedSheets = [];
        $recordsToInsert = [];
        $kategoriCounts = [
            'tanam' => 0,
            'panen' => 0,
            'produksi' => 0,
            'produktivitas' => 0,
            'puso' => 0,
        ];

        $prevYear = (int)$tahun - 1;

        foreach ($sheetNames as $rawSheetName) {
            $sheetKey = strtolower(trim($rawSheetName));

            // Cocokkan nama sheet ke komoditas
            $komoditas = null;
            foreach (self::SHEET_MAPPING as $pattern => $mappedName) {
                if ($sheetKey === $pattern || strpos($sheetKey, $pattern) !== false) {
                    $komoditas = $mappedName;
                    break;
                }
            }

            if (!$komoditas) {
                // Abaikan sheet rekapitulasi, grafik, atau sheet umum
                $ignoreList = ['rekap', 'rekapitulasi', 'cover', 'grafik', 'sheet1', 'sheet2', 'data', 'total', 'jumlah'];
                if (!in_array($sheetKey, $ignoreList) && strlen($sheetKey) >= 3) {
                    $komoditas = ucwords(trim($rawSheetName));
                } else {
                    continue; // Bukan sheet komoditas yang dikenali
                }
            }

            $worksheet = $spreadsheet->getSheetByName($rawSheetName);
            $maxRow = $worksheet->getHighestRow();

            $currentCategory = null;

            for ($r = 1; $r <= $maxRow; $r++) {
                $valA = trim((string) $worksheet->getCell('A' . $r)->getValue());
                $valB = trim((string) $worksheet->getCell('B' . $r)->getValue());

                $combinedRow = strtoupper($valA . ' ' . $valB);

                // Deteksi pergantian tabel
                if (str_contains($combinedRow, 'TARGET') || str_contains($combinedRow, 'DATA PUSO') || str_contains($combinedRow, 'REALISASI')) {
                    if (str_contains($combinedRow, 'TANAM')) {
                        $currentCategory = 'tanam';
                    } elseif (str_contains($combinedRow, 'PRODUKTIVITAS') || str_contains($combinedRow, 'PRODTIVITAS')) {
                        $currentCategory = 'produktivitas';
                    } elseif (str_contains($combinedRow, 'PANEN')) {
                        $currentCategory = 'panen';
                    } elseif (str_contains($combinedRow, 'PRODUKSI')) {
                        $currentCategory = 'produksi';
                    } elseif (str_contains($combinedRow, 'PUSO')) {
                        $currentCategory = 'puso';
                    }
                }

                // Cek baris data kecamatan: Col A angka 1 s/d 35, Col B nama kecamatan
                if (is_numeric($valA) && (int)$valA >= 1 && (int)$valA <= 35 && !empty($valB) && !is_numeric($valB)) {
                    $kecUpper = strtoupper($valB);
                    if ($kecUpper === 'KECAMATAN' || str_contains($kecUpper, 'JUMLAH') || str_contains($kecUpper, 'TOTAL')) {
                        continue;
                    }

                    if (!$currentCategory) {
                        $currentCategory = 'tanam'; // default fallback
                    }

                    $noUrut = (int)$valA;
                    $luasSawah = (float)$worksheet->getCell('C' . $r)->getCalculatedValue();

                    $t1 = (float)$worksheet->getCell('D' . $r)->getCalculatedValue();
                    $t2 = (float)$worksheet->getCell('E' . $r)->getCalculatedValue();
                    $tTot = (float)$worksheet->getCell('F' . $r)->getCalculatedValue();
                    if ($tTot == 0 && ($t1 > 0 || $t2 > 0)) {
                        $tTot = $t1 + $t2;
                    }

                    if ($currentCategory === 'tanam') {
                        // Tanam: G..L = Okt..Mar MH, N..S = Apr..Sep MK
                        $rOkt = (float)$worksheet->getCell('G' . $r)->getCalculatedValue();
                        $rNop = (float)$worksheet->getCell('H' . $r)->getCalculatedValue();
                        $rDes = (float)$worksheet->getCell('I' . $r)->getCalculatedValue();
                        $rJan = (float)$worksheet->getCell('J' . $r)->getCalculatedValue();
                        $rFeb = (float)$worksheet->getCell('K' . $r)->getCalculatedValue();
                        $rMar = (float)$worksheet->getCell('L' . $r)->getCalculatedValue();
                        $sub1 = (float)$worksheet->getCell('M' . $r)->getCalculatedValue();
                        if ($sub1 == 0) {
                            $sub1 = $rOkt + $rNop + $rDes + $rJan + $rFeb + $rMar;
                        }

                        $rApr = (float)$worksheet->getCell('N' . $r)->getCalculatedValue();
                        $rMei = (float)$worksheet->getCell('O' . $r)->getCalculatedValue();
                        $rJun = (float)$worksheet->getCell('P' . $r)->getCalculatedValue();
                        $rJul = (float)$worksheet->getCell('Q' . $r)->getCalculatedValue();
                        $rAgs = (float)$worksheet->getCell('R' . $r)->getCalculatedValue();
                        $rSep = (float)$worksheet->getCell('S' . $r)->getCalculatedValue();
                        $sub2 = (float)$worksheet->getCell('T' . $r)->getCalculatedValue();
                        if ($sub2 == 0) {
                            $sub2 = $rApr + $rMei + $rJun + $rJul + $rAgs + $rSep;
                        }
                    } else {
                        // Panen, Produksi, Produktivitas, Puso: G..L = Jan..Jun, N..S = Jul..Des
                        $rJan = (float)$worksheet->getCell('G' . $r)->getCalculatedValue();
                        $rFeb = (float)$worksheet->getCell('H' . $r)->getCalculatedValue();
                        $rMar = (float)$worksheet->getCell('I' . $r)->getCalculatedValue();
                        $rApr = (float)$worksheet->getCell('J' . $r)->getCalculatedValue();
                        $rMei = (float)$worksheet->getCell('K' . $r)->getCalculatedValue();
                        $rJun = (float)$worksheet->getCell('L' . $r)->getCalculatedValue();
                        $sub1 = (float)$worksheet->getCell('M' . $r)->getCalculatedValue();
                        if ($sub1 == 0 && $currentCategory !== 'produktivitas') {
                            $sub1 = $rJan + $rFeb + $rMar + $rApr + $rMei + $rJun;
                        }

                        $rJul = (float)$worksheet->getCell('N' . $r)->getCalculatedValue();
                        $rAgs = (float)$worksheet->getCell('O' . $r)->getCalculatedValue();
                        $rSep = (float)$worksheet->getCell('P' . $r)->getCalculatedValue();
                        $rOkt = (float)$worksheet->getCell('Q' . $r)->getCalculatedValue();
                        $rNop = (float)$worksheet->getCell('R' . $r)->getCalculatedValue();
                        $rDes = (float)$worksheet->getCell('S' . $r)->getCalculatedValue();
                        $sub2 = (float)$worksheet->getCell('T' . $r)->getCalculatedValue();
                        if ($sub2 == 0 && $currentCategory !== 'produktivitas') {
                            $sub2 = $rJul + $rAgs + $rSep + $rOkt + $rNop + $rDes;
                        }
                    }

                    $totalVal = (float)$worksheet->getCell('U' . $r)->getCalculatedValue();
                    if ($totalVal == 0 && $currentCategory !== 'produktivitas') {
                        $totalVal = $sub1 + $sub2;
                    }

                    $pSub = (float)$worksheet->getCell('V' . $r)->getCalculatedValue();
                    $pTot = (float)$worksheet->getCell('W' . $r)->getCalculatedValue();
                    if (is_string($pSub) || is_nan($pSub)) {
                        $pSub = ($t1 > 0 ? round(($sub1 / $t1) * 100, 2) : 0);
                    }
                    if (is_string($pTot) || is_nan($pTot)) {
                        $pTot = ($tTot > 0 ? round(($totalVal / $tTot) * 100, 2) : 0);
                    }

                    $satuan = match($currentCategory) {
                        'tanam', 'panen', 'puso' => 'Ha',
                        'produksi' => 'Ton',
                        'produktivitas' => 'Kw/Ha',
                        default => 'Ha'
                    };

                    $musimTanam = $currentCategory === 'tanam'
                        ? "MT {$prevYear}/{$tahun} dan MT {$tahun}"
                        : "Tahun {$tahun}";

                    $recordsToInsert[] = [
                        'komoditas'          => $komoditas,
                        'kategori'           => $currentCategory,
                        'satuan'             => $satuan,
                        'tahun'              => $tahun,
                        'musim_tanam'        => $musimTanam,
                        'no_urut'            => $noUrut,
                        'kecamatan'          => $kecUpper,
                        'luas_sawah'         => $luasSawah,
                        'target_okmar'       => $t1,
                        'target_asep'        => $t2,
                        'target_total'       => $tTot,
                        'realisasi_okt'      => $rOkt,
                        'realisasi_nop'      => $rNop,
                        'realisasi_des'      => $rDes,
                        'realisasi_jan'      => $rJan,
                        'realisasi_feb'      => $rFeb,
                        'realisasi_mar'      => $rMar,
                        'jumlah_mh'          => $sub1,
                        'realisasi_apr'      => $rApr,
                        'realisasi_mei'      => $rMei,
                        'realisasi_jun'      => $rJun,
                        'realisasi_jul'      => $rJul,
                        'realisasi_ags'      => $rAgs,
                        'realisasi_sep'      => $rSep,
                        'jumlah_mk'          => $sub2,
                        'total_realisasi'    => $totalVal,
                        'persentase_sub'     => $pSub,
                        'persentase_capaian' => $pTot,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ];

                    $kategoriCounts[$currentCategory] = ($kategoriCounts[$currentCategory] ?? 0) + 1;
                }
            }

            if (!in_array($komoditas, $processedSheets)) {
                $processedSheets[] = $komoditas;
            }
        }

        if (empty($recordsToInsert)) {
            return [
                'success' => false,
                'message' => 'Format file tidak sesuai atau tidak ada data komoditas yang dapat dibaca.',
                'totalRows' => 0,
                'sheets' => [],
                'kategoriCounts' => [],
            ];
        }

        // Ganti seluruh data komoditas untuk tahun terpilih
        DB::transaction(function () use ($tahun, $processedSheets, $recordsToInsert) {
            PadiPalawija::where('tahun', $tahun)
                ->whereIn('komoditas', $processedSheets)
                ->delete();

            foreach (array_chunk($recordsToInsert, 100) as $chunk) {
                PadiPalawija::insert($chunk);
            }

            // Otomatis daftarkan seluruh komoditas yang diimpor ke Master Komoditas
            Komoditas::syncFromImport('tanaman_pangan', $processedSheets, [
                'satuan_luas' => 'Ha',
                'satuan_produksi' => 'Ton',
            ]);
        });

        return [
            'success'        => true,
            'message'        => "Berhasil memperbarui data Tahun {$tahun} untuk " . count($processedSheets) . " komoditas.",
            'totalRows'      => count($recordsToInsert),
            'sheets'         => $processedSheets,
            'kategoriCounts' => $kategoriCounts,
        ];
    }
}
