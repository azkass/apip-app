@extends('layouts.app')
@section('content')
    @component('components.prosedur.edit', ['prosedurPengawasan' => $prosedurPengawasan,'is_pjk' => $is_pjk])
    @endcomponent
@endsection
