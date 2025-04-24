@extends('layouts.app')

@section('content')
<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th class="w-8">ID</th>
                <th class="w-40">Nama</th>
                <th class="w-64">Email</th>
                <th class="">Role</th>
                <th class="w-40">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td class="text-center">{{$user->id}}</td>
                <td class="pl-4">{{ $user->name }}</td>
                <td class="pl-4">{{ $user->email }}</td>
                <td>{{ ucfirst($user->role) }}</td>
                <td class="text-center">
                    <a href="{{ route('admin.editrole', $user->id) }}" class="">Edit Role</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
