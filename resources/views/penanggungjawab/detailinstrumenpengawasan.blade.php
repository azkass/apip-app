@extends('layouts.app')
@section('content')
    @component('components.instrumen.detail', ['instrumenPengawasan' => $instrumenPengawasan])
    @endcomponent
@endsection
