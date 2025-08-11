@extends('layouts.app')

@section('content')
<div class="w-full p-8">
    <h1 class="text-2xl font-bold mb-4">Daftar Evaluasi Prosedur Pengawasan</h1>
    <div class="bg-white rounded-lg">
        <table class="border-collapse w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold w-12">No</th>
                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold w-28">Nomor SOP</th>
                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Nama SOP</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold w-28">Hasil</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold w-32">Pembuat</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold w-36">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($groupedData as $index => $evaluasi)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="border border-gray-300 px-4 py-3 text-center">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-3">{{ $evaluasi->sop_nomor }}</td>
                        <td class="border border-gray-300 px-4 py-3">{{ $evaluasi->sop_nama }}</td>
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
                                <a href="{{ route('monitoring-evaluasi.show', $evaluasi->id) }}"
                                   class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition">
                                    Lihat
                                </a>
                                <a href="{{ route('monitoring-evaluasi.edit', $evaluasi->id) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                                    Edit
                                </a>
                                <form action="{{ route('monitoring-evaluasi.destroy', $evaluasi->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="border border-gray-300 px-4 py-3 text-center">
                            Belum ada data monitoring evaluasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
