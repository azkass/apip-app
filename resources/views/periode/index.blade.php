@extends('layouts.app')

@section('content')
<div class="container ml-2 sm:ml-8 mt-1 sm:mt-8">
    <div class="flex justify-between items-center">
        @if(Auth::user()->role == 'admin')
        <a href="{{ route('periode.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">
            Tambah Periode
        </a>
        @endif
    </div>
    <div class="bg-white">
        <table class="border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">No</th>
                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Tanggal Mulai</th>
                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Tanggal Berakhir</th>
                    <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Pembuat Periode</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Status</th>
                    @if(Auth::user()->role == 'admin')
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($periode as $index => $item)
                    @php
                        $now = now()->toDateString();
                        $isActive = $now >= $item->mulai && $now <= $item->berakhir;
                        $isPast = $now > $item->berakhir;
                        $isFuture = $now < $item->mulai;
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="border border-gray-300 px-4 py-3 text-center">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-3">
                            {{ \Carbon\Carbon::parse($item->mulai)->format('d M Y') }}
                        </td>
                        <td class="border border-gray-300 px-4 py-3">
                            {{ \Carbon\Carbon::parse($item->berakhir)->format('d M Y') }}
                        </td>
                        <td class="border border-gray-300 px-4 py-3">
                            <div>
                                <div class="font-medium">{{ $item->pembuat_name }}</div>
                                <!-- <div class="text-sm text-gray-500">{{ $item->pembuat_email }}</div> -->
                            </div>
                        </td>
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            @if ($isActive)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @elseif ($isPast)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Berakhir
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Akan Datang
                                </span>
                            @endif
                        </td>

                        @if(Auth::user()->role == 'admin')
                        <td class="border border-gray-300 px-4 py-3 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('periode.edit', $item->id) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                                    Edit
                                </a>
                                @if (Auth::user()->role == 'admin' || Auth::id() == $item->pembuat_id)
                                    <form action="{{ route('periode.destroy', $item->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Yakin ingin menghapus periode ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="border border-gray-300 px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M8 7V3a4 4 0 118 0v4m-4 8a4 4 0 11-8 0v-3h8v3z"/>
                                </svg>
                                <p class="text-lg font-medium">Belum ada periode evaluasi</p>
                                <p class="text-sm">Klik "Tambah Periode" untuk membuat periode evaluasi baru</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 text-sm text-gray-600">
        <div class="flex items-center space-x-4">
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 bg-green-100 rounded-full mr-2"></span>
                <span>Aktif: Periode sedang berjalan</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 bg-yellow-100 rounded-full mr-2"></span>
                <span>Akan Datang: Periode belum dimulai</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 bg-red-100 rounded-full mr-2"></span>
                <span>Berakhir: Periode sudah selesai</span>
            </div>
        </div>
    </div>
</div>
@endsection
