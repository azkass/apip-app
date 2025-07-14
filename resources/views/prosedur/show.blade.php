@extends('layouts.app')
@section('content')
    @component('components.prosedur.show', ['prosedurPengawasan' => $prosedurPengawasan])
    @endcomponent

    <div class="flex justify-between items-center mb-2 ml-8 mr-8">
        <div class="flex justify-between items-center mb-4 mx-8">
            <h2 class="font-bold text-lg">Dokumen SOP</h2>
            <button id="downloadPdf" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Download PDF
            </button>
        </div>
        <button id="downloadPdf" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Download PDF
        </button>
    </div>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('downloadPdf').addEventListener('click', function () {
                    const { jsPDF } = window.jspdf;
                    const coverContainer = document.getElementById('coverContainer');
                    const graphContainer = document.getElementById('graphContainer');

                    // Use html2canvas to capture the containers
                    html2canvas(coverContainer, { scale: 2 }).then(coverCanvas => {
                        html2canvas(graphContainer, { scale: 2 }).then(graphCanvas => {
                            const coverImage = coverCanvas.toDataURL('image/png');
                            const graphImage = graphCanvas.toDataURL('image/png');

                            const pdf = new jsPDF({
                                orientation: 'landscape',
                                unit: 'pt',
                                format: 'legal'
                            });

                            const pdfWidth = pdf.internal.pageSize.getWidth();
                            const pdfHeight = pdf.internal.pageSize.getHeight();

                            // Add cover image
                            pdf.addImage(coverImage, 'PNG', 0, 0, pdfWidth, pdfHeight);

                            // Add new page for the graph
                            pdf.addPage();
                            pdf.addImage(graphImage, 'PNG', 0, 0, pdfWidth, pdfHeight);

                            pdf.save('SOP-Dokumen.pdf');
                        });
                    });
                });
            });
        </script>
        @vite(['resources/js/graph.js', 'resources/js/editCover.js', 'resources/js/download.js'])
    @endpush
@endsection
