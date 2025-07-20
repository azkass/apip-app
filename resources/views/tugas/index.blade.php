@extends('layouts.app')
@section('content')
<div class="container p-8">
    <div class="mb-8">
        <div class="flex justify-between mb-4">
            <h1 class="font-bold text-2xl">Prosedur Pengawasan</h1>
            @if (Auth::user()->role == 'perencana')
            <a href="{{ route('prosedur-pengawasan.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">Tambah</a>
            @endif
        </div>
        @include('components.prosedur.tablecard', ['prosedurPengawasan' => $prosedurPengawasan])
    </div>
    <div>
        <div class="flex justify-between mb-4">
            <h1 class="font-bold text-2xl">Instrumen Pengawasan</h1>
            @if (Auth::user()->role == 'perencana')
            <a href="{{ route('instrumen-pengawasan.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">Tambah</a>
            @endif
        </div>
        @include('components.instrumen.tablecard', ['instrumenPengawasan' => $instrumenPengawasan])
    </div>
</div>

@endsection
