@extends('layouts.app')
@section('content')
    @component('components.instrumen.daftar', [
        'instrumenPengawasan' => $instrumenPengawasan,
        'activeTab' => $activeTab
    ])
    @endcomponent
@endsection
