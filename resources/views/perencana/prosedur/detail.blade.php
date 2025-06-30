@extends('layouts.app')
@section('content')
    @component('components.prosedur.detail', ['prosedurPengawasan' => $prosedurPengawasan])
    @endcomponent

    <!-- Tambahkan container dan tombol untuk graph -->
    <div class="container mt-4 mx-4 border-1 border-black">
        <div id="graphContainerBox">
            <div id="graphContainer"></div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.prosedurDetailData = {!! $prosedurPengawasan->isi ?? '{}' !!};
            });
        </script>

        <!-- Set mxBasePath sebelum load mxClient -->
            <script>
                window.mxBasePath = '/vendor/mxgraph';
            </script>

            <!-- Load mxGraph core -->
            <script src="/vendor/mxgraph/js/mxClient.js"></script>

            <!-- Load Vite script khusus untuk page ini -->
            @vite('resources/js/graph.js')
    @endpush
@endsection
