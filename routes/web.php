<?php

use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

// use Dompdf\Dompdf as PDF;
// require __DIR__ . '/../vendor/autoload.php';

// Login
Route::get('/auth/redirect', [SocialiteController::class, 'redirect']);
Route::get('/auth/google/callback', [SocialiteController::class, 'callback']);
// Route untuk logout
Route::post('/logout', [SocialiteController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Route yang membutuhkan login
    Route::get('/', function () {
        $user = Auth::user();
        if ($user->role == 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role == 'pegawai') {
            return redirect('/pegawai/dashboard');
        }
        return redirect('/login');
    });
});
Route::middleware('auth','role:admin')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    });
});
Route::middleware('auth','role:pegawai')->group(function () {
    Route::get('/pegawai/dashboard', function () {
        return view('pegawai.dashboard');
    });
});

Route::get('/login', function () {
    return view('login', ['title' => 'Login Page']);
});
Route::get('/reviu', function () {
    return view('riviu', ['title' => 'Riviu Page']);
});
Route::get('/upload-instrumen', function () {
    return view('upload-instrumen', ['title' => 'Upload Instrumen Page']);
});
Route::get('/cover', function () {
    return view('cover', ['title' => 'Cover Page']);
});
Route::get('/sop-dompdf', function () {
    return view('sop-dompdf');
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

Route::get('/activity-form', [ActivityController::class, 'showForm'])->name('activity.form');
Route::post('/generate-table', [ActivityController::class, 'generateTable'])->name('activity.generate');
