    @extends('layouts.app')
@section('content')
    @component('components.prosedur.edit', ['prosedurPengawasan' => $prosedurPengawasan,'is_pjk' => $is_pjk, 'inspektur_utama_nama' => $inspektur_utama_nama])
    @endcomponent
@endsection
