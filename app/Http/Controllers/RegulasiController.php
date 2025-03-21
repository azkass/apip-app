<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Regulasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegulasiController extends Controller
{
    public function index()
    {
        $regulasi = Regulasi::getAll();
        return view("perencana.regulasi.daftarregulasi", compact("regulasi"));
    }

    public function detail($id)
    {
        $regulasi = Regulasi::detail($id);
        return view("perencana.regulasi.detailregulasi", compact("regulasi"));
    }

    public function create()
    {
        return view("perencana.regulasi.createregulasi");
    }

    public function store(Request $request)
    {
        $request->validate([
            "judul" => "required|string|max:255",
            "tautan" => "required|string",
        ]);
        $request->merge(["perencana_id" => Auth::id()]);

        Regulasi::create($request->only(["judul", "tautan", "perencana_id"]));

        return redirect()
            ->route("perencana.regulasi.index")
            ->with("success", "Regulasi berhasil ditambahkan");
    }

    public function edit($id)
    {
        $regulasi = Regulasi::find($id);
        return view("perencana.regulasi.editregulasi", compact("regulasi"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "judul" => "required|string|max:255",
            "tautan" => "required|string",
            "perencana_id" => "required|exists:users,id",
        ]);

        Regulasi::update(
            $id,
            $request->only(["judul", "tautan", "perencana_id"])
        );

        $regulasi = Regulasi::detail($id);
        return view("perencana.regulasi.detailregulasi", compact("regulasi"));
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
