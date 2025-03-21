@extends('layouts.app')
@section('content')
    @component('components.instrumen.edit', ['instrumenPengawasan' => $instrumenPengawasan,'is_pjk' => $is_pjk])
    @endcomponent
@endsection
