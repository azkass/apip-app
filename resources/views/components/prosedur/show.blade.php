<div class="container mx-auto p-4">
    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-gray-600 text-sm font-medium">Nomor SOP</p>
                <p class="text-lg font-semibold">{{ $prosedurPengawasan->nomor }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-medium">Judul</p>
                <p class="text-lg font-semibold">{{ $prosedurPengawasan->judul }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-medium">Status</p>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                    @if($prosedurPengawasan->status == 'draft')
                        bg-yellow-100 text-yellow-800
                    @elseif($prosedurPengawasan->status == 'diajukan')
                        bg-blue-100 text-blue-800
                    @elseif($prosedurPengawasan->status == 'disetujui')
                        bg-green-100 text-green-800
                    @endif">
                    {{ ucfirst($prosedurPengawasan->status) }}
                </span>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-medium">Tanggal Pembuatan</p>
                <p class="text-base">{{ $prosedurPengawasan->tanggal_pembuatan ? date('d/m/Y', strtotime($prosedurPengawasan->tanggal_pembuatan)) : '-' }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-medium">Tanggal Revisi</p>
                <p class="text-base">{{ $prosedurPengawasan->tanggal_revisi ? date('d/m/Y', strtotime($prosedurPengawasan->tanggal_revisi)) : '-' }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-medium">Tanggal Efektif</p>
                <p class="text-base">{{ $prosedurPengawasan->tanggal_efektif ? date('d/m/Y', strtotime($prosedurPengawasan->tanggal_efektif)) : '-' }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-medium">Disahkan Oleh</p>
                <p class="text-base">{{ $prosedurPengawasan->disahkan_oleh_nama }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-medium">Perencana</p>
                <p class="text-base">{{ $prosedurPengawasan->perencana_nama }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-medium">Petugas Pengelola</p>
                <p class="text-base">{{ $prosedurPengawasan->petugas_nama }}</p>
            </div>
        </div>

        <div class="flex space-x-3 mt-6">
            @if (Auth::user()->role == 'pjk' || Auth::user()->role == 'perencana')
                                <a href="{{ route('prosedur-pengawasan.edit', $prosedurPengawasan->id) }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">
                    Edit
                </a>
            @endif
            
            @if (Auth::user()->role == 'perencana')
                                <form action="{{ route('prosedur-pengawasan.delete', $prosedurPengawasan->id) }}" 
                      method="POST" 
                      onsubmit="return confirm('Yakin ingin menghapus prosedur ini?')" 
                      class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md transition">
                        Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
