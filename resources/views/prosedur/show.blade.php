@extends('layouts.app')
@section('content')
    @component('components.prosedur.show', ['prosedurPengawasan' => $prosedurPengawasan])
    @endcomponent

    <div class="flex justify-between items-center mb-6 ml-2 md:ml-16 mr-24 mt-4">
        <h2 class="font-bold text-2xl">Dokumen SOP</h2>
        <button id="printSopBtn" class="px-4 py-1 cursor-pointer bg-green-500 hover:bg-green-600 rounded-sm text-white font-semibold">
            Cetak SOP
        </button>
    </div>

    <div class="ml-2 md:ml-16" style="width: 75%; height: 75%;">
        <div class="container scale-75 origin-top-left">
            <!-- Container untuk cover SOP -->
            <div class="mb-4">
                <div id="coverContainer"></div>
            </div>
            <!-- Container untuk body SOP -->
            <div id="graphContainerBox">
                <div id="graphContainer"></div>
            </div>
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
            document.addEventListener('DOMContentLoaded', function () {
                // Wait for printSOP.js to be loaded before calling setupPrintFunctionality
                function waitForPrintSetup() {
                    if (window.setupPrintFunctionality) {
                        setupPrintFunctionality();
                    } else {
                        setTimeout(waitForPrintSetup, 100);
                    }
                }
                waitForPrintSetup();
            });
        </script>
        <script src="/vendor/mxgraph/js/mxClient.js"></script>
        @vite(['resources/js/graph.js', 'resources/js/editCover.js', 'resources/js/printSOP.js'])
    @endpush
@endsection
