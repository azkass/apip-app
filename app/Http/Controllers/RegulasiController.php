<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regulasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RegulasiController extends Controller
{
    public function index(Request $request)
    {
        $jenisPeraturanFilter = $request->get("jenis_peraturan");
        $statusFilter = $request->get("status");
        $search = $request->get("search");

        // Get regulasi based on filters
        if ($search) {
            $regulasi = Regulasi::search($search);
        } elseif ($jenisPeraturanFilter && $statusFilter) {
            $regulasi = Regulasi::getByJenisPeraturanAndStatus(
                $jenisPeraturanFilter,
                $statusFilter,
            );
        } elseif ($jenisPeraturanFilter) {
            $regulasi = Regulasi::getByJenisPeraturan($jenisPeraturanFilter);
        } elseif ($statusFilter) {
            $regulasi = Regulasi::getByStatus($statusFilter);
        } else {
            $regulasi = Regulasi::getAll();
        }

        // Get filter options
        $jenisPeraturanOptions = [
            "peraturan_bps" => "Peraturan BPS",
            "peraturan_kepala_bps" => "Peraturan Kepala BPS",
            "surat_edaran_kepala_bps" => "Surat Edaran Kepala BPS",
            "keputusan_kepala_bps" => "Keputusan Kepala BPS",
            "surat_edaran_irtama_bps" => "Surat Edaran Irtama BPS",
            "keputusan_irtama_bps" => "Keputusan Irtama BPS",
        ];

        $statusOptions = [
            "berlaku" => "Berlaku",
            "tidak_berlaku" => "Tidak Berlaku",
        ];

        return view("regulasi.daftarregulasi", [
            "regulasi" => $regulasi,
            "title" => "Regulasi",
            "jenisPeraturanOptions" => $jenisPeraturanOptions,
            "statusOptions" => $statusOptions,
            "currentJenisPeraturan" => $jenisPeraturanFilter,
            "currentStatus" => $statusFilter,
            "currentSearch" => $search,
        ]);
    }

    public function detail($id)
    {
        $regulasi = Regulasi::detail($id);
        return view("regulasi.detailregulasi", [
            "regulasi" => $regulasi,
            "title" => "Detail Regulasi",
        ]);
    }

    public function view($id)
    {
        $regulasi = Regulasi::findPdf($id);

        if (!$regulasi || !$regulasi->file) {
            abort(404, "File tidak ditemukan");
        }

        $filePath = "regulasi/" . $regulasi->file;

        if (!Storage::exists($filePath)) {
            abort(404, "File tidak ditemukan di storage");
        }

        $path = storage_path("app/private/" . $filePath);

        return response()->file($path, [
            "Content-Type" => "application/pdf",
            "Content-Disposition" =>
                'inline; filename="' . $regulasi->file . '"',
        ]);
    }

    public function create()
    {
        return view("regulasi.createregulasi", [
            "title" => "Tambah Regulasi",
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "tahun" => "required|string|max:255",
            "nomor" => "required|string|max:255",
            "tentang" => "required|string|max:1000",
            "jenis_peraturan" =>
                "required|in:peraturan_bps,peraturan_kepala_bps,surat_edaran_kepala_bps,keputusan_kepala_bps,surat_edaran_irtama_bps,keputusan_irtama_bps",
            "status" => "required|in:berlaku,tidak_berlaku",
            "tautan" => "nullable|url|max:500",
            "file" => "nullable|file|mimes:pdf|max:10240",
        ]);

        // Handle file upload
        $fileName = null;
        if ($request->hasFile("file") && $request->file("file")->isValid()) {
            $file = $request->file("file");
            $fileName = time() . "_" . $file->getClientOriginalName();

            // Simpan file ke storage/app/regulasi
            $file->storeAs("regulasi", $fileName);
        }

        // Prepare data for insertion
        $data = [
            "tahun" => $request->tahun,
            "nomor" => $request->nomor,
            "tentang" => $request->tentang,
            "jenis_peraturan" => $request->jenis_peraturan,
            "status" => $request->status,
            "tautan" => $request->tautan,
            "file" => $fileName,
            "pembuat_id" => Auth::id(),
        ];

        Regulasi::create($data);

        return redirect()
            ->route("regulasi.index")
            ->with("success", "Regulasi berhasil ditambahkan");
    }

    public function downloadPdf($id)
    {
        $regulasi = Regulasi::find($id);

        if (!$regulasi || !$regulasi->file) {
            abort(404, "File tidak ditemukan");
        }

        $filePath = "regulasi/" . $regulasi->file;

        if (!Storage::exists($filePath)) {
            abort(404, "File tidak ditemukan di storage");
        }

        return Storage::download($filePath, $regulasi->file);
    }

    public function edit($id)
    {
        $regulasi = Regulasi::find($id);

        if (!$regulasi) {
            return redirect()
                ->route("regulasi.index")
                ->with("error", "Regulasi tidak ditemukan");
        }

        return view("regulasi.editregulasi", [
            "regulasi" => $regulasi,
            "title" => "Edit Regulasi",
        ]);
    }

    public function update(Request $request, $id)
    {
        $regulasi = Regulasi::find($id);
        if (!$regulasi) {
            return redirect()
                ->back()
                ->with("error", "Regulasi tidak ditemukan");
        }

        $validationRules = [
            "tahun" => "required|string|max:255",
            "nomor" => "required|string|max:255",
            "tentang" => "required|string|max:1000",
            "jenis_peraturan" =>
                "required|in:peraturan_bps,peraturan_kepala_bps,surat_edaran_kepala_bps,keputusan_kepala_bps,surat_edaran_irtama_bps,keputusan_irtama_bps",
            "status" => "required|in:berlaku,tidak_berlaku",
            "tautan" => "nullable|url|max:500",
            "pembuat_id" => "required|exists:users,id",
        ];

        // File PDF bersifat opsional pada update
        if ($request->hasFile("file")) {
            $validationRules["file"] = "file|mimes:pdf|max:10240";
        }

        $request->validate($validationRules);

        // Handle file upload jika ada
        $fileName = $regulasi->file; // Gunakan file yang sudah ada sebagai default
        if ($request->hasFile("file") && $request->file("file")->isValid()) {
            $file = $request->file("file");
            $fileName = time() . "_" . $file->getClientOriginalName();

            // Simpan file baru ke storage/app/regulasi
            $file->storeAs("regulasi", $fileName);

            // Hapus file lama jika ada
            if (
                $regulasi->file &&
                Storage::exists("regulasi/" . $regulasi->file)
            ) {
                Storage::delete("regulasi/" . $regulasi->file);
            }
        }

        // Prepare data for update
        $data = [
            "tahun" => $request->tahun,
            "nomor" => $request->nomor,
            "tentang" => $request->tentang,
            "jenis_peraturan" => $request->jenis_peraturan,
            "status" => $request->status,
            "tautan" => $request->tautan,
            "file" => $fileName,
            "pembuat_id" => $request->pembuat_id,
        ];

        Regulasi::update($id, $data);

        return redirect()
            ->route("regulasi.detail", $id)
            ->with("success", "Regulasi berhasil diubah");
    }

    public function delete($id)
    {
        $regulasi = Regulasi::find($id);

        if (!$regulasi) {
            return redirect()
                ->route("regulasi.index")
                ->with("error", "Regulasi tidak ditemukan");
        }

        // Hapus file jika ada
        if ($regulasi->file && Storage::exists("regulasi/" . $regulasi->file)) {
            Storage::delete("regulasi/" . $regulasi->file);
        }

        $status = Regulasi::delete($id);

        if ($status) {
            return redirect()
                ->route("regulasi.index")
                ->with("success", "Regulasi berhasil dihapus");
        } else {
            return redirect()
                ->route("regulasi.index")
                ->with("error", "Regulasi gagal dihapus");
        }
    }
}
