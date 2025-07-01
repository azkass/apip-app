<?php

use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstrumenPengawasanController;
use App\Http\Controllers\RegulasiController;
use App\Http\Controllers\ProsedurPengawasanController;
use App\Http\Controllers\EvaluasiProsedurController;
use App\Http\Controllers\PeriodeEvaluasiProsedurController;
use App\Http\Controllers\InspekturUtamaController;

// use Dompdf\Dompdf as PDF;
// require __DIR__ . '/../vendor/autoload.php';

// Periode Evaluasi
Route::get("/periode", [
    PeriodeEvaluasiProsedurController::class,
    "index",
])->name("periode.index");
Route::get("/periode/create", [
    PeriodeEvaluasiProsedurController::class,
    "create",
])->name("periode.create");
Route::post("/periode", [
    PeriodeEvaluasiProsedurController::class,
    "store",
])->name("periode.store");
Route::get("/periode/edit/{id}", [
    PeriodeEvaluasiProsedurController::class,
    "edit",
])->name("periode.edit");
Route::put("/periode/{id}", [
    PeriodeEvaluasiProsedurController::class,
    "update",
])->name("periode.update");
Route::delete("/periode/{id}", [
    PeriodeEvaluasiProsedurController::class,
    "destroy",
])->name("periode.destroy");

// Evaluasi
Route::middleware(["periode.evaluasi"])->group(function () {
    Route::get("/evaluasi", [EvaluasiProsedurController::class, "index"])->name(
        "evaluasi.index"
    );
    Route::get("/evaluasi/create/{sop_id}", [
        EvaluasiProsedurController::class,
        "create",
    ])->name("evaluasi.create");
    Route::post("/evaluasi", [
        EvaluasiProsedurController::class,
        "store",
    ])->name("evaluasi.store");
    Route::get("/evaluasi/{id}", [
        EvaluasiProsedurController::class,
        "show",
    ])->name("evaluasi.show");
    Route::get("/evaluasi/{id}/edit", [
        EvaluasiProsedurController::class,
        "edit",
    ])->name("evaluasi.edit");
    Route::put("/evaluasi/{id}", [
        EvaluasiProsedurController::class,
        "update",
    ])->name("evaluasi.update");
    Route::delete("/evaluasi/{id}", [
        EvaluasiProsedurController::class,
        "destroy",
    ])->name("evaluasi.destroy");
});

// Login
Route::get("/auth/redirect", [SocialiteController::class, "redirect"]);
Route::get("/auth/google/callback", [SocialiteController::class, "callback"]);
// Logout
Route::post("/logout", [SocialiteController::class, "logout"])->name("logout");
Route::get("/login", function () {
    return view("login", ["title" => "Login"]);
});
Route::middleware("auth")->group(function () {
    // Route yang membutuhkan login
    Route::get("/", function () {
        $user = Auth::user();
        if ($user->role == "admin") {
            return redirect("/admin/dashboard");
        } elseif ($user->role == "pegawai") {
            return redirect("/pegawai/dashboard");
        } elseif ($user->role == "perencana") {
            return redirect("/perencana/dashboard");
        } elseif ($user->role == "pjk") {
            return redirect("/penanggungjawab/dashboard");
        } else {
            return redirect("/login");
        }
    });
});
Route::middleware("auth", "role:admin")->group(function () {
    Route::get("/admin/dashboard", function () {
        return view("admin.dashboard", ["title" => "Dashboard"]);
    });
    Route::get("/admin/list", [SocialiteController::class, "list"])->name(
        "admin.list"
    );
    Route::get("/admin/editrole/{id}", [
        SocialiteController::class,
        "edit",
    ])->name("admin.editrole");
    Route::put("/admin/update/{id}", [
        SocialiteController::class,
        "update",
    ])->name("admin.update");
    Route::get("/admin/prosedurpengawasan", [
        ProsedurPengawasanController::class,
        "index",
    ])->name("admin.prosedurpengawasan.index");
    Route::get("/admin/prosedurpengawasan/{id}", [
        ProsedurPengawasanController::class,
        "detail",
    ])->name("admin.prosedur-pengawasan.detail");

    // Routes untuk Inspektur Utama - hanya admin yang bisa akses
    Route::get("/admin/inspektur-utama", [
        InspekturUtamaController::class,
        "index",
    ])->name("admin.inspektur-utama.index");
    Route::get("/admin/inspektur-utama/create", [
        InspekturUtamaController::class,
        "create",
    ])->name("admin.inspektur-utama.create");
    Route::post("/admin/inspektur-utama", [
        InspekturUtamaController::class,
        "store",
    ])->name("admin.inspektur-utama.store");
    Route::get("/admin/inspektur-utama/{id}", [
        InspekturUtamaController::class,
        "show",
    ])->name("admin.inspektur-utama.show");
    Route::get("/admin/inspektur-utama/{id}/edit", [
        InspekturUtamaController::class,
        "edit",
    ])->name("admin.inspektur-utama.edit");
    Route::put("/admin/inspektur-utama/{id}", [
        InspekturUtamaController::class,
        "update",
    ])->name("admin.inspektur-utama.update");
    Route::delete("/admin/inspektur-utama/{id}", [
        InspekturUtamaController::class,
        "destroy",
    ])->name("admin.inspektur-utama.destroy");
});

Route::middleware("auth", "role:pjk")->group(function () {
    Route::get("/penanggungjawab/dashboard", function () {
        return view("penanggungjawab.dashboard", ["title" => "Dashboard"]);
    });
    Route::get("/penanggungjawab/instrumenpengawasan", [
        InstrumenPengawasanController::class,
        "index",
    ])->name("pjk.instrumen-pengawasan.index");
    Route::get("/penanggungjawab/instrumenpengawasan/{id}", [
        InstrumenPengawasanController::class,
        "show",
    ])->name("pjk.instrumen-pengawasan.detail");
    Route::get("/penanggungjawab/instrumenpengawasan/{id}/edit", [
        InstrumenPengawasanController::class,
        "edit",
    ])->name("pjk.instrumen-pengawasan.edit");
    Route::put("/penanggungjawab/instrumenpengawasan/{id}", [
        InstrumenPengawasanController::class,
        "update",
    ])->name("pjk-instrumen-pengawasan.update");

    Route::get("/penanggungjawab/prosedurpengawasan", [
        ProsedurPengawasanController::class,
        "index",
    ])->name("pjk.prosedur-pengawasan.index");
    Route::get("/penanggungjawab/prosedurpengawasan/{id}", [
        ProsedurPengawasanController::class,
        "detail",
    ])->name("pjk.prosedur-pengawasan.detail");
    Route::get("/penanggungjawab/prosedurpengawasan/{id}/edit", [
        ProsedurPengawasanController::class,
        "edit",
    ])->name("pjk.prosedur-pengawasan.edit");
    Route::put("/penanggungjawab/prosedurpengawasan/{id}", [
        ProsedurPengawasanController::class,
        "update",
    ])->name("pjk.prosedur-pengawasan.update");
    Route::delete("/penanggungjawab/prosedurpengawasan/{id}", [
        ProsedurPengawasanController::class,
        "delete",
    ])->name("pjk.prosedur-pengawasan.delete");
});
Route::middleware("auth", "role:perencana")->group(function () {
    Route::get("/perencana/dashboard", function () {
        return view("perencana.dashboard", ["title" => "Dashboard"]);
    });
    Route::get("/perencana/instrumenpengawasan", [
        InstrumenPengawasanController::class,
        "index",
    ])->name("perencana.instrumen-pengawasan.index");
    Route::get("/perencana/instrumenpengawasan/create", [
        InstrumenPengawasanController::class,
        "create",
    ])->name("instrumen-pengawasan.create");
    Route::get("/perencana/instrumenpengawasan/{id}", [
        InstrumenPengawasanController::class,
        "show",
    ])->name("perencana.instrumen-pengawasan.detail");
    Route::post("/perencana/instrumenpengawasan", [
        InstrumenPengawasanController::class,
        "store",
    ])->name("instrumen-pengawasan.store");

    Route::get("/perencana/instrumenpengawasan/{id}/download", [
        InstrumenPengawasanController::class,
        "downloadPdf",
    ])->name("instrumen-pengawasan.download");

    Route::get("/perencana/instrumenpengawasan/{id}/edit", [
        InstrumenPengawasanController::class,
        "edit",
    ])->name("perencana.instrumen-pengawasan.edit");
    Route::put("/perencana/instrumenpengawasan/{id}", [
        InstrumenPengawasanController::class,
        "update",
    ])->name("instrumen-pengawasan.update");
    Route::delete("/perencana/instrumenpengawasan/{id}", [
        InstrumenPengawasanController::class,
        "delete",
    ])->name("instrumen-pengawasan.delete");

    Route::get("/perencana/prosedurpengawasan", [
        ProsedurPengawasanController::class,
        "index",
    ])->name("perencana.prosedur-pengawasan.index");
    Route::get("/perencana/prosedurpengawasan/create", [
        ProsedurPengawasanController::class,
        "create",
    ])->name("prosedur-pengawasan.create");
    Route::post("/perencana/prosedurpengawasan", [
        ProsedurPengawasanController::class,
        "store",
    ])->name("prosedur-pengawasan.store");
    Route::get("/perencana/prosedurpengawasan/edit/{id}", [
        ProsedurPengawasanController::class,
        "edit",
    ])->name("perencana.prosedur-pengawasan.edit");
    Route::get("/perencana/prosedurpengawasan/detail/{id}", [
        ProsedurPengawasanController::class,
        "detail",
    ])->name("perencana.prosedur-pengawasan.detail");
    Route::put("/perencana/prosedurpengawasan/{id}", [
        ProsedurPengawasanController::class,
        "update",
    ])->name("perencana.prosedur-pengawasan.update");
    Route::delete("/perencana/prosedurpengawasan/delete/{id}", [
        ProsedurPengawasanController::class,
        "delete",
    ])->name("perencana.prosedur-pengawasan.delete");

    Route::get("/perencana/prosedurpengawasan/create-cover", function () {
        return view("perencana.prosedur.cover", [
            "title" => "Buat Prosedur Pengawasan",
        ]);
    });
    Route::get("/perencana/prosedurpengawasan/create-fix", function () {
        return view("perencana.prosedur.create-fix", [
            "title" => "Buat Prosedur Pengawasan",
        ]);
    });
    Route::get("/perencana/prosedurpengawasan/edit-body/{id}", [
        ProsedurPengawasanController::class,
        "editBody",
    ])->name("perencana.prosedur-pengawasan.edit-body");
    Route::put("/perencana/prosedurpengawasan/body/{id}", [
        ProsedurPengawasanController::class,
        "updateBody",
    ])->name("perencana.prosedur-pengawasan.update-body");

    Route::get("/perencana/regulasi", [
        RegulasiController::class,
        "index",
    ])->name("perencana.regulasi.index");
    Route::get("/perencana/regulasi/create", [
        RegulasiController::class,
        "create",
    ])->name("perencana.regulasi.create");

    Route::get("/perencana/regulasi/{id}/download", [
        RegulasiController::class,
        "downloadPdf",
    ])->name("perencana.regulasi.download");

    Route::get("/perencana/regulasi/{id}", [
        RegulasiController::class,
        "detail",
    ])->name("perencana.regulasi.detail");
    Route::post("/perencana/regulasi", [
        RegulasiController::class,
        "store",
    ])->name("perencana.regulasi.store");
    Route::get("/perencana/regulasi/{id}/edit", [
        RegulasiController::class,
        "edit",
    ])->name("perencana.regulasi.edit");
    Route::put("/perencana/regulasi/{id}", [
        RegulasiController::class,
        "update",
    ])->name("perencana.regulasi.update");
    Route::delete("/perencana/regulasi/{id}", [
        RegulasiController::class,
        "delete",
    ])->name("perencana.regulasi.delete");
});

Route::middleware("auth", "role:pegawai")->group(function () {
    Route::get("/pegawai/dashboard", function () {
        return view("pegawai.dashboard", ["title" => "Dashboard"]);
    });
    Route::get("/pegawai/instrumenpengawasan", [
        InstrumenPengawasanController::class,
        "index",
    ])->name("pegawai.instrumen-pengawasan.index");
    Route::get("/pegawai/instrumenpengawasan/{id}", [
        InstrumenPengawasanController::class,
        "show",
    ])->name("pegawai.instrumen-pengawasan.detail");
});

// Route::post('/generate-pdf', function (Illuminate\Http\Request $request) {
//     $activities = $request->input('activities');
//     $durations = $request->input('durations');
//     $actor1 = $request->input('actor1');
//     $actor2 = $request->input('actor2');

//     $data = compact('activities', 'durations', 'actor1', 'actor2');
//     $html = view('table', $data)->render();
//     $pdf = new PDF();
//     $pdf->loadHtml($html);
//     $pdf->setPaper('A4', 'landscape');
//     $pdf->render();
//     return $pdf->stream('aktivitas.pdf');
// })->name('generate-pdf');

use App\Http\Controllers\ActivityController;
Route::post("/generate-table", [
    ActivityController::class,
    "generateTable",
])->name("activity.generate");
