<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProsedurPengawasan;
use App\Models\InspekturUtama;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProsedurPengawasanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query("status", "semua");
        $activeTab = $status;
        $prosedurPengawasan = ProsedurPengawasan::getByStatus($status);

        if (Auth::user()->role == "pegawai") {
            $prosedurPengawasan = array_filter(
                $prosedurPengawasan,
                fn($i) => $i->status == "disetujui",
            );
        }

        $viewData = [
            "title" => "Prosedur Pengawasan",
            "activeTab" => $activeTab,
            "prosedurPengawasan" => $prosedurPengawasan,
        ];

        return view("prosedur.index", $viewData);
    }
    public function create()
    {
        $is_pjk = DB::select(
            'SELECT id, name, role FROM users WHERE role IN ("pjk", "operator")',
        ); // Ambil data user untuk dropdown
        $is_perencana = DB::select(
            'SELECT id, name, role FROM users WHERE role IN ("perencana")',
        ); // Ambil data user untuk dropdown

        $inspektur_utama = InspekturUtama::getNama();

        return view("prosedur.create", [
            "is_pjk" => $is_pjk,
            "is_perencana" => $is_perencana,
            "inspektur_utama" => $inspektur_utama,
            "title" => "Tambah Prosedur Pengawasan",
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "nama" => "required|max:255",
            "nomor" => "required|max:255",
            "status" => [
                "required",
                "in:draft,diajukan,revisi,menunggu_disetujui,disetujui",
            ],
            "pembuat_id" => "required|exists:users,id",
            "penyusun_id" => "required|exists:users,id",
            "tanggal_pembuatan" => "nullable|date",
            "tanggal_revisi" => "nullable|date",
            "tanggal_efektif" => "nullable|date",
            "disahkan_oleh" => "required|exists:inspektur_utama,id",
        ]);

        ProsedurPengawasan::create($validatedData);

        return redirect()
            ->route("prosedur-pengawasan.index")
            ->with("success", "Prosedur Pengawasan berhasil ditambahkan.");
    }

    public function show($id)
    {
        $prosedurPengawasan = ProsedurPengawasan::show($id);
        return view("prosedur.show", [
            "prosedurPengawasan" => $prosedurPengawasan,
            "title" => $prosedurPengawasan->nama,
        ]);
    }

    public function edit($id)
    {
        $prosedurPengawasan = ProsedurPengawasan::findHeader($id);
        $is_pjk = DB::select(
            'SELECT id, name, role FROM users WHERE role IN ("pjk", "operator")',
        ); // Ambil data user untuk dropdown

        $inspektur_utama_nama = InspekturUtama::getNama();

        return view("prosedur.edit", [
            "prosedurPengawasan" => $prosedurPengawasan,
            "is_pjk" => $is_pjk,
            "inspektur_utama_nama" => $inspektur_utama_nama,
            "title" => "Prosedur Pengawasan",
        ]);
    }

    public function update(Request $request, $id)
    {
        $prosedurPengawasan = ProsedurPengawasan::findHeader($id);

        $validatedData = $request->validate([
            "nama" => "required|max:255",
            "nomor" => "required|max:255",
            "status" => [
                "required",
                "in:draft,diajukan,revisi,menunggu_disetujui,disetujui",
            ],
            "pembuat_id" => "required|exists:users,id",
            "penyusun_id" => "required|exists:users,id",
            "tanggal_pembuatan" => "nullable|date",
            "tanggal_revisi" => "nullable|date",
            "tanggal_efektif" => "nullable|date",
            "disahkan_oleh" => "required|exists:inspektur_utama,id",
        ]);

        ProsedurPengawasan::update($id, $request);

        $updatedProsedurPengawasan = ProsedurPengawasan::findHeader($id);

        if (Auth::user()->role == "pjk") {
            return redirect()
                ->route(
                    "prosedur-pengawasan.edit-cover",
                    $updatedProsedurPengawasan->id,
                )
                ->with("success", "Data berhasil disimpan");
        } else {
            return redirect()
                ->route(
                    "prosedur-pengawasan.show",
                    $updatedProsedurPengawasan->id,
                )
                ->with("success", "Data berhasil disimpan");
        }
    }

    public function editCover($id)
    {
        $prosedurPengawasan = ProsedurPengawasan::findCover($id);
        // Pastikan field cover di-decode ke array
        if ($prosedurPengawasan) {
            // Ambil dari kolom cover jika field-field tidak ada
            $cover = json_decode($prosedurPengawasan->cover ?? "{}", true);
            $prosedurPengawasan->dasar_hukum =
                $cover["dasar_hukum"] ?? ($cover["dasarHukum"] ?? []);
            $prosedurPengawasan->keterkaitan = $cover["keterkaitan"] ?? [];
            $prosedurPengawasan->peringatan = $cover["peringatan"] ?? [];
            $prosedurPengawasan->kualifikasi = $cover["kualifikasi"] ?? [];
            $prosedurPengawasan->peralatan = $cover["peralatan"] ?? [];
            $prosedurPengawasan->pencatatan = $cover["pencatatan"] ?? [];
        }
        return view("prosedur.edit-cover", [
            "prosedurPengawasan" => $prosedurPengawasan,
            "title" => "Prosedur Pengawasan",
        ]);
    }

    public function getCoverData($id)
    {
        $prosedur = ProsedurPengawasan::findCover($id);
        if (!$prosedur) {
            return response()->json(["error" => "Not found"], 404);
        }
        // Ambil cover JSON jika ada, pastikan array
        $cover = [];
        if (!empty($prosedur->cover)) {
            $decoded = json_decode($prosedur->cover, true);
            if (is_array($decoded)) {
                $cover = $decoded;
            }
        }
        // Mapping field statis dan dinamis
        $static = [
            "nomor_sop" => $prosedur->nomor ?? "",
            "tanggal_pembuatan" => $prosedur->tanggal_pembuatan ?? "",
            "tanggal_revisi" => $prosedur->tanggal_revisi ?? "",
            "tanggal_efektif" => $prosedur->tanggal_efektif ?? "",
            "disahkan_oleh" => $prosedur->disahkan_oleh_nama ?? "",
            "disahkan_oleh_nip" => $prosedur->disahkan_oleh_nip ?? "",
            "disahkan_oleh_jabatan" => $prosedur->disahkan_oleh_jabatan ?? "",
            "nama_sop" => $prosedur->nama ?? "",
            "pejabat_nama" => $prosedur->petugas_nama ?? "",
        ];
        $dynamic = [
            "dasarHukum",
            "keterkaitan",
            "peringatan",
            "kualifikasi",
            "peralatan",
            "pencatatan",
        ];
        foreach ($dynamic as $key) {
            $static[$key] = $cover[$key] ?? [];
        }
        return response()->json($static);
    }
    public function updateCover(Request $request, $id)
    {
        $validatedData = $request->validate([
            "cover" => "required|json",
        ]);

        ProsedurPengawasan::updateCover($id, $validatedData);

        // Re-fetch the updated model to get all data
        $prosedur = ProsedurPengawasan::findCover($id);
        if (!$prosedur) {
            return response()->json(["error" => "Not found after update"], 404);
        }

        $cover = [];
        if (!empty($prosedur->cover)) {
            $decoded = json_decode($prosedur->cover, true);
            if (is_array($decoded)) {
                $cover = $decoded;
            }
        }

        // Prepare the data structure exactly like getCoverData
        $responseData = [
            "nomor_sop" => $prosedur->nomor ?? "",
            "tanggal_pembuatan" => $prosedur->tanggal_pembuatan ?? "",
            "tanggal_revisi" => $prosedur->tanggal_revisi ?? "",
            "tanggal_efektif" => $prosedur->tanggal_efektif ?? "",
            "disahkan_oleh" => $prosedur->disahkan_oleh_nama ?? "",
            "disahkan_oleh_nip" => $prosedur->disahkan_oleh_nip ?? "",
            "disahkan_oleh_jabatan" => $prosedur->disahkan_oleh_jabatan ?? "",
            "nama_sop" => $prosedur->judul ?? "",
            "pejabat_nama" => $prosedur->petugas_nama ?? "",
        ];

        // These are the keys the JS expects for the dynamic fields
        $dynamicKeys = [
            "dasarHukum",
            "keterkaitan",
            "peringatan",
            "kualifikasi",
            "peralatan",
            "pencatatan",
        ];

        foreach ($dynamicKeys as $camelCaseKey) {
            // Convert camelCase to snake_case for fallback lookup
            $snakeCaseKey = strtolower(
                preg_replace("/([a-z])([A-Z])/", '$1_$2', $camelCaseKey),
            );
            // Check for camelCase key first, then snake_case, then default to empty array
            $responseData[$camelCaseKey] =
                $cover[$camelCaseKey] ?? ($cover[$snakeCaseKey] ?? []);
        }

        return response()->json($responseData);
    }

    public function editBody($id)
    {
        $prosedurPengawasan = ProsedurPengawasan::findBody($id);

        return view("prosedur.edit-body", [
            "prosedurPengawasan" => $prosedurPengawasan,
            "title" => "Prosedur Pengawasan",
        ]);
    }

    public function updateBody(Request $request, $id)
    {
        $validatedData = $request->validate([
            "isi" => "required|json",
        ]);

        ProsedurPengawasan::updateBody($id, $validatedData);

        return response()->json([
            "success" => true,
            "message" => "Data berhasil disimpan",
        ]);
    }

    public function uploadTtd($id)
    {
        $prosedurPengawasan = ProsedurPengawasan::findById($id);

        return view("prosedur.upload-ttd", [
            "prosedurPengawasan" => $prosedurPengawasan,
            "title" => "Prosedur Pengawasan",
        ]);
    }

    public function storeTtd(Request $request, $id)
    {
        $validated = $request->validate([
            "file" => "required|mimes:pdf|max:2048",
        ]);

        // Dapatkan data SOP untuk membuat nama file
        $prosedur = ProsedurPengawasan::findById($id);
        // Ganti karakter ilegal pada nama file
        $sanitizePattern = '/[\\\\\/\:\*\?\"\<\>\|]/';
        $nomor = preg_replace($sanitizePattern, "-", $prosedur->nomor);
        $nama = preg_replace($sanitizePattern, "-", $prosedur->nama);

        $filename = $nomor . ", " . $nama . " - signed.pdf";

        // Simpan file ke storage/app/prosedur
        $request->file("file")->storeAs("prosedur", $filename);

        // Hanya simpan nama file di database (kolom file_ttd)
        ProsedurPengawasan::updateTtd($id, ["file_ttd" => $filename]);

        return redirect()
            ->route("prosedur-pengawasan.show", $id)
            ->with("success", "Dokumen Prosedur Pengawasan berhasil diunggah.");
    }

    public function downloadTtd($id)
    {
        $prosedur = ProsedurPengawasan::findById($id);
        $filename = $prosedur->file_ttd ?? null;
        if (!$filename) {
            return abort(404);
        }

        $primaryPath = "prosedur/" . $filename;
        $fallbackPath = "private/prosedur/" . $filename; // path lama (sebelum migrasi)

        $chosenPath = null;
        if (\Illuminate\Support\Facades\Storage::exists($primaryPath)) {
            $chosenPath = $primaryPath;
        } elseif (\Illuminate\Support\Facades\Storage::exists($fallbackPath)) {
            $chosenPath = $fallbackPath;
        } else {
            return abort(404, "File tidak ditemukan");
        }

        $fullPath = storage_path("app/private/" . $chosenPath);

        // Jika file ternyata tidak ada (mis-match Storage::exists), coba fallback sekali lagi
        if (!file_exists($fullPath)) {
            $altPath =
                $chosenPath === $primaryPath ? $fallbackPath : $primaryPath;
            if (\Illuminate\Support\Facades\Storage::exists($altPath)) {
                $fullPath = storage_path("app/" . $altPath);
            }
        }

        return response()->file($fullPath, [
            "Content-Type" => "application/pdf",
            "Content-Disposition" => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function downloadTtdFile($id)
    {
        $prosedur = ProsedurPengawasan::findById($id);
        $filename = $prosedur->file_ttd ?? ($prosedur->ttd ?? null);
        if (!$filename) {
            return abort(404);
        }

        $primaryPath = "prosedur/" . $filename;
        $fallbackPath = "private/prosedur/" . $filename;

        $chosenPath = null;
        if (\Illuminate\Support\Facades\Storage::exists($primaryPath)) {
            $chosenPath = $primaryPath;
        } elseif (\Illuminate\Support\Facades\Storage::exists($fallbackPath)) {
            $chosenPath = $fallbackPath;
        } else {
            return abort(404, "File tidak ditemukan");
        }

        return \Illuminate\Support\Facades\Storage::download(
            $chosenPath,
            $filename,
        );
    }

    public function delete($id)
    {
        ProsedurPengawasan::delete($id);
        return redirect()
            ->route("prosedur-pengawasan.index")
            ->with("success", "Prosedur Pengawasan deleted successfully");
    }
}
