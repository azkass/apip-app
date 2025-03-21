@extends('layouts.app')
@section('content')
    @component('components.instrumen.daftar', ['instrumenPengawasan' => $instrumenPengawasan])
    @endcomponent
@endsection
