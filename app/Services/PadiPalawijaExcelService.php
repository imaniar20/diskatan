<?php

namespace App\Services;

use App\Models\PadiPalawija;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
     * Generate file Excel (XLSX) berisi 7 sheet untuk tahun tertentu
     */
    public function export(string $tahun): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // Buang sheet default

        foreach (self::DEFAULT_SHEETS as $sheetIndex => $cfg) {
            $sheetKey = $cfg['sheet'];
            $komoditasName = $cfg['name'];

            $worksheet = $spreadsheet->createSheet($sheetIndex);
            $worksheet->setTitle($sheetKey);

            // Ambil data dari database untuk tahun & komoditas ini
            $dbItems = PadiPalawija::where('tahun', $tahun)
                ->where('komoditas', $komoditasName)
                ->get()
                ->keyBy(function ($item) {
                    return strtoupper(trim($item->kecamatan));
                });

            // Judul Header
            $worksheet->mergeCells('A1:V1');
            $worksheet->setCellValue('A1', "TARGET DAN REALISASI TANAM " . strtoupper($komoditasName) . " TAHUN {$tahun}");
            $worksheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);
            $worksheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $worksheet->mergeCells('A2:V2');
            $worksheet->setCellValue('A2', "DINAS KETAHANAN PANGAN DAN PERTANIAN KABUPATEN KUNINGAN");
            $worksheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
            $worksheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $prevYear = (int)$tahun - 1;
            $worksheet->mergeCells('A3:V3');
            $worksheet->setCellValue('A3', "Musim Tanam MT {$prevYear}/{$tahun} dan MT {$tahun}");
            $worksheet->getStyle('A3')->getFont()->setItalic(true)->setSize(10);
            $worksheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Baris Header Kolom (Baris 5 - 6)
            $worksheet->mergeCells('A5:A6')->setCellValue('A5', 'NO');
            $worksheet->mergeCells('B5:B6')->setCellValue('B5', 'KECAMATAN');
            $worksheet->mergeCells('C5:C6')->setCellValue('C5', 'LUAS SAWAH');
            
            $worksheet->mergeCells('D5:F5')->setCellValue('D5', 'TARGET (Ha)');
            $worksheet->setCellValue('D6', 'OKMAR');
            $worksheet->setCellValue('E6', 'ASEP');
            $worksheet->setCellValue('F6', 'JUMLAH');

            $worksheet->mergeCells('G5:M5')->setCellValue('G5', 'REALISASI MUSIM HUJAN (Ha)');
            $worksheet->setCellValue('G6', 'OKT');
            $worksheet->setCellValue('H6', 'NOP');
            $worksheet->setCellValue('I6', 'DES');
            $worksheet->setCellValue('J6', 'JAN');
            $worksheet->setCellValue('K6', 'PEB');
            $worksheet->setCellValue('L6', 'MAR');
            $worksheet->setCellValue('M6', 'JUMLAH MH');

            $worksheet->mergeCells('N5:T5')->setCellValue('N5', 'REALISASI MUSIM KEMARAU (Ha)');
            $worksheet->setCellValue('N6', 'APR');
            $worksheet->setCellValue('O6', 'MEI');
            $worksheet->setCellValue('P6', 'JUNI');
            $worksheet->setCellValue('Q6', 'JULI');
            $worksheet->setCellValue('R6', 'AGUST');
            $worksheet->setCellValue('S6', 'SEPT');
            $worksheet->setCellValue('T6', 'JUMLAH MK');

            $worksheet->mergeCells('U5:U6')->setCellValue('U5', "TOTAL\nREALISASI");
            $worksheet->mergeCells('V5:V6')->setCellValue('V5', "% CAPAIAN");

            // Baris 7: Angka Penomoran Kolom (1 s/d 22)
            $colNumbers = [
                'A' => '1', 'B' => '2', 'C' => '3', 'D' => '4', 'E' => '5',
                'F' => '6 (4+5)', 'G' => '7', 'H' => '8', 'I' => '9', 'J' => '10',
                'K' => '11', 'L' => '12', 'M' => '13 (7..12)', 'N' => '14', 'O' => '15',
                'P' => '16', 'Q' => '17', 'R' => '18', 'S' => '19', 'T' => '20 (14..19)',
                'U' => '21 (13+20)', 'V' => '22 (21/6*100)'
            ];
            foreach ($colNumbers as $col => $numText) {
                $worksheet->setCellValue($col . '7', $numText);
            }

            // Styling Header
            $headerRange = 'A5:V7';
            $worksheet->getStyle($headerRange)->getFont()->setBold(true)->setSize(9);
            $worksheet->getStyle($headerRange)->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setWrapText(true);
            $worksheet->getStyle('A5:V6')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE8F5E9'); // Light green tone
            $worksheet->getStyle('A7:V7')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFF1F5F9'); // Light slate gray

            // Isi Baris Data Kecamatan (Baris 8 s/d 39)
            $startRow = 8;
            $currentRow = $startRow;

            foreach (self::MASTER_KECAMATAN as $kec) {
                $namaKec = $kec['nama'];
                $item = $dbItems->get($namaKec);

                $worksheet->setCellValue('A' . $currentRow, $kec['no']);
                $worksheet->setCellValue('B' . $currentRow, $namaKec);
                $worksheet->setCellValue('C' . $currentRow, $item ? (float)$item->luas_sawah : 0);
                $worksheet->setCellValue('D' . $currentRow, $item ? (float)$item->target_okmar : 0);
                $worksheet->setCellValue('E' . $currentRow, $item ? (float)$item->target_asep : 0);
                $worksheet->setCellValue('F' . $currentRow, "=D{$currentRow}+E{$currentRow}");

                $worksheet->setCellValue('G' . $currentRow, $item ? (float)$item->realisasi_okt : 0);
                $worksheet->setCellValue('H' . $currentRow, $item ? (float)$item->realisasi_nop : 0);
                $worksheet->setCellValue('I' . $currentRow, $item ? (float)$item->realisasi_des : 0);
                $worksheet->setCellValue('J' . $currentRow, $item ? (float)$item->realisasi_jan : 0);
                $worksheet->setCellValue('K' . $currentRow, $item ? (float)$item->realisasi_feb : 0);
                $worksheet->setCellValue('L' . $currentRow, $item ? (float)$item->realisasi_mar : 0);
                $worksheet->setCellValue('M' . $currentRow, "=SUM(G{$currentRow}:L{$currentRow})");

                $worksheet->setCellValue('N' . $currentRow, $item ? (float)$item->realisasi_apr : 0);
                $worksheet->setCellValue('O' . $currentRow, $item ? (float)$item->realisasi_mei : 0);
                $worksheet->setCellValue('P' . $currentRow, $item ? (float)$item->realisasi_jun : 0);
                $worksheet->setCellValue('Q' . $currentRow, $item ? (float)$item->realisasi_jul : 0);
                $worksheet->setCellValue('R' . $currentRow, $item ? (float)$item->realisasi_ags : 0);
                $worksheet->setCellValue('S' . $currentRow, $item ? (float)$item->realisasi_sep : 0);
                $worksheet->setCellValue('T' . $currentRow, "=SUM(N{$currentRow}:S{$currentRow})");

                $worksheet->setCellValue('U' . $currentRow, "=M{$currentRow}+T{$currentRow}");
                $worksheet->setCellValue('V' . $currentRow, "=IF(F{$currentRow}>0, ROUND((U{$currentRow}/F{$currentRow})*100, 2), 0)");

                // Alignment
                $worksheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $worksheet->getStyle('B' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $worksheet->getStyle("C{$currentRow}:V{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $currentRow++;
            }

            $endDataRow = $currentRow - 1;

            // Baris Total / JUMLAH
            $totalRow = $currentRow;
            $worksheet->mergeCells("A{$totalRow}:B{$totalRow}")->setCellValue("A{$totalRow}", 'JUMLAH');
            $worksheet->setCellValue("C{$totalRow}", "=SUM(C{$startRow}:C{$endDataRow})");
            $worksheet->setCellValue("D{$totalRow}", "=SUM(D{$startRow}:D{$endDataRow})");
            $worksheet->setCellValue("E{$totalRow}", "=SUM(E{$startRow}:E{$endDataRow})");
            $worksheet->setCellValue("F{$totalRow}", "=SUM(F{$startRow}:F{$endDataRow})");

            foreach (['G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U'] as $c) {
                $worksheet->setCellValue("{$c}{$totalRow}", "=SUM({$c}{$startRow}:{$c}{$endDataRow})");
            }
            $worksheet->setCellValue("V{$totalRow}", "=IF(F{$totalRow}>0, ROUND((U{$totalRow}/F{$totalRow})*100, 2), 0)");

            $worksheet->getStyle("A{$totalRow}:V{$totalRow}")->getFont()->setBold(true);
            $worksheet->getStyle("A{$totalRow}:B{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $worksheet->getStyle("A{$totalRow}:V{$totalRow}")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFDCFCE7');

            // Format Angka & Border
            $tableRange = "A5:V{$totalRow}";
            $worksheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $worksheet->getStyle("C8:U{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.##');
            $worksheet->getStyle("V8:V{$totalRow}")->getNumberFormat()->setFormatCode('#,##0.00');

            // Set column auto-size
            foreach (range('A', 'V') as $col) {
                $worksheet->getColumnDimension($col)->setAutoSize(true);
            }
        }

        $spreadsheet->setActiveSheetIndex(0);
        return $spreadsheet;
    }

    /**
     * Import file Excel dan gantikan (replace) data untuk tahun yang dipilih
     *
     * @return array [ 'success' => bool, 'message' => string, 'totalRows' => int, 'sheets' => array ]
     */
    public function import(UploadedFile $file, string $tahun): array
    {
        $filePath = $file->getRealPath();
        $spreadsheet = IOFactory::load($filePath);
        $sheetNames = $spreadsheet->getSheetNames();

        $processedSheets = [];
        $recordsToInsert = [];

        foreach ($sheetNames as $rawSheetName) {
            $sheetKey = strtolower(trim($rawSheetName));

            // Periksa apakah nama sheet cocok dengan daftar pemetaan
            $komoditas = null;
            foreach (self::SHEET_MAPPING as $pattern => $mappedName) {
                if ($sheetKey === $pattern || strpos($sheetKey, $pattern) !== false) {
                    $komoditas = $mappedName;
                    break;
                }
            }

            if (!$komoditas) {
                continue; // Bukan sheet komoditas yang dikenali, lewati
            }

            $worksheet = $spreadsheet->getSheetByName($rawSheetName);
            $highestRow = $worksheet->getHighestDataRow();

            $noUrut = 1;
            for ($row = 5; $row <= $highestRow; $row++) {
                $colACell = strtoupper(trim((string) $worksheet->getCell('A' . $row)->getValue()));
                $colBCell = strtoupper(trim((string) $worksheet->getCell('B' . $row)->getValue()));

                // Begitu bertemu baris JUMLAH / TOTAL pada kolom A atau B, tabel Tanam untuk komoditas ini selesai
                if (str_contains($colACell, 'JUMLAH') || str_contains($colBCell, 'JUMLAH') || str_contains($colACell, 'TOTAL') || str_contains($colBCell, 'TOTAL')) {
                    break;
                }

                // Cek nama kecamatan
                if (empty($colBCell) || is_numeric($colBCell) || strlen($colBCell) < 3) {
                    continue;
                }

                if (str_contains($colBCell, 'KECAMATAN') || str_contains($colACell, 'NO')) {
                    continue;
                }

                $kecamatanUpper = $colBCell;

                // Ambil nilai setiap sel
                $luasSawah = (float) $worksheet->getCell('C' . $row)->getCalculatedValue();
                $tOkmar    = (float) $worksheet->getCell('D' . $row)->getCalculatedValue();
                $tAsep     = (float) $worksheet->getCell('E' . $row)->getCalculatedValue();
                $tTotal    = (float) $worksheet->getCell('F' . $row)->getCalculatedValue();
                if ($tTotal == 0 && ($tOkmar > 0 || $tAsep > 0)) {
                    $tTotal = $tOkmar + $tAsep;
                }

                $rOkt = (float) $worksheet->getCell('G' . $row)->getCalculatedValue();
                $rNop = (float) $worksheet->getCell('H' . $row)->getCalculatedValue();
                $rDes = (float) $worksheet->getCell('I' . $row)->getCalculatedValue();
                $rJan = (float) $worksheet->getCell('J' . $row)->getCalculatedValue();
                $rFeb = (float) $worksheet->getCell('K' . $row)->getCalculatedValue();
                $rMar = (float) $worksheet->getCell('L' . $row)->getCalculatedValue();
                $rMH  = (float) $worksheet->getCell('M' . $row)->getCalculatedValue();
                if ($rMH == 0) {
                    $rMH = $rOkt + $rNop + $rDes + $rJan + $rFeb + $rMar;
                }

                $rApr = (float) $worksheet->getCell('N' . $row)->getCalculatedValue();
                $rMei = (float) $worksheet->getCell('O' . $row)->getCalculatedValue();
                $rJun = (float) $worksheet->getCell('P' . $row)->getCalculatedValue();
                $rJul = (float) $worksheet->getCell('Q' . $row)->getCalculatedValue();
                $rAgs = (float) $worksheet->getCell('R' . $row)->getCalculatedValue();
                $rSep = (float) $worksheet->getCell('S' . $row)->getCalculatedValue();
                $rMK  = (float) $worksheet->getCell('T' . $row)->getCalculatedValue();
                if ($rMK == 0) {
                    $rMK = $rApr + $rMei + $rJun + $rJul + $rAgs + $rSep;
                }

                $rTotal = (float) $worksheet->getCell('U' . $row)->getCalculatedValue();
                if ($rTotal == 0) {
                    $rTotal = $rMH + $rMK;
                }

                $persen = $tTotal > 0 ? round(($rTotal / $tTotal) * 100, 2) : 0;

                $prevYear = (int)$tahun - 1;
                $recordsToInsert[] = [
                    'komoditas'          => $komoditas,
                    'tahun'              => $tahun,
                    'musim_tanam'        => "MT {$prevYear}/{$tahun} dan MT {$tahun}",
                    'no_urut'            => $noUrut++,
                    'kecamatan'          => $kecamatanUpper,
                    'luas_sawah'         => $luasSawah,
                    'target_okmar'       => $tOkmar,
                    'target_asep'        => $tAsep,
                    'target_total'       => $tTotal,
                    'realisasi_okt'      => $rOkt,
                    'realisasi_nop'      => $rNop,
                    'realisasi_des'      => $rDes,
                    'realisasi_jan'      => $rJan,
                    'realisasi_feb'      => $rFeb,
                    'realisasi_mar'      => $rMar,
                    'jumlah_mh'          => $rMH,
                    'realisasi_apr'      => $rApr,
                    'realisasi_mei'      => $rMei,
                    'realisasi_jun'      => $rJun,
                    'realisasi_jul'      => $rJul,
                    'realisasi_ags'      => $rAgs,
                    'realisasi_sep'      => $rSep,
                    'jumlah_mk'          => $rMK,
                    'total_realisasi'    => $rTotal,
                    'persentase_capaian' => $persen,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
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
        });

        return [
            'success'   => true,
            'message'   => "Berhasil memperbarui data Tahun {$tahun} untuk komoditas: " . implode(', ', $processedSheets),
            'totalRows' => count($recordsToInsert),
            'sheets'    => $processedSheets,
        ];
    }
}
