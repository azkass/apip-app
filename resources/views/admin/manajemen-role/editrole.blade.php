@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Edit Role Pengguna</h1>
    </div>
    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form action="{{ route('admin.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <div class="bg-gray-50 p-4 rounded-md mb-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ID</label>
                            <div class="text-lg font-medium">{{$user->id}}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                            <div class="text-lg font-medium">{{ $user->name }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <div class="text-lg font-medium">{{ $user->email }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role Saat Ini</label>
                            <div class="text-lg font-medium">{{ ucfirst($user->role) }}</div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Pilih Role Baru</label>
                    <select name="role" id="role"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pjk" {{ $user->role == 'pjk' ? 'selected' : '' }}>Penanggung Jawab Kegiatan</option>
                        <option value="perencana" {{ $user->role == 'perencana' ? 'selected' : '' }}>Perencana</option>
                        <option value="pegawai" {{ $user->role == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                    </select>
                </div>
            </div>

            <div class="flex space-x-4">
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    Update Role
                </button>
                <a href="{{ route('admin.list') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
