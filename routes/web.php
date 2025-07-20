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
use App\Http\Controllers\TugasController;
use App\Http\Controllers\MonevProsedurPengawasanController;

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
        return redirect("/dashboard");
    });
    Route::get("/dashboard", [DashboardController::class, "index"]);

    // Logout
    Route::post("/logout", [SocialiteController::class, "logout"])->name(
        "logout",
    );
    // Tugas Saya
    Route::middleware("role:admin|pjk|perencana")->group(function () {
        Route::get("/tugas", [TugasController::class, "index"]);
    });

    // Monitoring Evaluasi (Monev Prosedur Pengawasan)
    Route::prefix("monitoring-evaluasi")
        ->name("monitoring-evaluasi.")
        ->group(function () {
            Route::get("/", [
                MonevProsedurPengawasanController::class,
                "index",
            ])->name("index");
            Route::get("/create/{sop_id}", [
                MonevProsedurPengawasanController::class,
                "create",
            ])
                ->where("sop_id", "[0-9]+") // Validasi parameter sebagai angka
                ->name("create")
                ->middleware("periode.evaluasi");
            Route::post("/", [
                MonevProsedurPengawasanController::class,
                "store",
            ])
                ->name("store")
                ->middleware("periode.evaluasi");
            Route::get("/{id}", [
                MonevProsedurPengawasanController::class,
                "show",
            ])->name("show");
            Route::get("/{id}/edit", [
                MonevProsedurPengawasanController::class,
                "edit",
            ])
                ->name("edit")
                ->middleware("periode.evaluasi");
            Route::put("/{id}", [
                MonevProsedurPengawasanController::class,
                "update",
            ])
                ->name("update")
                ->middleware("periode.evaluasi");
            Route::delete("/{id}", [
                MonevProsedurPengawasanController::class,
                "destroy",
            ])->name("destroy");
            Route::get("/{id}/download", [
                MonevProsedurPengawasanController::class,
                "downloadMonev",
            ])->name("download-monev");
        });

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
                ->middleware("role:perencana|pjk");
            Route::put("/{id}", [ProsedurPengawasanController::class, "update"])
                ->name("update")
                ->middleware("role:perencana|pjk");
            Route::get("/{id}/upload-ttd", [
                ProsedurPengawasanController::class,
                "uploadTtd",
            ])
                ->name("upload-ttd")
                ->middleware("role:perencana");
            Route::post("/{id}/upload-ttd", [
                ProsedurPengawasanController::class,
                "storeTtd",
            ])
                ->name("store-ttd")
                ->middleware("role:perencana");
            Route::get("/{id}/download-ttd", [
                ProsedurPengawasanController::class,
                "downloadTtd",
            ])
                ->name("download-ttd")
                ->middleware("role:perencana|pjk|admin|pegawai");
            Route::delete("/{id}", [
                ProsedurPengawasanController::class,
                "delete",
            ])
                ->name("delete")
                ->middleware("role:perencana");
            Route::get("/{id}/cover-data", [
                ProsedurPengawasanController::class,
                "getCoverData",
            ])->name("get-cover-data");
            Route::get("/{id}/download-ttd-file", [
                ProsedurPengawasanController::class,
                "downloadTtdFile",
            ])
                ->name("download-ttd-file");

            // Routes for editing cover and body, accessible by perencana
            Route::middleware("role:perencana|pjk")->group(function () {
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
    Route::prefix("periode")
        ->name("periode.")
        ->controller(PeriodeEvaluasiProsedurController::class)
        ->group(function () {
            Route::get("/", "index")->name("index");
        });

    // Periode Evaluasi role admin untuk create, store, edit, update, destroy
    Route::middleware(["role:admin"])
        ->prefix("periode")
        ->name("periode.")
        ->controller(PeriodeEvaluasiProsedurController::class)
        ->group(function () {
            Route::get("/create", "create")->name("create");
            Route::post("/", "store")->name("store");
            Route::get("/edit/{id}", "edit")->name("edit");
            Route::put("/{id}", "update")->name("update");
            Route::delete("/{id}", "destroy")->name("destroy");
        });

    // Evaluasi
    Route::middleware(["periode.evaluasi"])
        ->prefix("evaluasi")
        ->name("evaluasi.")
        ->controller(EvaluasiProsedurController::class)
        ->group(function () {
            Route::get("/", "index")->name("index");
            Route::get("/create/{sop_id}", "create")->name("create");
            Route::post("/", "store")->name("store");
            Route::get("{id}", "show")->name("show");
            Route::get("{id}/edit", "edit")->name("edit");
            Route::put("{id}", "update")->name("update");
            Route::delete("{id}", "destroy")->name("destroy");
        });

    // Route khusus role:perencana untuk create delete
    Route::middleware("role:perencana")
        ->prefix("instrumenpengawasan")
        ->name("instrumen-pengawasan.")
        ->controller(InstrumenPengawasanController::class)
        ->group(function () {
            Route::get("create", "create")->name("create");
            Route::post("/", "store")->name("store");
            Route::delete("{id}", "delete")->name("delete");
        });

    // Route tanpa middleware (akses umum)
    Route::prefix("instrumenpengawasan")
        ->name("instrumen-pengawasan.")
        ->controller(InstrumenPengawasanController::class)
        ->group(function () {
            Route::get("/", "index")->name("index");
            Route::get("/view/{id}", "view")->name("view");
            Route::get("{id}", "show")->name("detail");
            Route::get("{id}/download", "downloadPdf")->name("download");
        });

    // Route khusus role pjk atau perencana untuk edit update
    Route::middleware("role:pjk|perencana")
        ->prefix("instrumenpengawasan")
        ->name("instrumen-pengawasan.")
        ->controller(InstrumenPengawasanController::class)
        ->group(function () {
            Route::get("{id}/edit", "edit")->name("edit");
            Route::put("{id}", "update")->name("update");
        });

    // Regulasi
    Route::prefix("regulasi")
        ->name("regulasi.")
        ->controller(RegulasiController::class)
        ->group(function () {
            Route::get("/", "index")->name("index");
            Route::get("create", "create")->name("create");
            Route::post("/", "store")->name("store");
            Route::get("/view/{id}", "view")->name("view");
            Route::get("{id}/download", "downloadPdf")->name("download");
            Route::get("{id}", "detail")->name("detail");
            Route::get("{id}/edit", "edit")->name("edit");
            Route::put("{id}", "update")->name("update");
            Route::delete("{id}", "delete")->name("delete");
        });
});
Route::middleware("auth", "role:admin")->group(function () {
    Route::get("/admin/list", [SocialiteController::class, "list"])->name(
        "admin.list",
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
