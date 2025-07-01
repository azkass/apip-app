@extends('layouts.app')
@section('content')
    <div class="max-w-4xl mx-auto mt-8 p-6 bg-white shadow-md rounded-lg">
        <h2 class="text-xl font-semibold mb-6 text-gray-800">Tambah Prosedur Pengawasan Baru</h2>
        
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ route('prosedur-pengawasan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                    <input type="text" name="judul" id="judul" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200" required>
                </div>
                
                <div>
                    <label for="nomor" class="block text-sm font-medium text-gray-700 mb-1">Nomor</label>
                    <input type="text" name="nomor" id="nomor" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200" required>
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
            </div>
            
            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
