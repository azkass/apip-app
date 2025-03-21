@extends('layouts.app')
@section('content')
    <h1>Buat Instrumen Pengawasan</h1>
    <div class="container">
        <form action="{{ route('instrumen-pengawasan.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="judul">Judul</label>
                <input type="text" name="judul" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="petugas_pengelola_id">Petugas Pengelola</label>
                <select name="petugas_pengelola_id" class="form-control" required>
                    @foreach ($is_pjk as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="isi">Isi</label>
                <textarea name="isi" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control" required>
                    <option value="draft">Draft</option>
                    <option value="diajukan">Diajukan</option>
                    <option value="disetujui">Disetujui</option>
                </select>
            </div>
            <div class="form-group">
                <label for="perencana_id">Perencana</label>
                <select name="perencana_id" class="form-control" required>
                    @foreach ($is_perencana as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="">Submit</button>
        </form>

@endsection
