@extends('layouts.app')
@section('content')
<div class="p-8">
    <form action="{{ route('perencana.regulasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="judul" class="font-semibold" >Judul : </label>
            <input type="text" name="judul" id="judul" required>
        </div>
        <div>
            <label for="tautan" class="font-semibold" >Tautan : </label>
            <input type="text" name="tautan" id="tautan" required>
        </div>
        <div class="form-group" class="">
            <label for="pdf" class="font-semibold" >File : </label>
            <input type="file" name="pdf" id="pdf" accept="application/pdf" required>
        </div>
        <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md">Submit</button>
    </form>
</div>
@endsection
