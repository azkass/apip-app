@extends('layouts.app')
@section('content')
    @component('components.prosedur.detail', ['prosedurPengawasan' => $prosedurPengawasan])
    @endcomponent
@endsection
