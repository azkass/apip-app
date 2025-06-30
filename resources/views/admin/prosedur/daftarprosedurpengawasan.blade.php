@extends('layouts.app')
@section('content')
    @component('components.prosedur.daftar', [
        'prosedurPengawasan' => $prosedurPengawasan,
        'activeTab' => $activeTab
    ])
    @endcomponent
@endsection
