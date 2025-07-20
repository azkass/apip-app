@extends('layouts.app')

@section('content')
<div class="container p-8">
    <div class="bg-white rounded-lg">
        <h1 class="text-2xl font-semibold mb-4">Edit Role Pengguna</h1>
        <table class="w-full border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">ID</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Nama</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Email</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Role</th>
                    <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="border border-gray-300 px-4 py-3 text-center">{{$user->id}}</td>
                    <td class="border border-gray-300 px-4 py-3">{{ $user->name }}</td>
                    <td class="border border-gray-300 px-4 py-3">{{ $user->email }}</td>
                    <td class="border border-gray-300 px-4 py-3 text-center">
                        @switch($user->role)
                            @case('admin')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Admin
                                </span>
                                @break
                            @case('pjk')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Penanggung Jawab Kegiatan
                                </span>
                                @break
                            @case('perencana')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Perencana
                                </span>
                                @break
                            @case('pegawai')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pegawai
                                </span>
                                @break
                            @default
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($user->role) }}
                                </span>
                        @endswitch
                    </td>
                    <td class="border border-gray-300 px-4 py-3 text-center">
                        <a href="{{ route('admin.editrole', $user->id) }}"
                           class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                            Edit Role
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
