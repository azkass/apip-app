<?php

use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstrumenPengawasanController;

// use Dompdf\Dompdf as PDF;
// require __DIR__ . '/../vendor/autoload.php';

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
        }
        return redirect("/login");
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
});
Route::middleware("auth", "role:pjk")->group(function () {
    Route::get("/penanggungjawab/dashboard", function () {
        return view("penanggungjawab.dashboard", ["title" => "Dashboard"]);
    });
    Route::get("/penanggungjawab/daftarinstrumenpengawasan", [
        InstrumenPengawasanController::class,
        "index",
    ])->name("instrumen-pengawasan.index");
    Route::get("/penanggungjawab/daftarinstrumenpengawasan/{id}", [
        InstrumenPengawasanController::class,
        "show",
    ])->name("pjk.instrumen-pengawasan.detail");
    Route::get("/penanggungjawab/daftarinstrumenpengawasan/{id}/edit", [
        InstrumenPengawasanController::class,
        "edit",
    ])->name("pjk.instrumen-pengawasan.edit");
    Route::put("/penanggungjawab/daftarinstrumenpengawasan/{id}", [
        InstrumenPengawasanController::class,
        "update",
    ])->name("pjk-instrumen-pengawasan.update");

    Route::get("/penanggungjawab/daftarprosedurpengawasan", function () {
        return view("penanggungjawab.daftarprosedurpengawasan", [
            "title" => "Daftar Prosedur Pengawasan",
        ]);
    });
});
Route::middleware("auth", "role:perencana")->group(function () {
    Route::get("/perencana/dashboard", function () {
        return view("perencana.dashboard", ["title" => "Dashboard"]);
    });
    Route::get("/perencana/daftarinstrumenpengawasan", [
        InstrumenPengawasanController::class,
        "index",
    ])->name("instrumen-pengawasan.index");
    Route::get("/perencana/daftarinstrumenpengawasan/create", [
        InstrumenPengawasanController::class,
        "create",
    ])->name("instrumen-pengawasan.create");
    Route::get("/perencana/daftarinstrumenpengawasan/{id}", [
        InstrumenPengawasanController::class,
        "show",
    ])->name("perencana.instrumen-pengawasan.detail");
    Route::post("/perencana/daftarinstrumenpengawasan", [
        InstrumenPengawasanController::class,
        "store",
    ])->name("instrumen-pengawasan.store");
    Route::get("/perencana/daftarinstrumenpengawasan/{id}/edit", [
        InstrumenPengawasanController::class,
        "edit",
    ])->name("perencana.instrumen-pengawasan.edit");
    Route::put("/perencana/daftarinstrumenpengawasan/{id}", [
        InstrumenPengawasanController::class,
        "update",
    ])->name("instrumen-pengawasan.update");
    Route::delete("/perencana/daftarinstrumenpengawasan/{id}", [
        InstrumenPengawasanController::class,
        "delete",
    ])->name("instrumen-pengawasan.delete");

    Route::get("/perencana/daftarprosedurpengawasan", function () {
        return view("perencana.daftarprosedurpengawasan", [
            "title" => "Daftar Prosedur Pengawasan",
        ]);
    });
    Route::get("/perencana/daftarprosedurpengawasan/create", function () {
        return view("perencana.createprosedurpengawasan", [
            "title" => "Buat Prosedur Pengawasan",
        ]);
    });
    Route::get("/perencana/daftarprosedurpengawasan/create-cover", function () {
        return view("perencana.cover", [
            "title" => "Buat Prosedur Pengawasan",
        ]);
    });
});

Route::middleware("auth", "role:pegawai")->group(function () {
    Route::get("/pegawai/dashboard", function () {
        return view("pegawai.dashboard", ["title" => "Dashboard"]);
    });
    Route::get("/pegawai/daftarinstrumenpengawasan", [
        InstrumenPengawasanController::class,
        "index",
    ])->name("pegawai.instrumen-pengawasan.index");
    Route::get("/pegawai/daftarinstrumenpengawasan/{id}", [
        InstrumenPengawasanController::class,
        "show",
    ])->name("pegawai.instrumen-pengawasan.detail");
});

// MaxGraph
Route::get("/maxgraph", function () {
    return view("maxGraph", ["title" => "maxgraph"]);
});

Route::get("/reviu", function () {
    return view("riviu", ["title" => "Riviu Page"]);
});
Route::get("/cover", function () {
    return view("cover", ["title" => "Cover Page"]);
});
Route::get("/sop-dompdf", function () {
    return view("sop-dompdf");
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

Route::get("/activity-form", [ActivityController::class, "showForm"])->name(
    "activity.form"
);
Route::post("/generate-table", [
    ActivityController::class,
    "generateTable",
])->name("activity.generate");
