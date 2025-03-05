<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ProsedurPengawasan
{
    protected $table = "prosedur_pengawasan";

    public function all()
    {
        return DB::select("SELECT * FROM prosedur_pengawasan");
    }

    public function detail($id)
    {
        return DB::selectOne("SELECT * FROM prosedur_pengawasan WHERE id = ?", [
            $id,
        ]);
    }
}
