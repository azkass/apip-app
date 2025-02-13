@extends('layouts.app')
@section('content')
    @component('components.instrumen.edit', ['instrumenPengawasan' => $instrumenPengawasan, 'users' => $users])
    @endcomponent
@endsection
