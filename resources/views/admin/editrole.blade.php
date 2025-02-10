@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Role Pengguna</h1>
    <form action="{{ route('admin.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Edit Role</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        <select name="role" id="role" class="form-control text-center">
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="pjk" {{ $user->role == 'pjk' ? 'selected' : '' }}>Penanggung Jawab Kegiatan</option>
                            <option value="perencana" {{ $user->role == 'perencana' ? 'selected' : '' }}>Perencana</option>
                            <option value="pegawai" {{ $user->role == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                        </select>
                    </td>
                </tr>

        </table>
        </div>
        <button type="submit" class="cursor-pointer">Update Role</button>
        <button href="/admin/listrole" class="cursor-pointer">Batal</button>
    </form>
</div>
@endsection
