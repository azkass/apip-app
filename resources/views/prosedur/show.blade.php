@extends('layouts.app')
@section('content')
    @component('components.prosedur.show', ['prosedurPengawasan' => $prosedurPengawasan])
    @endcomponent

    <h2 class="font-bold text-lg mb-2 ml-8">Dokumen SOP</h2>
    <div class="container mt-4 mx-4">
        <!-- Container untuk cover SOP -->
        <div class="mb-8">
            <div id="coverContainer"></div>
        </div>
        <!-- Container untuk body SOP -->
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
                axios.get(`/prosedur-pengawasan/${id}/cover-data`).then(resp => {
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
