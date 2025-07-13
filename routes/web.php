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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PertanyaanEvaluasiController;

// use Dompdf\Dompdf as PDF;
// require __DIR__ . '/../vendor/autoload.php';

Route::get("/test", function () {
    return view("prosedur.create-js-fix");
});

// Login
Route::get("/auth/redirect", [SocialiteController::class, "redirect"]);
Route::get("/auth/google/callback", [SocialiteController::class, "callback"]);
// Logout
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
    Route::post("/logout", [SocialiteController::class, "logout"])->name(
        "logout"
    );
    // Prosedur Pengawasan Routes (Domain-Oriented)
    Route::prefix("prosedur-pengawasan")
        ->name("prosedur-pengawasan.")
        ->group(function () {
            Route::get("/", [
                ProsedurPengawasanController::class,
                "index",
            ])->name("index");
            Route::get("/create", [
                ProsedurPengawasanController::class,
                "create",
            ])
                ->name("create")
                ->middleware("role:perencana");
            Route::post("/", [ProsedurPengawasanController::class, "store"])
                ->name("store")
                ->middleware("role:perencana");
            Route::get("/{id}", [
                ProsedurPengawasanController::class,
                "show",
            ])->name("show");
            Route::get("/{id}/edit", [
                ProsedurPengawasanController::class,
                "edit",
            ])
                ->name("edit")
                ->middleware("role:perencana,pjk");
            Route::put("/{id}", [ProsedurPengawasanController::class, "update"])
                ->name("update")
                ->middleware("role:perencana,pjk");
            Route::delete("/{id}", [
                ProsedurPengawasanController::class,
                "delete",
            ])
                ->name("delete")
                ->middleware("role:perencana,pjk");
            Route::get("/{id}/cover-data", [
                ProsedurPengawasanController::class,
                "getCoverData",
            ])->name("get-cover-data");

            // Routes for editing cover and body, accessible by perencana
            Route::middleware("role:perencana")->group(function () {
                Route::get("/{id}/edit-cover", [
                    ProsedurPengawasanController::class,
                    "editCover",
                ])->name("edit-cover");
                Route::put("/{id}/update-cover", [
                    ProsedurPengawasanController::class,
                    "updateCover",
                ])->name("update-cover");
                Route::get("/{id}/edit-body", [
                    ProsedurPengawasanController::class,
                    "editBody",
                ])->name("edit-body");
                Route::put("/{id}/body", [
                    ProsedurPengawasanController::class,
                    "updateBody",
                ])->name("update-body");
            });
        });

    // Periode Evaluasi
    Route::get("/periode", [
        PeriodeEvaluasiProsedurController::class,
        "index",
    ])->name("periode.index");
    Route::middleware(["role:admin"])->group(function () {
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
    });

    // Evaluasi
    Route::middleware(["periode.evaluasi"])->group(function () {
        Route::get("/evaluasi", [
            EvaluasiProsedurController::class,
            "index",
        ])->name("evaluasi.index");
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

    // Regulasi
    Route::get("/regulasi", [RegulasiController::class, "index"])->name(
        "regulasi.index"
    );
    Route::get("/regulasi/create", [RegulasiController::class, "create"])->name(
        "regulasi.create"
    );
    Route::get("/regulasi/{id}/download", [
        RegulasiController::class,
        "downloadPdf",
    ])->name("regulasi.download");
    Route::get("/regulasi/{id}", [RegulasiController::class, "detail"])->name(
        "regulasi.detail"
    );
    Route::post("/regulasi", [RegulasiController::class, "store"])->name(
        "regulasi.store"
    );
    Route::get("/regulasi/{id}/edit", [
        RegulasiController::class,
        "edit",
    ])->name("regulasi.edit");
    Route::put("/regulasi/{id}", [RegulasiController::class, "update"])->name(
        "regulasi.update"
    );
    Route::delete("/regulasi/{id}", [
        RegulasiController::class,
        "delete",
    ])->name("regulasi.delete");
});
Route::middleware("auth", "role:admin")->group(function () {
    Route::get("/admin/dashboard", [DashboardController::class, "index"]);
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

    // Daftar Pejabat Inspektur Utama
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

    // Daftar Pertanyaan Evaluasi
    Route::get("/admin/pertanyaan-evaluasi", [
        PertanyaanEvaluasiController::class,
        "index",
    ])->name("pertanyaan.index");
    Route::get("/admin/pertanyaan-evaluasi/create", [
        PertanyaanEvaluasiController::class,
        "create",
    ])->name("pertanyaan.create");
    Route::post("/admin/pertanyaan-evaluasi", [
        PertanyaanEvaluasiController::class,
        "store",
    ])->name("pertanyaan.store");
    Route::get("/admin/pertanyaan-evaluasi/{pertanyaan}/edit", [
        PertanyaanEvaluasiController::class,
        "edit",
    ])->name("pertanyaan.edit");
    Route::put("/admin/pertanyaan-evaluasi/{pertanyaan}", [
        PertanyaanEvaluasiController::class,
        "update",
    ])->name("pertanyaan.update");
    Route::delete("/admin/pertanyaan-evaluasi/{pertanyaan}", [
        PertanyaanEvaluasiController::class,
        "destroy",
    ])->name("pertanyaan.destroy");
});

Route::middleware("auth", "role:pjk")->group(function () {
    Route::get("/penanggungjawab/dashboard", [
        DashboardController::class,
        "index",
    ]);
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
});
Route::middleware("auth", "role:perencana")->group(function () {
    Route::get("/perencana/dashboard", [DashboardController::class, "index"]);
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
});

Route::middleware("auth", "role:pegawai")->group(function () {
    Route::get("/pegawai/dashboard", [DashboardController::class, "index"]);
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
