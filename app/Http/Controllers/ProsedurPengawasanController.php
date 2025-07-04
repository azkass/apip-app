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
                fn($i) => $i->status == "disetujui"
            );
        }

        $viewData = [
            "title" => "Prosedur Pengawasan",
            "activeTab" => $activeTab,
            "prosedurPengawasan" => $prosedurPengawasan,
        ];

        $viewPath = match (Auth::user()->role) {
            "admin" => "admin.prosedur.daftarprosedurpengawasan",
            "perencana" => "perencana.prosedur.daftarprosedurpengawasan",
            "pjk" => "penanggungjawab.prosedur.daftarprosedurpengawasan",
            "pegawai" => "pegawai.prosedur.daftarprosedurpengawasan",
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
        
        $inspektur_utama = InspekturUtama::getNama();
        
        return view("perencana.prosedur.createprosedurpengawasan", [
            "is_pjk" => $is_pjk,
            "is_perencana" => $is_perencana,
            "inspektur_utama" => $inspektur_utama,
            "title" => "Tambah Prosedur Pengawasan",
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "judul" => "required|max:255",
            "nomor" => "required|max:255",
            "status" => "required|max:255",
            "pembuat_id" => "required|exists:users,id",
            "penyusun_id" => "required|exists:users,id",
            "tanggal_pembuatan" => "nullable|date",
            "tanggal_revisi" => "nullable|date",
            "tanggal_efektif" => "nullable|date",
            "disahkan_oleh" => "required|exists:inspektur_utama,id",
        ]);

        ProsedurPengawasan::create($validatedData);

        return redirect()
            ->route("perencana.prosedur-pengawasan.index")
            ->with("success", "Prosedur Pengawasan berhasil ditambahkan.");
    }

    public function detail($id)
    {
        $prosedurPengawasan = ProsedurPengawasan::detail($id);
        if (Auth::user()->role == "perencana") {
            return view("perencana.prosedur.detail", [
                "prosedurPengawasan" => $prosedurPengawasan,
                "title" => "Detail Prosedur Pengawasan",
            ]);
        } elseif (Auth::user()->role == "pjk") {
            return view("penanggungjawab.prosedur.detail", [
                "prosedurPengawasan" => $prosedurPengawasan,
                "title" => "Detail Prosedur Pengawasan",
            ]);
        } elseif (Auth::user()->role == "pegawai") {
            return view("pegawai.prosedur.detail", [
                "prosedurPengawasan" => $prosedurPengawasan,
                "title" => "Detail Prosedur Pengawasan",
            ]);
        }
    }

    public function edit($id)
    {
        $prosedurPengawasan = ProsedurPengawasan::find($id);
        $is_pjk = DB::select(
            'SELECT id, name, role FROM users WHERE role IN ("pjk", "operator")'
        ); // Ambil data user untuk dropdown
        
        $inspektur_utama = InspekturUtama::getAll();
        
        if (Auth::user()->role == "perencana") {
            return view("perencana.prosedur.editprosedurpengawasan", [
                "prosedurPengawasan" => $prosedurPengawasan,
                "is_pjk" => $is_pjk,
                "inspektur_utama" => $inspektur_utama,
                "title" => "Edit Prosedur Pengawasan",
            ]);
        } elseif (Auth::user()->role == "pjk") {
            return view("penanggungjawab.prosedur.editprosedurpengawasan", [
                "prosedurPengawasan" => $prosedurPengawasan,
                "is_pjk" => $is_pjk,
                "inspektur_utama" => $inspektur_utama,
                "title" => "Edit Prosedur Pengawasan",
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $prosedurPengawasan = ProsedurPengawasan::detail($id);

        $validatedData = $request->validate([
            "judul" => "required|max:255",
            "nomor" => "required|max:255",
            "status" => "required|max:255",
            "pembuat_id" => "required|exists:users,id",
            "penyusun_id" => "required|exists:users,id",
            "tanggal_pembuatan" => "nullable|date",
            "tanggal_revisi" => "nullable|date",
            "tanggal_efektif" => "nullable|date",
            "disahkan_oleh" => "required|exists:inspektur_utama,id",
        ]);

        ProsedurPengawasan::update($id, $request);

        $updatedProsedurPengawasan = ProsedurPengawasan::detail($id);

        if (Auth::user()->role == "perencana") {
            return redirect()->route(
                // "perencana.prosedur-pengawasan.detail",
                "perencana.prosedur-pengawasan.edit-body",
                $updatedProsedurPengawasan->id
            );
        } elseif (Auth::user()->role == "pjk") {
            return redirect()->route(
                "pjk.prosedur-pengawasan.detail",
                $updatedProsedurPengawasan->id
            );
        }
    }

    public function editBody($id)
    {
        $prosedurPengawasan = ProsedurPengawasan::detail($id);

        return view("perencana.prosedur.edit-body", [
            "prosedurPengawasan" => $prosedurPengawasan,
            "title" => "Edit Prosedur Pengawasan",
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
    
    public function editCover($id)
    {
        $prosedurPengawasan = ProsedurPengawasan::detail($id);

        return view("perencana.prosedur.edit-cover", [
            "prosedurPengawasan" => $prosedurPengawasan,
            "title" => "Edit Cover Prosedur Pengawasan",
        ]);
    }

    public function updateCover(Request $request, $id)
    {
        $validatedData = $request->validate([
            "cover" => "required|json",
        ]);

        ProsedurPengawasan::updateCover($id, $validatedData);

        return response()->json([
            "success" => true,
            "message" => "Cover berhasil disimpan",
        ]);
    }

    public function delete($id)
    {
        ProsedurPengawasan::delete($id);
        return redirect()
            ->route("perencana.prosedur-pengawasan.index")
            ->with("success", "Prosedur Pengawasan deleted successfully");
    }
}
