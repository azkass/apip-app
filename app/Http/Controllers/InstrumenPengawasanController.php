<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstrumenPengawasan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InstrumenPengawasanController extends Controller
{
    public function index()
    {
        $instrumenPengawasan = InstrumenPengawasan::getAll();

        // Pegawai hanya dapat melihat instrumen pengawasan yang sudah disetujui
        if (Auth::user()->role == "pegawai") {
            $instrumenPengawasan = array_filter($instrumenPengawasan, function (
                $instrumen
            ) {
                return $instrumen->status == "disetujui";
            });
        }

        if (Auth::user()->role == "perencana") {
            return view(
                "perencana.daftarinstrumenpengawasan",
                compact("instrumenPengawasan")
            );
        } elseif (Auth::user()->role == "pjk") {
            return view(
                "penanggungjawab.daftarinstrumenpengawasan",
                compact("instrumenPengawasan")
            );
        } elseif (Auth::user()->role == "pegawai") {
            return view(
                "pegawai.daftarinstrumenpengawasan",
                compact("instrumenPengawasan")
            );
        }
    }

    public function create()
    {
        $is_pjk = DB::select(
            'SELECT id, name, role FROM users WHERE role IN ("pjk", "operator")'
        ); // Ambil data user untuk dropdown
        $is_perencana = DB::select(
            'SELECT id, name, role FROM users WHERE role IN ("perencana")'
        ); // Ambil data user untuk dropdown
        return view(
            "perencana.createinstrumenpengawasan",
            compact("is_pjk", "is_perencana")
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            "judul" => "required|string|max:255",
            "petugas_pengelola_id" => "required|exists:users,id",
            "isi" => "nullable|string",
            "status" => "required|in:draft,diajukan,disetujui",
            "perencana_id" => "required|exists:users,id",
        ]);

        InstrumenPengawasan::create($request->all());

        return redirect()
            ->route("instrumen-pengawasan.index")
            ->with("success", "Instrumen Pengawasan created successfully.");
    }

    public function show($id)
    {
        $instrumenPengawasan = InstrumenPengawasan::detail($id);
        // return view('perencana.detailinstrumenpengawasan', compact('instrumenPengawasan'));
        if (Auth::user()->role == "perencana") {
            return view(
                "perencana.detailinstrumenpengawasan",
                compact("instrumenPengawasan")
            );
        } elseif (Auth::user()->role == "pjk") {
            return view(
                "penanggungjawab.detailinstrumenpengawasan",
                compact("instrumenPengawasan")
            );
        } elseif (Auth::user()->role == "pegawai") {
            return view(
                "pegawai.detailinstrumenpengawasan",
                compact("instrumenPengawasan")
            );
        }
    }

    public function edit($id)
    {
        $instrumenPengawasan = InstrumenPengawasan::find($id);
        $is_pjk = DB::select(
            'SELECT id, name, role FROM users WHERE role IN ("pjk", "operator")'
        ); // Ambil data user untuk dropdown
        if (Auth::user()->role == "perencana") {
            return view(
                "perencana.editinstrumenpengawasan",
                compact("instrumenPengawasan", "is_pjk")
            );
        } elseif (Auth::user()->role == "pjk") {
            return view(
                "penanggungjawab.editinstrumenpengawasan",
                compact("instrumenPengawasan", "is_pjk")
            );
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
        InstrumenPengawasan::update($id, $request->all());
        $instrumenPengawasan = InstrumenPengawasan::detail($id);
        if (Auth::user()->role == "perencana") {
            return view(
                "perencana.detailinstrumenpengawasan",
                compact("instrumenPengawasan")
            );
        } elseif (Auth::user()->role == "pjk") {
            return view(
                "penanggungjawab.detailinstrumenpengawasan",
                compact("instrumenPengawasan")
            );
        }
    }

    public function delete($id)
    {
        InstrumenPengawasan::delete($id);
        return redirect()
            ->route("instrumen-pengawasan.index")
            ->with("success", "Instrumen Pengawasan deleted successfully");
    }
}
