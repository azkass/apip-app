<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstrumenPengawasan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            "petugas_pengelola_id" => "required|exists:users,id",
            "isi" => "nullable|string",
            "status" => "required|in:draft,diajukan,disetujui",
        ]);

        $request->merge(["perencana_id" => Auth::id()]);

        InstrumenPengawasan::create(
            $request->only([
                "judul",
                "petugas_pengelola_id",
                "isi",
                "status",
                "perencana_id",
            ])
        );

        return redirect()
            ->route("perencana.instrumen-pengawasan.index")
            ->with("success", "Instrumen Pengawasan created successfully.");
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
        $request->validate([
            "judul" => "required|string|max:255",
            "petugas_pengelola_id" => "required|exists:users,id",
            "isi" => "nullable|string",
            "status" => "required|in:draft,diajukan,disetujui",
            "perencana_id" => "required|exists:users,id",
        ]);
        InstrumenPengawasan::update(
            $id,
            $request->only([
                "judul",
                "petugas_pengelola_id",
                "isi",
                "status",
                "perencana_id",
            ])
        );
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
