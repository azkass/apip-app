@extends('layouts.app')
@section('content')
    @component('components.prosedur.index', [
        'prosedurPengawasan' => $prosedurPengawasan,
        'activeTab' => $activeTab
    ])
    @endcomponent
@endsection
