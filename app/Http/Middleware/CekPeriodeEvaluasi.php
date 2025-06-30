<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use App\Models\PeriodeEvaluasiProsedur;

class CekPeriodeEvaluasi
{
    public function handle($request, Closure $next)
    {
        $periode = PeriodeEvaluasiProsedur::getLatest();
        $now = now()->toDateString();

        if (!$periode) {
            return redirect()
                ->route("periode.create")
                ->with(
                    "error",
                    "Belum ada periode evaluasi yang dibuat. Silakan buat periode evaluasi terlebih dahulu."
                );
        }

        if ($now < $periode->mulai || $now > $periode->berakhir) {
            return redirect()
                ->back()
                ->with(
                    "error",
                    "Akses CRUD hanya diizinkan antara " .
                        date("d-m-Y", strtotime($periode->mulai)) .
                        " hingga " .
                        date("d-m-Y", strtotime($periode->berakhir))
                );
        }

        return $next($request);
    }
}
