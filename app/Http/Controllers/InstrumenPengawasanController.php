<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstrumenPengawasan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InstrumenPengawasanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query("status", "semua");
        $instrumenPengawasan = InstrumenPengawasan::getByStatus($status);

        // Filter untuk role pegawai - hanya tampilkan yang disetujui
        if (Auth::user()->role == "pegawai") {
            $instrumenPengawasan = array_filter(
                $instrumenPengawasan,
                fn($i) => $i->status == "disetujui",
            );
        }

        return view("instrumen.daftarinstrumenpengawasan", [
            "title" => "Instrumen Pengawasan",
            "activeTab" => $status,
            "instrumenPengawasan" => $instrumenPengawasan,
        ]);
    }

    public function view($id)
    {
        $instrumenPengawasan = InstrumenPengawasan::find($id);

        if (!$instrumenPengawasan || !$instrumenPengawasan->file) {
            abort(404, "File tidak ditemukan");
        }

        $filePath = "instrumen/" . $instrumenPengawasan->file;

        if (!Storage::exists($filePath)) {
            abort(404, "File tidak ditemukan di storage");
        }
        $path = storage_path("app/private/" . $filePath);

        return response()->file($path, [
            "Content-Type" => "application/pdf",
            "Content-Disposition" =>
                'inline; filename="' . $instrumenPengawasan->file . '"',
        ]);
    }

    public function create()
    {
        $is_pjk = DB::select(
            'SELECT id, name, role FROM users WHERE role IN ("pjk")',
        ); // Ambil data user untuk dropdown
        $is_perencana = DB::select(
            'SELECT id, name, role FROM users WHERE role IN ("perencana")',
        ); // Ambil data user untuk dropdown
        return view("instrumen.createinstrumenpengawasan", [
            "is_pjk" => $is_pjk,
            "is_perencana" => $is_perencana,
            "title" => "Tambah Instrumen Pengawasan",
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "kode" => "required|string|max:255",
            "hasil_kerja" => "required|string|max:255",
            "nama" => "required|string|max:255",
            "penyusun_id" => "required|exists:users,id",
            "deskripsi" => "nullable|string",
            "pdf" => "nullable|file|mimes:pdf|max:10240", // Menerima file PDF maksimal 10MB
            "status" => "required|in:draft,diajukan,disetujui",
        ]);

        // Handle file upload
        $fileName = null;
        if ($request->hasFile("pdf") && $request->file("pdf")->isValid()) {
            $file = $request->file("pdf");
            $fileName = $file->getClientOriginalName();

            // Simpan file ke storage/app/instrumen
            $file->storeAs("instrumen", $fileName);
        }

        // Merge data dengan pembuat_id dan nama file
        $request->merge([
            "pembuat_id" => Auth::id(),
            "file" => $fileName,
        ]);

        InstrumenPengawasan::create(
            $request->only([
                "kode",
                "hasil_kerja",
                "nama",
                "penyusun_id",
                "deskripsi",
                "file",
                "status",
                "pembuat_id",
            ]),
        );

        return redirect()
            ->route("instrumen-pengawasan.index")
            ->with("success", "Instrumen Pengawasan berhasil dibuat.");
    }

    public function downloadPdf($id)
    {
        $instrumen = InstrumenPengawasan::find($id);

        if (!$instrumen || !$instrumen->file) {
            abort(404, "File tidak ditemukan");
        }

        $filePath = "instrumen/" . $instrumen->file;

        if (!Storage::exists($filePath)) {
            abort(404, "File tidak ditemukan di storage");
        }

        return Storage::download($filePath, $instrumen->file);
    }

    public function show($id)
    {
        $instrumenPengawasan = InstrumenPengawasan::detail($id);
        return view("instrumen.detailinstrumenpengawasan", [
            "instrumenPengawasan" => $instrumenPengawasan,
            "title" => "Instrumen Pengawasan",
        ]);
    }

    public function edit($id)
    {
        $instrumenPengawasan = InstrumenPengawasan::find($id);
        $is_pjk = DB::select(
            'SELECT id, name, role FROM users WHERE role IN ("pjk", "operator")',
        ); // Ambil data user untuk dropdown
        return view("instrumen.editinstrumenpengawasan", [
            "instrumenPengawasan" => $instrumenPengawasan,
            "is_pjk" => $is_pjk,
            "title" => "Instrumen Pengawasan",
        ]);
    }

    public function update(Request $request, $id)
    {
        $instrumenPengawasan = InstrumenPengawasan::detail($id);

        if (!$instrumenPengawasan) {
            return redirect()
                ->back()
                ->with("error", "Instrumen Pengawasan tidak ditemukan");
        }

        $validationRules = [
            "kode" => "required|string|max:255",
            "hasil_kerja" => "required|string|max:255",
            "nama" => "required|string|max:255",
            "penyusun_id" => "required|exists:users,id",
            "deskripsi" => "nullable|string",
            "status" => "required|in:draft,diajukan,disetujui",
            "pembuat_id" => "required|exists:users,id",
        ];

        // File PDF bersifat opsional pada update
        if ($request->hasFile("pdf")) {
            $validationRules["pdf"] = "file|mimes:pdf|max:10240";
        }

        $request->validate($validationRules);

        // Handle file upload jika ada
        $fileName = $instrumenPengawasan->file; // Gunakan file yang sudah ada sebagai default

        if ($request->hasFile("pdf") && $request->file("pdf")->isValid()) {
            $file = $request->file("pdf");
            $fileName = $file->getClientOriginalName();

            // Simpan file baru ke storage/app/instrumen
            $file->storeAs("instrumen", $fileName);

            // Hapus file lama jika ada
            if (
                $instrumenPengawasan->file &&
                Storage::exists("instrumen/" . $instrumenPengawasan->file)
            ) {
                Storage::delete("instrumen/" . $instrumenPengawasan->file);
            }
        }

        // Update data dengan file yang sesuai (baru atau tetap yang lama)
        $data = $request->only([
            "kode",
            "hasil_kerja",
            "nama",
            "penyusun_id",
            "deskripsi",
            "status",
            "pembuat_id",
        ]);
        $data["file"] = $fileName;

        InstrumenPengawasan::update($id, $data);

        // Ambil data yang sudah diupdate
        $updatedInstrumenPengawasan = InstrumenPengawasan::detail($id);

        return redirect()
            ->route(
                "instrumen-pengawasan.detail",
                $updatedInstrumenPengawasan->id,
            )
            ->with("success", "Instrumen pengawasan berhasil diedit");
    }

    public function delete($id)
    {
        InstrumenPengawasan::delete($id);
        return redirect()
            ->route("instrumen-pengawasan.index")
            ->with("success", "Instrumen Pengawasan berhasil dihapus");
    }
}
