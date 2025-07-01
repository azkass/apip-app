<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InspekturUtama;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InspekturUtamaController extends Controller
{
    public function index(): View
    {
        $inspekturUtama = InspekturUtama::getAll();

        return view("admin.inspektur-utama.index", [
            "title" => "Data Inspektur Utama",
            "inspekturUtama" => $inspekturUtama,
        ]);
    }

    public function create(): View
    {
        return view("admin.inspektur-utama.create", [
            "title" => "Tambah Inspektur Utama",
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                "nama" => "required|string|max:255",
                "nip" => "required|string|max:50|unique:inspektur_utama,nip",
                "jabatan" => "required|string|max:255",
            ],
            [
                "nama.required" => "Nama wajib diisi",
                "nama.max" => "Nama maksimal 255 karakter",
                "nip.required" => "NIP wajib diisi",
                "nip.max" => "NIP maksimal 50 karakter",
                "nip.unique" => "NIP sudah terdaftar",
                "jabatan.required" => "Jabatan wajib diisi",
                "jabatan.max" => "Jabatan maksimal 255 karakter",
            ]
        );

        InspekturUtama::create($request->only(["nama", "nip", "jabatan"]));

        return redirect()
            ->route("admin.inspektur-utama.index")
            ->with("success", "Data Inspektur Utama berhasil ditambahkan");
    }

    /**
     * @param int $id
     */
    public function show($id): View|RedirectResponse
    {
        $inspekturUtama = InspekturUtama::find($id);

        if (!$inspekturUtama) {
            return redirect()
                ->route("admin.inspektur-utama.index")
                ->with("error", "Data Inspektur Utama tidak ditemukan");
        }

        return view("admin.inspektur-utama.show", [
            "title" => "Detail Inspektur Utama",
            "inspekturUtama" => $inspekturUtama,
        ]);
    }

    /**
     * @param int $id
     */
    public function edit($id): View|RedirectResponse
    {
        $inspekturUtama = InspekturUtama::find($id);

        if (!$inspekturUtama) {
            return redirect()
                ->route("admin.inspektur-utama.index")
                ->with("error", "Data Inspektur Utama tidak ditemukan");
        }

        return view("admin.inspektur-utama.edit", [
            "title" => "Edit Inspektur Utama",
            "inspekturUtama" => $inspekturUtama,
        ]);
    }

    /**
     * @param int $id
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $inspekturUtama = InspekturUtama::find($id);

        if (!$inspekturUtama) {
            return redirect()
                ->route("admin.inspektur-utama.index")
                ->with("error", "Data Inspektur Utama tidak ditemukan");
        }

        $request->validate(
            [
                "nama" => "required|string|max:255",
                "nip" =>
                    "required|string|max:50|unique:inspektur_utama,nip," . $id,
                "jabatan" => "required|string|max:255",
            ],
            [
                "nama.required" => "Nama wajib diisi",
                "nama.max" => "Nama maksimal 255 karakter",
                "nip.required" => "NIP wajib diisi",
                "nip.max" => "NIP maksimal 50 karakter",
                "nip.unique" => "NIP sudah terdaftar",
                "jabatan.required" => "Jabatan wajib diisi",
                "jabatan.max" => "Jabatan maksimal 255 karakter",
            ]
        );

        InspekturUtama::update($id, $request->only(["nama", "nip", "jabatan"]));

        return redirect()
            ->route("admin.inspektur-utama.show", $id)
            ->with("success", "Data Inspektur Utama berhasil diperbarui");
    }

    /**
     * @param int $id
     */
    public function destroy($id): RedirectResponse
    {
        $inspekturUtama = InspekturUtama::find($id);

        if (!$inspekturUtama) {
            return redirect()
                ->route("admin.inspektur-utama.index")
                ->with("error", "Data Inspektur Utama tidak ditemukan");
        }

        InspekturUtama::delete($id);

        return redirect()
            ->route("admin.inspektur-utama.index")
            ->with("success", "Data Inspektur Utama berhasil dihapus");
    }
}
