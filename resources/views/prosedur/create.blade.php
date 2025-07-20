@extends('layouts.app')
@section('content')
    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-md rounded-lg">
        <form action="{{ route('prosedur-pengawasan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama SOP</label>
                <input type="text" name="nama" id="nama" autocomplete="off"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200" required>
            </div>

            <div>
                <label for="nomor" class="block text-sm font-medium text-gray-700 mb-1">Nomor</label>
                <input type="text" name="nomor" id="nomor" autocomplete="off"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200" required>
            </div>

            <div>
                <label for="tanggal_pembuatan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembuatan</label>
                <input type="date" name="tanggal_pembuatan" id="tanggal_pembuatan" autocomplete="off"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
            </div>

            <div>
                <label for="tanggal_revisi" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Revisi</label>
                <input type="date" name="tanggal_revisi" id="tanggal_revisi" autocomplete="off"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
            </div>

            <div>
                <label for="tanggal_efektif" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Efektif</label>
                <input type="date" name="tanggal_efektif" id="tanggal_efektif" autocomplete="off"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
            </div>

            <div>
                <label for="disahkan_oleh" class="block text-sm font-medium text-gray-700 mb-1">Disahkan Oleh</label>
                <select name="disahkan_oleh" id="disahkan_oleh" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                    @foreach ($inspektur_utama as $inspektur)
                        <option value="{{ $inspektur->id }}">{{ $inspektur->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="penyusun_id" class="block text-sm font-medium text-gray-700 mb-1">Petugas Penyusun</label>
                <select name="penyusun_id" id="penyusun_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200" required>
                    @foreach ($is_pjk as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200" required>
                    <option value="draft">Draft</option>
                    <option value="diajukan">Diajukan</option>
                    <option value="revisi">Revisi</option>
                    <option value="menunggu disetujui">Menunggu Disetujui</option>
                    <option value="disetujui">Disetujui</option>
                </select>
            </div>

            <div>
                <label for="pembuat_id" class="block text-sm font-medium text-gray-700 mb-1">Perencana</label>
                <select name="pembuat_id" id="pembuat_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200" required>
                    @foreach ($is_perencana as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
