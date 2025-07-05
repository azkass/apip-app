<?php

namespace App\Http\Controllers;

use App\Models\PertanyaanEvaluasi;
use Illuminate\Http\Request;

class PertanyaanEvaluasiController extends Controller
{    
    /**
     * Display a listing of pertanyaan evaluasi
     */
    public function index()
    {
        $pertanyaan = PertanyaanEvaluasi::getAll();
        return view('admin.pertanyaan.index', compact('pertanyaan'));
    }

    /**
     * Show the form for creating a new pertanyaan
     */
    public function create()
    {
        return view('admin.pertanyaan.create');
    }

    /**
     * Store a newly created pertanyaan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:255',
        ]);

        PertanyaanEvaluasi::create($validated);

        return redirect()->route('pertanyaan.index')
            ->with('success', 'Pertanyaan evaluasi berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified pertanyaan
     */
    public function edit($id)
    {
        $pertanyaan = PertanyaanEvaluasi::find($id);
        
        if (!$pertanyaan) {
            return redirect()->route('pertanyaan.index')
                ->with('error', 'Pertanyaan tidak ditemukan.');
        }
        
        return view('admin.pertanyaan.edit', compact('pertanyaan'));
    }

    /**
     * Update the specified pertanyaan
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pertanyaan' => 'required|string|max:255',
        ]);

        if (!PertanyaanEvaluasi::find($id)) {
            return redirect()->route('pertanyaan.index')
                ->with('error', 'Pertanyaan tidak ditemukan.');
        }

        PertanyaanEvaluasi::update($id, $validated);

        return redirect()->route('pertanyaan.index')
            ->with('success', 'Pertanyaan evaluasi berhasil diperbarui.');
    }

    /**
     * Remove the specified pertanyaan
     */
    public function destroy($id)
    {
        if (!PertanyaanEvaluasi::find($id)) {
            return redirect()->route('pertanyaan.index')
                ->with('error', 'Pertanyaan tidak ditemukan.');
        }
        
        PertanyaanEvaluasi::destroy($id);

        return redirect()->route('pertanyaan.index')
            ->with('success', 'Pertanyaan evaluasi berhasil dihapus.');
    }
} 