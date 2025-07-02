<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regulasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RegulasiController extends Controller
{
    public function index()
    {
        $regulasi = Regulasi::getAll();
        return view("perencana.regulasi.daftarregulasi", [
            "regulasi" => $regulasi,
            "title" => "Regulasi Pengawasan",
        ]);
    }

    public function detail($id)
    {
        $regulasi = Regulasi::detail($id);
        return view("perencana.regulasi.detailregulasi", [
            "regulasi" => $regulasi,
            "title" => "Detail Regulasi",
        ]);
    }

    public function create()
    {
        return view("perencana.regulasi.createregulasi", [
            "title" => "Tambah Regulasi",
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "judul" => "required|string|max:255",
            "tautan" => "required|string",
            "pdf" => "required|file|mimes:pdf|max:10240",
            "kode" => "required|string|max:255",
            "hasil_kerja" => "required|string|max:255",
        ]);

        // Handle file upload
        $fileName = null;
        if ($request->hasFile("pdf") && $request->file("pdf")->isValid()) {
            $file = $request->file("pdf");
            $fileName = $file->getClientOriginalName();

            // Simpan file ke storage/app/regulasi
            $file->storeAs("regulasi", $fileName);
        }

        // Merge data dengan pembuat_id dan nama file
        $request->merge([
            "pembuat_id" => Auth::id(),
            "file" => $fileName,
        ]);

        Regulasi::create(
            $request->only(["judul", "tautan", "file", "pembuat_id", "kode", "hasil_kerja"])
        );

        return redirect()
            ->route("perencana.regulasi.index")
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
        return view("perencana.regulasi.editregulasi", [
            "regulasi" => $regulasi,
            "title" => "Edit Regulasi",
        ]);
    }

    public function update(Request $request, $id)
    {
        $regulasi = Regulasi::detail($id);
        if (!$regulasi) {
            return redirect()
                ->back()
                ->with("error", "Regulasi tidak ditemukan");
        }

        $validationRules = [
            "judul" => "required|string|max:255",
            "tautan" => "required|string",
            "pembuat_id" => "required|exists:users,id",
            "kode" => "required|string|max:255",
            "hasil_kerja" => "required|string|max:255",
        ];

        // File PDF bersifat opsional pada update
        if ($request->hasFile("pdf")) {
            $validationRules["pdf"] = "file|mimes:pdf|max:10240";
        }

        $request->validate($validationRules);

        // Handle file upload jika ada
        $fileName = $regulasi->file; // Gunakan file yang sudah ada sebagai default
        if ($request->hasFile("pdf") && $request->file("pdf")->isValid()) {
            $file = $request->file("pdf");
            $fileName = $file->getClientOriginalName();
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

        // Update data dengan file yang sesuai (baru atau tetap yang lama)
        $data = $request->only(["judul", "tautan", "status", "pembuat_id", "kode", "hasil_kerja"]);
        $data["file"] = $fileName;

        Regulasi::update($id, $data);

        // Ambil data yang sudah diupdate
        $updatedRegulasi = Regulasi::detail($id);
        // $regulasi = Regulasi::detail($id);
        return redirect()->route(
            "perencana.regulasi.detail",
            $updatedRegulasi->id
        );
    }

    public function delete($id)
    {
        $status = Regulasi::delete($id);
        if ($status) {
            return redirect()
                ->route("perencana.regulasi.index")
                ->with("success", "Regulasi berhasil dihapus");
        } else {
            return redirect()
                ->route("perencana.regulasi.index")
                ->with("error", "Regulasi gagal dihapus");
        }
    }
}
