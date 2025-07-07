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
        
        $inspektur_utama_nama = InspekturUtama::getNama();
        
        if (Auth::user()->role == "perencana") {
            return view("perencana.prosedur.editprosedurpengawasan", [
                "prosedurPengawasan" => $prosedurPengawasan,
                "is_pjk" => $is_pjk,
                "inspektur_utama_nama" => $inspektur_utama_nama,
                "title" => "Edit Prosedur Pengawasan",
            ]);
        } elseif (Auth::user()->role == "pjk") {
            return view("penanggungjawab.prosedur.editprosedurpengawasan", [
                "prosedurPengawasan" => $prosedurPengawasan,
                "is_pjk" => $is_pjk,
                "inspektur_utama_nama" => $inspektur_utama_nama,
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
                "perencana.prosedur-pengawasan.edit-cover",
                $updatedProsedurPengawasan->id
            );
        } elseif (Auth::user()->role == "pjk") {
            return redirect()->route(
                "pjk.prosedur-pengawasan.detail",
                $updatedProsedurPengawasan->id
            );
        }
    }

    public function editCover($id)
    {
        $prosedurPengawasan = ProsedurPengawasan::detail($id);
        // Pastikan field cover di-decode ke array
        if ($prosedurPengawasan) {
            // Ambil dari kolom cover jika field-field tidak ada
            $cover = json_decode($prosedurPengawasan->cover ?? '{}', true);
            $prosedurPengawasan->dasar_hukum = $cover['dasar_hukum'] ?? $cover['dasarHukum'] ?? [];
            $prosedurPengawasan->keterkaitan = $cover['keterkaitan'] ?? [];
            $prosedurPengawasan->peringatan = $cover['peringatan'] ?? [];
            $prosedurPengawasan->kualifikasi = $cover['kualifikasi'] ?? [];
            $prosedurPengawasan->peralatan = $cover['peralatan'] ?? [];
            $prosedurPengawasan->pencatatan = $cover['pencatatan'] ?? [];
        }
        return view("perencana.prosedur.edit-cover", [
            "prosedurPengawasan" => $prosedurPengawasan,
            "title" => "Edit Cover Prosedur Pengawasan",
        ]);
    }

    public function getCoverData($id)
    {
        $prosedur = ProsedurPengawasan::detail($id);
        if (!$prosedur) {
            return response()->json(['error' => 'Not found'], 404);
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
            'nomor_sop'         => $prosedur->nomor ?? '',
            'tanggal_pembuatan' => $prosedur->tanggal_pembuatan ?? '',
            'tanggal_revisi'    => $prosedur->tanggal_revisi ?? '',
            'tanggal_efektif'   => $prosedur->tanggal_efektif ?? '',
            'disahkan_oleh'     => $prosedur->disahkan_oleh_nama ?? '',
            'nama_sop'          => $prosedur->judul ?? '',
            'pejabat_nama'      => $prosedur->petugas_nama ?? '',
        ];
        $dynamic = ['dasarHukum','keterkaitan','peringatan','kualifikasi','peralatan','pencatatan'];
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
        $prosedur = ProsedurPengawasan::detail($id);
        if (!$prosedur) {
            return response()->json(['error' => 'Not found after update'], 404);
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
            'nomor_sop'         => $prosedur->nomor ?? '',
            'tanggal_pembuatan' => $prosedur->tanggal_pembuatan ?? '',
            'tanggal_revisi'    => $prosedur->tanggal_revisi ?? '',
            'tanggal_efektif'   => $prosedur->tanggal_efektif ?? '',
            'disahkan_oleh'     => $prosedur->disahkan_oleh_nama ?? '',
            'nama_sop'          => $prosedur->judul ?? '',
            'pejabat_nama'      => $prosedur->petugas_nama ?? '',
        ];
        
        // These are the keys the JS expects for the dynamic fields
        $dynamicKeys = ['dasarHukum','keterkaitan','peringatan','kualifikasi','peralatan','pencatatan'];
        
        foreach ($dynamicKeys as $camelCaseKey) {
            // Convert camelCase to snake_case for fallback lookup
            $snakeCaseKey = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $camelCaseKey));
            // Check for camelCase key first, then snake_case, then default to empty array
            $responseData[$camelCaseKey] = $cover[$camelCaseKey] ?? $cover[$snakeCaseKey] ?? [];
        }
        
        return response()->json($responseData);
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
    

    public function delete($id)
    {
        ProsedurPengawasan::delete($id);
        return redirect()
            ->route("perencana.prosedur-pengawasan.index")
            ->with("success", "Prosedur Pengawasan deleted successfully");
    }
}
