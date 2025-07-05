@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold">Daftar Evaluasi Prosedur</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold w-12">No</th>
                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold w-28">Nomor SOP</th>
                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Judul SOP</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold w-28">Hasil</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold w-32">Pembuat</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold w-36">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $index => $evaluasi)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="border border-gray-300 px-4 py-3 text-center">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-3">{{ $evaluasi->sop_nomor }}</td>
                        <td class="border border-gray-300 px-4 py-3">{{ Str::limit($evaluasi->sop_judul, 50) }}</td>
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            @php
                                $persentase = $evaluasi->jumlah_pertanyaan > 0 
                                    ? ($evaluasi->jawaban_ya / $evaluasi->jumlah_pertanyaan) * 100 
                                    : 0;
                                $colorClass = $persentase >= 70 
                                    ? 'bg-green-100 text-green-800' 
                                    : ($persentase >= 50 
                                        ? 'bg-yellow-100 text-yellow-800' 
                                        : 'bg-red-100 text-red-800');
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colorClass }}">
                                {{ number_format($persentase, 1) }}% ({{ $evaluasi->jawaban_ya }}/{{ $evaluasi->jumlah_pertanyaan }})
                            </span>
                        </td>
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            <div class="font-medium">{{ $evaluasi->penyusun_nama }}</div>
                        </td>
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('evaluasi.show', $evaluasi->sop_id) }}"
                                   class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition">
                                    Lihat
                                </a>
                                <a href="{{ route('evaluasi.edit', $evaluasi->sop_id) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                                    Edit
                                </a>
                                <form action="{{ route('evaluasi.destroy', $evaluasi->sop_id) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Yakin ingin menghapus evaluasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="border border-gray-300 px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-lg font-medium">Belum ada data evaluasi</p>
                                <p class="text-sm">Silahkan pilih SOP untuk mengevaluasi</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
