<div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
    @props(['inspektur_utama', 'is_pjk', 'is_pjk'])
    @if (Auth::user()->role == 'pjk' || Auth::user()->role == 'perencana')
        <form action="{{ route(Auth::user()->role .'.prosedur-pengawasan.update', $prosedurPengawasan->id) }}" method="POST" enctype="multipart/form-data">
    @endif
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Nama SOP</label>
                <input type="text" name="judul" id="judul" value="{{ $prosedurPengawasan->judul }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
            </div>
            
            <div>
                <label for="nomor" class="block text-sm font-medium text-gray-700 mb-1">Nomor SOP</label>
                <input type="text" name="nomor" id="nomor" value="{{ $prosedurPengawasan->nomor }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
            </div>
            
            <div>
                <label for="tanggal_pembuatan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembuatan</label>
                <input type="date" name="tanggal_pembuatan" id="tanggal_pembuatan" value="{{ $prosedurPengawasan->tanggal_pembuatan }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
            </div>
            
            <div>
                <label for="tanggal_revisi" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Revisi</label>
                <input type="date" name="tanggal_revisi" id="tanggal_revisi" value="{{ $prosedurPengawasan->tanggal_revisi }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
            </div>
            
            <div>
                <label for="tanggal_efektif" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Efektif</label>
                <input type="date" name="tanggal_efektif" id="tanggal_efektif" value="{{ $prosedurPengawasan->tanggal_efektif }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
            </div>
            
            <div>
                <label for="disahkan_oleh" class="block text-sm font-medium text-gray-700 mb-1">Disahkan Oleh</label>
                <select name="disahkan_oleh" id="disahkan_oleh" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                    @foreach ($inspektur_utama_nama as $inspektur)
                        <option value="{{ $inspektur->id }}" {{ $prosedurPengawasan->disahkan_oleh == $inspektur->id ? 'selected' : '' }}>
                            {{ $inspektur->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="penyusun_id" class="block text-sm font-medium text-gray-700 mb-1">Petugas Penyusun</label>
                <select name="penyusun_id" id="penyusun_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                    @foreach ($is_pjk as $user)
                        <option value="{{ $user->id }}" {{ $prosedurPengawasan->penyusun_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                    <option value="draft" {{ $prosedurPengawasan->status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="diajukan" {{ $prosedurPengawasan->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="disetujui" {{ $prosedurPengawasan->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                </select>
            </div>
        </div>
        
        <input type="hidden" name="pembuat_id" value="{{ $prosedurPengawasan->pembuat_id }}">
        
        <div class="mt-6">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition">
                Simpan
            </button>
        </div>
    </form>
</div>