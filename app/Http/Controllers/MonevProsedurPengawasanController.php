<?php

namespace App\Http\Controllers;

use App\Models\MonevProsedurPengawasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MonevProsedurPengawasanController extends Controller
{
    public function index()
    {
        $groupedData = MonevProsedurPengawasan::getGroupedData();
        return view("monitoring-evaluasi.index", [
            "groupedData" => $groupedData,
            "title" => "Monitoring Evaluasi Prosedur Pengawasan",
        ]);
    }

    public function show($id)
    {
        $item = MonevProsedurPengawasan::findWithProsedurPengawasan($id);
        if (!$item) {
            abort(404);
        }

        $pertanyaan_map = [
            "mampu_mendorong_kinerja" => "Mampu mendorong peningkatan kinerja",
            "mampu_dipahami" => "Mudah dipahami",
            "mudah_dilaksanakan" => "Mudah dilaksanakan",
            "dapat_menjalankan_peran" =>
                "Semua orang dapat menjalankan perannya masing-masing",
            "mampu_mengatasi_permasalahan" =>
                "Mampu mengatasi permasalahan yang berkaitan dengan proses",
            "mampu_menjawab_kebutuhan" =>
                "Mampu menjawab kebutuhan peningkatan kinerja organisasi",
            "sinergi_dengan_lainnya" => "Sinergi satu dengan lainnya",
        ];

        $evaluasiItems = [];
        $jawabanYa = 0;

        foreach ($pertanyaan_map as $field => $pertanyaan) {
            $jawaban = $item->$field === "ya" ? 1 : 0;
            if ($jawaban === 1) {
                $jawabanYa++;
            }
            $evaluasiItems[] = (object) [
                "pertanyaan" => $pertanyaan,
                "jawaban" => $jawaban,
            ];
        }

        $totalPertanyaan = count($pertanyaan_map);
        $persentase =
            $totalPertanyaan > 0 ? ($jawabanYa / $totalPertanyaan) * 100 : 0;

        return view("monitoring-evaluasi.show", [
            "item" => $item,
            "evaluasiItems" => $evaluasiItems,
            "totalPertanyaan" => $totalPertanyaan,
            "jawabanYa" => $jawabanYa,
            "persentase" => $persentase,
            "sop_id" => $item->sop_id,
            "sop_nomor" => $item->sop_nomor,
            "sop_nama" => $item->sop_nama,
            "penilaian" => $item->penilaian_penerapan,
            "catatan" => $item->catatan_penilaian,
            "tindakan" => $item->tindakan,
            "title" => "Detail Monitoring Evaluasi",
        ]);
    }

    public function create($sop_id)
    {
        // Validasi input ID sebagai integer
        if (!is_numeric($sop_id)) {
            abort(400, "ID SOP tidak valid");
        }

        // Check if evaluasi already exists for this SOP
        if (MonevProsedurPengawasan::monevExists($sop_id)) {
            $result = MonevProsedurPengawasan::findWithProsedurPengawasanBySopId(
                $sop_id,
            );

            // Akses properti id dari objek
            $id = $result->id;
            return redirect()
                ->route("monitoring-evaluasi.show", $id)
                ->with("error", "Evaluasi untuk SOP ini sudah ada!");
        }

        // Gunakan prepared statement untuk keamanan
        $sop = DB::selectOne(
            "SELECT id, nama FROM prosedur_pengawasan WHERE id = :id LIMIT 1",
            ["id" => $sop_id],
        );

        // Jika SOP tidak ditemukan
        if (!$sop) {
            abort(404, "SOP tidak ditemukan");
        }

        return view("monitoring-evaluasi.create", [
            "sop_id" => $sop->id,
            "sop_nama" => $sop->nama,
            "title" => "Monitoring Evaluasi Prosedur Pengawasan",
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "sop_id" => "required|exists:prosedur_pengawasan,id",
            "penilaian_penerapan" => "required|string",
            "catatan_penilaian" => "required|string",
            "tindakan" => "required|string",
            "mampu_mendorong_kinerja" => "required|in:ya,tidak",
            "mampu_dipahami" => "required|in:ya,tidak",
            "mudah_dilaksanakan" => "required|in:ya,tidak",
            "dapat_menjalankan_peran" => "required|in:ya,tidak",
            "mampu_mengatasi_permasalahan" => "required|in:ya,tidak",
            "mampu_menjawab_kebutuhan" => "required|in:ya,tidak",
            "sinergi_dengan_lainnya" => "required|in:ya,tidak",
        ]);

        $id = MonevProsedurPengawasan::create($validated);

        return redirect()
            ->route("monitoring-evaluasi.index")
            ->with("success", "Data berhasil ditambahkan!");
    }

    public function edit($id)
    {
        $item = MonevProsedurPengawasan::findWithProsedurPengawasan($id);
        if (!$item) {
            abort(404);
        }

        $pertanyaan_map = [
            "mampu_mendorong_kinerja" => "Mampu mendorong peningkatan kinerja",
            "mampu_dipahami" => "Mudah dipahami",
            "mudah_dilaksanakan" => "Mudah dilaksanakan",
            "dapat_menjalankan_peran" =>
                "Semua orang dapat menjalankan perannya masing-masing",
            "mampu_mengatasi_permasalahan" =>
                "Mampu mengatasi permasalahan yang berkaitan dengan proses",
            "mampu_menjawab_kebutuhan" =>
                "Mampu menjawab kebutuhan peningkatan kinerja organisasi",
            "sinergi_dengan_lainnya" => "Sinergi satu dengan lainnya",
        ];

        return view("monitoring-evaluasi.edit", [
            "item" => $item,
            "pertanyaan_map" => $pertanyaan_map,
            "sop_nomor" => $item->sop_nomor,
            "sop_nama" => $item->sop_nama,
            "title" => "Edit Monitoring Evaluasi",
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = MonevProsedurPengawasan::find($id);
        if (!$item) {
            abort(404);
        }

        $validated = $request->validate([
            "sop_id" => "sometimes|exists:prosedur_pengawasan,id",
            "penilaian_penerapan" => "sometimes|string",
            "catatan_penilaian" => "sometimes|string",
            "tindakan" => "sometimes|string",
            "mampu_mendorong_kinerja" => "sometimes|in:ya,tidak",
            "mampu_dipahami" => "sometimes|in:ya,tidak",
            "mudah_dilaksanakan" => "sometimes|in:ya,tidak",
            "dapat_menjalankan_peran" => "sometimes|in:ya,tidak",
            "mampu_mengatasi_permasalahan" => "sometimes|in:ya,tidak",
            "mampu_menjawab_kebutuhan" => "sometimes|in:ya,tidak",
            "sinergi_dengan_lainnya" => "sometimes|in:ya,tidak",
        ]);

        MonevProsedurPengawasan::update($id, $validated);

        return redirect()
            ->route("monitoring-evaluasi.index")
            ->with("success", "Data berhasil diubah!");
    }

    public function destroy($id)
    {
        $item = MonevProsedurPengawasan::find($id);
        if (!$item) {
            abort(404);
        }

        MonevProsedurPengawasan::delete($id);

        return redirect()
            ->route("monitoring-evaluasi.index")
            ->with("success", "Data berhasil dihapus!");
    }

    public function downloadMonev($id)
    {
        $item = MonevProsedurPengawasan::findWithProsedurPengawasan($id);
        if (!$item) {
            abort(404);
        }

        $pertanyaan_map = [
            "mampu_mendorong_kinerja" => "Mampu mendorong peningkatan kinerja",
            "mampu_dipahami" => "Mudah dipahami",
            "mudah_dilaksanakan" => "Mudah dilaksanakan",
            "dapat_menjalankan_peran" =>
                "Semua orang dapat menjalankan perannya masing-masing",
            "mampu_mengatasi_permasalahan" =>
                "Mampu mengatasi permasalahan yang berkaitan dengan proses",
            "mampu_menjawab_kebutuhan" =>
                "Mampu menjawab kebutuhan peningkatan kinerja organisasi",
            "sinergi_dengan_lainnya" => "Sinergi satu dengan lainnya",
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue("B2", "Tim : ");
        $sheet->setCellValue("C4", "Monitoring Pelaksanaan SOP AP");

        $sheet->setCellValue("B6", "No");
        $sheet->setCellValue("B7", "1");
        $sheet->setCellValue("C6", "Prosedur");
        $sheet->setCellValue("C7", $item->sop_nama);
        $sheet->setCellValue("D6", "Penilaian Terhadap Penerapan");
        $sheet->setCellValue("D7", $item->penilaian_penerapan);
        $sheet->setCellValue("E6", "Catatan Hasil Penilaian");
        $sheet->setCellValue("E7", $item->catatan_penilaian);
        $sheet->setCellValue("F6", "Tindakan yang Harus Diambil");
        $sheet->setCellValue("F7", $item->tindakan);

        $sheet->setCellValue("C10", "Evaluasi Penerapan SOP AP");
        $sheet->setCellValue("B12", "No");
        $sheet->setCellValue("C12", "Pertanyaan");
        $sheet->setCellValue("D12", "Jawaban");

        $row = 13;
        $index = 1;
        foreach ($pertanyaan_map as $field => $pertanyaan) {
            $sheet->setCellValue("B" . $row, $index);
            $sheet->setCellValue("C" . $row, $pertanyaan);
            $sheet->setCellValue(
                "D" . $row,
                $item->$field === "ya" ? "Ya" : "Tidak",
            );
            $row++;
            $index++;
        }

        // Border
        $borderStyle = [
            "borders" => [
                "allBorders" => [
                    "borderStyle" =>
                        \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle("B6:F7")->applyFromArray($borderStyle);
        $sheet->getStyle("B12:D19")->applyFromArray($borderStyle);

        // Apply style: center alignment & gray background to B6:F6 and B12:D12
        $ranges = ["B6:F6", "B12:D12"];
        foreach ($ranges as $rng) {
            $sheet
                ->getStyle($rng)
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet
                ->getStyle($rng)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB("FFD9D9D9"); // light gray
        }

        // Apply vertical center alignment to specific cells
        foreach (["B7", "C7", "D7", "F7"] as $cell) {
            $sheet
                ->getStyle($cell)
                ->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER);
        }
        // E7 vertical top and wrap text
        $sheet
            ->getStyle("E7")
            ->getAlignment()
            ->setVertical(Alignment::VERTICAL_TOP)
            ->setWrapText(true);
        $sheet->getStyle("C7")->getAlignment()->setWrapText(true);

        // Center horizontally D13:D19
        $sheet
            ->getStyle("D13:D19")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Atur lebar kolom Excel‐units
        $sheet->getColumnDimension("B")->setWidth(5);
        $sheet
            ->getStyle("B")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        // Ubah beberapa kolom sekaligus
        foreach (["C", "D", "E", "F"] as $col) {
            $sheet->getColumnDimension($col)->setWidth(40);
        }

        // Baris 7 lebih tinggi
        $sheet->getRowDimension(7)->setRowHeight(30);

        // Mengatur beberapa baris
        // for ($r = 12; $r <= 20; $r++) {
        //     $sheet->getRowDimension($r)->setRowHeight(22);
        // }

        $fileName =
            "monev_" . str_replace(["/", "\\"], "-", $item->sop_nama) . ".xlsx";
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save("php://output");
            },
            $fileName,
            [
                "Content-Type" =>
                    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            ],
        );
    }
}
