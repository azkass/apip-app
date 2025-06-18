@extends('layouts.app')
@section('content')
    <div class="p-8">
        <form action="{{ route('prosedur-pengawasan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="judul" class="font-bold">Judul : </label>
                <input type="text" name="judul" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nomor" class="font-bold">Nomor : </label>
                <input type="text" name="nomor" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="pengelola_id" class="font-bold">Petugas Pengelola : </label>
                <select name="pengelola_id" class="form-control" required>
                    @foreach ($is_pjk as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="status" class="font-bold">Status : </label>
                <select name="status" class="form-control" required>
                    <option value="draft">Draft</option>
                    <option value="diajukan">Diajukan</option>
                    <option value="disetujui">Disetujui</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pembuat_id" class="font-bold">Perencana : </label>
                <select name="pembuat_id" class="form-control" required>
                    @foreach ($is_perencana as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md">Simpan</button>
        </form>
    </div>
@endsection
