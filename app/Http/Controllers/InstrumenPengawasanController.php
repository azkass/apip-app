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
        $activeTab = $status;
        $instrumenPengawasan = InstrumenPengawasan::getByStatus($status);

        if (Auth::user()->role == "pegawai") {
            $instrumenPengawasan = array_filter(
                $instrumenPengawasan,
                fn($i) => $i->status == "disetujui"
            );
        }

        $viewData = [
            "title" => "Instrumen Pengawasan",
            "activeTab" => $activeTab,
            "instrumenPengawasan" => $instrumenPengawasan,
        ];

        $viewPath = match (Auth::user()->role) {
            "perencana" => "perencana.instrumen.daftarinstrumenpengawasan",
            "pjk" => "penanggungjawab.instrumen.daftarinstrumenpengawasan",
            "pegawai" => "pegawai.instrumen.daftarinstrumenpengawasan",
        };

        return view($viewPath, $viewData);
    }

    public function create()
    {
        $is_pjk = DB::select(
            'SELECT id, name, role FROM users WHERE role IN ("pjk", "operator")'
        ); // Ambil data user untuk dropdown
        $is_perencana = DB::select(
            'SELECT id, name, role FROM users WHERE role IN ("perencana")'
        ); // Ambil data user untuk dropdown
        return view("perencana.instrumen.createinstrumenpengawasan", [
            "is_pjk" => $is_pjk,
            "is_perencana" => $is_perencana,
            "title" => "Tambah Instrumen Pengawasan",
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "judul" => "required|string|max:255",
            "pengelola_id" => "required|exists:users,id",
            "deskripsi" => "nullable|string",
            "pdf" => "required|file|mimes:pdf|max:10240", // Menerima file PDF maksimal 10MB
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
                "judul",
                "pengelola_id",
                "deskripsi",
                "file",
                "status",
                "pembuat_id",
            ])
        );

        return redirect()
            ->route("perencana.instrumen-pengawasan.index")
            ->with("success", "Instrumen Pengawasan created successfully.");
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
        if (Auth::user()->role == "perencana") {
            return view("perencana.instrumen.detailinstrumenpengawasan", [
                "instrumenPengawasan" => $instrumenPengawasan,
                "title" => "Detail Instrumen Pengawasan",
            ]);
        } elseif (Auth::user()->role == "pjk") {
            return view("penanggungjawab.instrumen.detailinstrumenpengawasan", [
                "instrumenPengawasan" => $instrumenPengawasan,
                "title" => "Detail Instrumen Pengawasan",
            ]);
        } elseif (Auth::user()->role == "pegawai") {
            return view("pegawai.instrumen.detailinstrumenpengawasan", [
                "instrumenPengawasan" => $instrumenPengawasan,
                "title" => "Detail Instrumen Pengawasan",
            ]);
        }
    }

    public function edit($id)
    {
        $instrumenPengawasan = InstrumenPengawasan::find($id);
        $is_pjk = DB::select(
            'SELECT id, name, role FROM users WHERE role IN ("pjk", "operator")'
        ); // Ambil data user untuk dropdown
        if (Auth::user()->role == "perencana") {
            return view("perencana.instrumen.editinstrumenpengawasan", [
                "instrumenPengawasan" => $instrumenPengawasan,
                "is_pjk" => $is_pjk,
                "title" => "Edit Instrumen Pengawasan",
            ]);
        } elseif (Auth::user()->role == "pjk") {
            return view("penanggungjawab.instrumen.editinstrumenpengawasan", [
                "instrumenPengawasan" => $instrumenPengawasan,
                "is_pjk" => $is_pjk,
                "title" => "Edit Instrumen Pengawasan",
            ]);
        }
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
            "judul" => "required|string|max:255",
            "pengelola_id" => "required|exists:users,id",
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
            "judul",
            "pengelola_id",
            "deskripsi",
            "status",
            "pembuat_id",
        ]);
        $data["file"] = $fileName;

        InstrumenPengawasan::update($id, $data);

        // Ambil data yang sudah diupdate
        $updatedInstrumenPengawasan = InstrumenPengawasan::detail($id);

        // Redirect sesuai role user
        if (Auth::user()->role == "perencana") {
            return view("perencana.instrumen.detailinstrumenpengawasan", [
                "instrumenPengawasan" => $updatedInstrumenPengawasan,
                "title" => "Detail Instrumen Pengawasan",
            ])->with("success", "Instrumen Pengawasan berhasil diperbarui");
        } elseif (Auth::user()->role == "pjk") {
            return view("penanggungjawab.instrumen.detailinstrumenpengawasan", [
                "instrumenPengawasan" => $updatedInstrumenPengawasan,
                "title" => "Detail Instrumen Pengawasan",
            ])->with("success", "Instrumen Pengawasan berhasil diperbarui");
        }
    }

    public function delete($id)
    {
        InstrumenPengawasan::delete($id);
        return redirect()
            ->route("perencana.instrumen-pengawasan.index")
            ->with("success", "Instrumen Pengawasan deleted successfully");
    }
}
