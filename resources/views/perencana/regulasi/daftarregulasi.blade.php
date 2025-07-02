@extends('layouts.app')
@section('content')
<div class="container mx-auto p-4">
    <div class="flex items-center mb-6">
        <div class="flex border-2 border-gray-300 overflow-hidden rounded-md h-10 mr-2">
            <input type="text" placeholder="Cari Regulasi Pengawasan" class="w-96 outline-none bg-white text-gray-600 text-sm px-4 py-3" />
        </div>
        <a href="{{ route('perencana.regulasi.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">
            Tambah Regulasi
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">No</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Kode</th>
                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Hasil Kerja</th>
                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Judul</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Dokumen</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Tautan</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($regulasi as $index => $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="border border-gray-300 px-4 py-3 text-center">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-3 text-center">{{ $item->kode }}</td>
                        <td class="border border-gray-300 px-4 py-3">{{ $item->hasil_kerja }}</td>
                        <td class="border border-gray-300 px-4 py-3">
                            {{ $item->judul }}
                        </td>
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            <a href="{{ route('perencana.regulasi.download', $item->id) }}" class="inline-flex px-3 py-1 text-sm font-semibold rounded bg-red-500 hover:bg-red-600 text-white transition">
                                <i class="fas fa-download mr-1"></i> PDF
                            </a>
                        </td>
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            <a href="{{ $item->tautan }}" target="_blank" class="inline-flex px-3 py-1 text-sm font-semibold rounded bg-green-500 hover:bg-green-600 text-white transition">
                                Buka
                            </a>
                        </td>
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route(Auth::user()->role . '.regulasi.detail', $item->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition">
                                    Lihat
                                </a>
                                <a href="{{ route(Auth::user()->role . '.regulasi.edit', $item->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                                    Edit
                                </a>
                                <form action="{{ route('perencana.regulasi.delete', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus regulasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="border border-gray-300 px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-lg font-medium">Belum ada data regulasi</p>
                                <p class="text-sm">Klik "Tambah Regulasi" untuk membuat regulasi baru</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
