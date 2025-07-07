@extends('layouts.app')
@section('content')
    @component('components.prosedur.detail', ['prosedurPengawasan' => $prosedurPengawasan])
    @endcomponent

    <!-- Container untuk cover SOP -->
    <div class="container mt-4 mx-4">
        <div class="mb-8">
            <h2 class="font-bold text-lg mb-2">Dokumen SOP</h2>
            <div id="coverContainer"></div>
        </div>
        <!-- Container dan tombol untuk graph -->
        <div id="graphContainerBox">
            <div id="graphContainer"></div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.prosedurDetailData = {!! $prosedurPengawasan->isi ?? '{}' !!};
                // Ambil data cover dari backend dan render cover
                const id = {{ $prosedurPengawasan->id }};
                axios.get(`/perencana/prosedur-pengawasan/${id}/cover-data`).then(resp => {
                    function tryRenderCover() {
                        if (window.generateCoverMxGraph) {
                            generateCoverMxGraph(resp.data);
                        } else {
                            setTimeout(tryRenderCover, 100);
                        }
                    }
                    tryRenderCover();
                });
            });
        </script>
        <script>
            window.mxBasePath = '/vendor/mxgraph';
        </script>
        <script src="/vendor/mxgraph/js/mxClient.js"></script>
        @vite(['resources/js/graph.js', 'resources/js/editCover.js'])
    @endpush
@endsection
