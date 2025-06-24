@extends('layouts.app')
@section('content')
    @component('components.prosedur.detail', ['prosedurPengawasan' => $prosedurPengawasan])
    @endcomponent

    <!-- Tambahkan container dan tombol untuk graph -->
    <div class="container mt-4 px-4">
        <div id="graphContainerBox">
            <div id="graphContainer"></div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
            // Data statis untuk contoh
            // const staticGraphData = {"nActor":2,"actorName":["Inspektur Wilayah","Pengendali Teknis"],"nActivity":2,"rowHeights":[80,80],"activities":["",""],"tools":["",""],"times":["",""],"outputs":["",""],"notes":["",""],"graphLocation":[[1,0],[0,1]],"graphShape":[["state",0],[0,"process"]],"shape":[[{"id":"38","value":"","geometry":{"x":185,"y":105,"width":50,"height":30}},null],[null,{"id":"44","value":"","geometry":{"x":285,"y":187.5,"width":50,"height":25}}],[null,null],[null,null]],"falseData":[[null,null],[null,null]],"actorLoc":[[{"id":"20","value":"","geometry":{"x":0,"y":0,"width":100,"height":80}},{"id":"21","value":"","geometry":{"x":100,"y":0,"width":100,"height":80}}],[{"id":"31","value":"","geometry":{"x":0,"y":0,"width":100,"height":80}},{"id":"32","value":"","geometry":{"x":100,"y":0,"width":100,"height":80}}],[null,null]]};
            const staticGraphData = {!! $prosedurPengawasan->isi ?? '{}' !!};

            // Fungsi untuk generate graph
                // Assign data statis ke variabel global
                window.nActor = staticGraphData.nActor;
                window.actorName = staticGraphData.actorName;
                window.nActivity = staticGraphData.nActivity;
                window.rowHeights = staticGraphData.rowHeights;
                window.activities = staticGraphData.activities;
                window.tools = staticGraphData.tools;
                window.times = staticGraphData.times;
                window.outputs = staticGraphData.outputs;
                window.notes = staticGraphData.notes;
                window.graphLocation = staticGraphData.graphLocation;
                window.graphShape = staticGraphData.graphShape;
                window.shape = staticGraphData.shape;
                window.falseData = staticGraphData.falseData;
                window.actorLoc = staticGraphData.actorLoc;


                // Panggil fungsi draw() dengan container dan range activity
                const container = document.getElementById('graphContainer');
                draw(container, 1, staticGraphData.nActivity);
                console.log("data db:", typeof staticGraphData);
                console.log("data db:", staticGraphData);
            });


            function draw(container, start, end) {
                // =====================================
                // 1. Inisialisasi dan Konfigurasi Dasar

                // Reset the preview container
                container.innerHTML = "";
                // let pageSize = 5;

                // Checks if the browser is supported
                if (!mxClient.isBrowserSupported()) {
                    // Displays an error message if the browser is not supported.
                    mxUtils.error("Browser is not supported!", 200, false);
                } else {
                    // ORTH
                    // Enables guides
                    mxGraphHandler.prototype.guidesEnabled = true;

                    // Alt disables guides
                    mxGuide.prototype.isEnabledForEvent = function (evt) {
                        return !mxEvent.isAltDown(evt);
                    };

                    // Enables snapping waypoints to terminals
                    mxEdgeHandler.prototype.snapToTerminals = true;

                    // Enables orthogonal connect preview in IE
                    mxConnectionHandler.prototype.movePreviewAway = false;

                    // Disables the built-in context menu
                    mxEvent.disableContextMenu(container);

                    // =====================================
                    // 2. Setup Graph Utama
                    graph = new mxGraph(container);
                    graph.setHtmlLabels(true);
                    // Graph configure for Contstraint
                    graph.disconnectOnMove = false;
                    graph.foldingEnabled = false;
                    graph.cellsResizable = false;
                    graph.extendParents = false;
                    graph.setConnectable(true);
                    // Implements perimeter-less connection points as fixed points (computed before the edge style).
                    graph.view.updateFixedTerminalPoint = function (
                        edge,
                        terminal,
                        source,
                        constraint,
                    ) {
                        mxGraphView.prototype.updateFixedTerminalPoint.apply(
                            this,
                            arguments,
                        );

                        var pts = edge.absolutePoints;
                        var pt = pts[source ? 0 : pts.length - 1];

                        if (
                            terminal != null &&
                            pt == null &&
                            this.getPerimeterFunction(terminal) == null
                        ) {
                            edge.setAbsoluteTerminalPoint(
                                new mxPoint(
                                    this.getRoutingCenterX(terminal),
                                    this.getRoutingCenterY(terminal),
                                ),
                                source,
                            );
                        }
                    };

                    graph.isCellEditable = function (cell) {
                        return !this.model.isEdge(cell);
                    };

                    // ==============================
                    // 3. Definisi Shape

                    // Deklarasi process shape (persegi panjang)
                    var style = graph.getStylesheet().getDefaultVertexStyle();
                    style[mxConstants.STYLE_SHAPE] = mxConstants.SHAPE_RECTANGLE;
                    style[mxConstants.STYLE_PERIMETER] = mxPerimeter.RectanglePerimeter;
                    style[mxConstants.STYLE_FONTSIZE] = 10;
                    style[mxConstants.STYLE_ROUNDED] = false;
                    style[mxConstants.STYLE_VERTICAL_ALIGN] = "middle";
                    style[mxConstants.STYLE_MOVEABLE] = 0;
                    style[mxConstants.STYLE_RESIZABLE] = 0;
                    style[mxConstants.STYLE_EDITABLE] = 0;
                    style[mxConstants.STYLE_FONTCOLOR] = "black";
                    style[mxConstants.STYLE_STROKECOLOR] = "black";
                    style[mxConstants.STYLE_SPACING_TOP] = 5;
                    style[mxConstants.STYLE_SPACING_LEFT] = 5;
                    style[mxConstants.STYLE_SPACING_RIGHT] = 5;
                    style[mxConstants.STYLE_SPACING_BOTTOM] = 5;
                    style[mxConstants.STYLE_FILLCOLOR] = "white";
                    style[mxConstants.STYLE_WHITE_SPACE] = "wrap";
                    graph.getStylesheet().putCellStyle("process", style);

                    // Deklarasi process text
                    style = mxUtils.clone(style);
                    style[mxConstants.STYLE_VERTICAL_ALIGN] = "top";
                    style[mxConstants.STYLE_ALIGN] = "left";
                    graph.getStylesheet().putCellStyle("process_text", style);

                    // Deklarasi start shape (oval)
                    style = mxUtils.clone(style);
                    style[mxConstants.STYLE_SHAPE] = mxConstants.SHAPE_RECTANGLE; // Ubah dari ELLIPSE ke RECTANGLE
                    style[mxConstants.STYLE_ROUNDED] = true; // Tambahkan rounded corners
                    style[mxConstants.STYLE_ARCSIZE] = 50; // Nilai kelengkungan sudut
                    style[mxConstants.STYLE_PERIMETER] = mxPerimeter.RectanglePerimeter; // Tambahkan perimeter
                    delete style[mxConstants.STYLE_STARTSIZE];
                    style[mxConstants.STYLE_FONTCOLOR] = "black";
                    style[mxConstants.STYLE_STROKECOLOR] = "black";
                    style[mxConstants.STYLE_LABEL_BACKGROUNDCOLOR] = "white";
                    style[mxConstants.STYLE_FILLCOLOR] = "white";
                    graph.getStylesheet().putCellStyle("state", style);

                    // Deklarasi condition shape (belah ketupat)
                    style = mxUtils.clone(style);
                    style[mxConstants.STYLE_SHAPE] = mxConstants.SHAPE_RHOMBUS;
                    style[mxConstants.STYLE_PERIMETER] = mxPerimeter.RhombusPerimeter;
                    style[mxConstants.STYLE_VERTICAL_ALIGN] = "top";
                    style[mxConstants.STYLE_ASPECT] = "fixed";
                    delete style[mxConstants.STYLE_ROUNDED];
                    style[mxConstants.STYLE_SPACING_TOP] = 40;
                    style[mxConstants.STYLE_SPACING_RIGHT] = 40;
                    graph.getStylesheet().putCellStyle("condition", style);

                    // Deklarasi konektor ke halaman selanjutnya
                    style = mxUtils.clone(style);
                    style[mxConstants.STYLE_SHAPE] = mxConstants.SHAPE_IMAGE;
                    style[mxConstants.STYLE_PERIMETER] = mxPerimeter.RectanglePerimeter;
                    style[mxConstants.STYLE_IMAGE] =
                        "https://jgraph.github.io/mxgraph/javascript/examples/images/offpage.png";
                    style[mxConstants.STYLE_FONTSIZE] = 14;
                    style[mxConstants.STYLE_FONTSTYLE] = 1;
                    delete style[mxConstants.STYLE_SPACING_RIGHT];
                    graph.getStylesheet().putCellStyle("off-page", style);

                    // Deklarasi panah
                    style = graph.getStylesheet().getDefaultEdgeStyle();
                    style[mxConstants.STYLE_EDGE] = "orthogonalEdgeStyle";
                    style[mxConstants.STYLE_ENDARROW] = mxConstants.ARROW_BLOCK;
                    style[mxConstants.STYLE_ROUNDED] = false;
                    delete style[mxConstants.STYLE_FILLCOLOR];
                    style[mxConstants.STYLE_FONTCOLOR] = "black";
                    style[mxConstants.STYLE_STROKECOLOR] = "black";
                    style[mxConstants.STYLE_LABEL_BACKGROUNDCOLOR] = "white";
                    //style[mxConstants.STYLE_VERTICAL_LABEL_POSITION] = 'ALIGN_BOTTOM';

                    // Deklarasi garis
                    style = mxUtils.clone(style);
                    style[mxConstants.STYLE_EDGE] = mxEdgeStyle.SideToSide;
                    graph.getStylesheet().putCellStyle("side", style);

                    // Implements the connect preview
                    graph.connectionHandler.createEdgeState = function (me) {
                        var edge = graph.createEdge(null, null, null, null, null);

                        return new mxCellState(
                            this.graph.view,
                            edge,
                            this.graph.getCellStyle(edge),
                        );
                    };

                    // Gets the default parent for inserting new cells. This
                    // is normally the first child of the root (ie. layer 0).
                    var parent = graph.getDefaultParent();

                    // ====================================
                    // 4. Pembuatan Struktur Garis Tabel

                    // Adds cells to the model in a single step
                    graph.getModel().beginUpdate();
                    try {
                        // Deklarasi Font Times New Roman
                        const vertexStyle = graph.getStylesheet().getDefaultVertexStyle();
                        vertexStyle[mxConstants.STYLE_FONTSIZE] = 14;
                        vertexStyle[mxConstants.STYLE_FONTFAMILY] = "Times New Roman";

                        // Menentukan ukuran dan tata letak semua komponen tabel.
                        var xPointer = 0; // Penanda posisi X saat ini
                        var yPointer = 0; // Penanda posisi Y saat ini

                        // Menentukan lebar kolom
                        var wBase = 100; // Lebar dasar per aktor
                        var wNo = 40; // Lebar kolom "No."
                        var wAct = 120; // Lebar kolom "Aktivitas"
                        var wActor = wBase * nActor; // Lebar total kolom "Pelaksana"
                        var wMutu = wBase * 3; // Lebar kolom "Mutu Baku" (3 sub-kolom)
                        var wNote = 120; // Lebar kolom "Keterangan"
                        var wTotal = wNo + wAct + wActor + wMutu + wNote; // Lebar total tabel

                        // Menentukan tinggi setiap baris
                        var yHeadTop = 25; // Tinggi bagian atas header
                        var yHeadBottom = 55; // Tinggi bagian bawah header
                        var yHead = yHeadTop + yHeadBottom; // Tinggi total header
                        var yOffPage = 50; // Tinggi connector off-page

                        // Menghitung tinggi total tabel
                        var yTotal = yHead; // Tinggi total tabel
                        for (let i = start; i <= end; i++) {
                            yTotal = yTotal + rowHeights[i - 1];
                        }
                        if (start != 1) {
                            yTotal = yTotal + yOffPage;
                        }
                        if (end != nActivity) {
                            yTotal = yTotal + yOffPage;
                        }

                        // Pembuatan Container Utama
                        var pool = graph.insertVertex(
                            parent,
                            null,
                            "",
                            xPointer,
                            yPointer,
                            wTotal,
                            yTotal,
                            "strokeColor=none;",
                        );
                        var fcPool = graph.insertVertex(
                            parent,
                            null,
                            "",
                            xPointer,
                            yPointer,
                            wTotal,
                            yTotal,
                            "fillOpacity=0;strokeColor=none;",
                        );
                        var notouch = graph.insertVertex(
                            parent,
                            null,
                            "",
                            xPointer,
                            yPointer,
                            wTotal,
                            yTotal,
                            "fillOpacity=0;editable=0;movable=0;strokeColor=none;",
                        );
                        pool.setConnectable(false);

                        // Pembuatan Garis Header Tabel
                        var lane1 = graph.insertVertex(
                            pool,
                            null,
                            "",
                            xPointer,
                            yPointer,
                            wTotal,
                            yHead,
                        );

                        // Pembuatan garis kolom pada header tabel
                        var no = graph.insertVertex(
                            lane1,
                            null,
                            "No.",
                            xPointer,
                            yPointer,
                            wNo,
                            yHead,
                        );
                        xPointer = xPointer + wNo;
                        var act = graph.insertVertex(
                            lane1,
                            null,
                            "Aktivitas",
                            xPointer,
                            yPointer,
                            wAct,
                            yHead,
                        );
                        xPointer = xPointer + wAct;
                        var actor = graph.insertVertex(
                            lane1,
                            null,
                            "Pelaksana",
                            xPointer,
                            yPointer,
                            wActor,
                            yHead,
                            "verticalAlign=top",
                        );
                        var actorList = [0];
                        for (var i = 1; i <= nActor; i++) {
                            actorList[i] = graph.insertVertex(
                                actor,
                                null,
                                actorName[i - 1],
                                (i - 1) * wBase,
                                yHeadTop,
                                wBase,
                                yHeadBottom,
                            );
                        }
                        xPointer = xPointer + wActor;
                        var mutubaku = graph.insertVertex(
                            lane1,
                            null,
                            "Mutu Baku",
                            xPointer,
                            yPointer,
                            wMutu,
                            yHead,
                            "verticalAlign=top",
                        );
                        var syarat = graph.insertVertex(
                            mutubaku,
                            null,
                            "Kelengkapan",
                            0,
                            yHeadTop,
                            wBase,
                            yHeadBottom,
                        );
                        var waktu = graph.insertVertex(
                            mutubaku,
                            null,
                            "Waktu",
                            wBase,
                            yHeadTop,
                            wBase,
                            yHeadBottom,
                        );
                        var keluaran = graph.insertVertex(
                            mutubaku,
                            null,
                            "Output",
                            2 * wBase,
                            yHeadTop,
                            wBase,
                            yHeadBottom,
                        );
                        xPointer = xPointer + wMutu;
                        var ket = graph.insertVertex(
                            lane1,
                            null,
                            "Keterangan",
                            xPointer,
                            yPointer,
                            wNote,
                            yHead,
                        );

                        var yTemp = yPointer + yHead; // Posisi Y baru setelah header

                        // Pembuatan Body Tabel
                        //actorLoc = createArray(nActivity + 1, nActor);

                        // Start Off Page
                        if (start != 1) {
                            xPointer = 0;
                            yPointer = 0;

                            // Pembuatan off-page connectors:
                            var lane = graph.insertVertex(
                                pool,
                                null,
                                "",
                                xPointer,
                                yTemp,
                                wTotal,
                                yOffPage,
                            );

                            var no = graph.insertVertex(
                                lane,
                                null,
                                "",
                                xPointer,
                                yPointer,
                                wNo,
                                yOffPage,
                            );
                            xPointer = xPointer + wNo;
                            var act = graph.insertVertex(
                                lane,
                                null,
                                "",
                                xPointer,
                                yPointer,
                                wAct,
                                yOffPage,
                                "process_text",
                            );
                            xPointer = xPointer + wAct;
                            var actor = graph.insertVertex(
                                lane,
                                null,
                                "",
                                xPointer,
                                yPointer,
                                wActor,
                                yOffPage,
                                "process_text",
                            );
                            var topRow = [];
                            for (var j = 0; j < nActor; j++) {
                                topRow[j] = graph.insertVertex(
                                    actor,
                                    null,
                                    "",
                                    j * wBase,
                                    yPointer,
                                    wBase,
                                    yOffPage,
                                );
                            }
                            xPointer = xPointer + wActor;
                            var mutubaku = graph.insertVertex(
                                lane,
                                null,
                                "",
                                xPointer,
                                yPointer,
                                wMutu,
                                yOffPage,
                                "process_text",
                            );
                            var syarat = graph.insertVertex(
                                mutubaku,
                                null,
                                "",
                                0,
                                yPointer,
                                wBase,
                                yOffPage,
                                "process_text",
                            );
                            var waktu = graph.insertVertex(
                                mutubaku,
                                null,
                                "",
                                wBase,
                                yPointer,
                                wBase,
                                yOffPage,
                                "process_text",
                            );
                            var keluaran = graph.insertVertex(
                                mutubaku,
                                null,
                                "",
                                2 * wBase,
                                yPointer,
                                wBase,
                                yOffPage,
                                "process_text",
                            );
                            xPointer = xPointer + wMutu;
                            var ket = graph.insertVertex(
                                lane,
                                null,
                                "",
                                xPointer,
                                yPointer,
                                wNote,
                                yOffPage,
                                "process_text",
                            );
                            yTemp = yTemp + yOffPage;
                        }

                        // Data Row
                        for (var i = start; i <= end; i++) {
                            xPointer = 0;
                            yPointer = 0;
                            var activity = activities[i - 1];
                            var tool = tools[i - 1];
                            var time = times[i - 1];
                            var output = outputs[i - 1];
                            var note = notes[i - 1];

                            var yBaseTemp = rowHeights[i - 1];

                            var lane = graph.insertVertex(
                                pool,
                                null,
                                "",
                                xPointer,
                                yTemp,
                                wTotal,
                                yBaseTemp,
                            );
                            var no = graph.insertVertex(
                                lane,
                                null,
                                i,
                                xPointer,
                                yPointer,
                                wNo,
                                yBaseTemp,
                            );
                            xPointer = xPointer + wNo;
                            var act = graph.insertVertex(
                                lane,
                                null,
                                activity,
                                xPointer,
                                yPointer,
                                wAct,
                                yBaseTemp,
                                "process_text",
                            );
                            xPointer = xPointer + wAct;
                            var actor = graph.insertVertex(
                                lane,
                                null,
                                "",
                                xPointer,
                                yPointer,
                                wActor,
                                yBaseTemp,
                                "process_text",
                            );
                            for (var j = 0; j < nActor; j++) {
                                actorLoc[i - 1][j] = graph.insertVertex(
                                    actor,
                                    null,
                                    "",
                                    j * wBase,
                                    yPointer,
                                    wBase,
                                    yBaseTemp,
                                );
                            }
                            xPointer = xPointer + wActor;
                            var mutubaku = graph.insertVertex(
                                lane,
                                null,
                                "",
                                xPointer,
                                yPointer,
                                wMutu,
                                yBaseTemp,
                                "process_text",
                            );
                            var syarat = graph.insertVertex(
                                mutubaku,
                                null,
                                tool,
                                0,
                                yPointer,
                                wBase,
                                yBaseTemp,
                                "process_text",
                            );
                            var waktu = graph.insertVertex(
                                mutubaku,
                                null,
                                time,
                                wBase,
                                yPointer,
                                wBase,
                                yBaseTemp,
                                "process_text",
                            );
                            var keluaran = graph.insertVertex(
                                mutubaku,
                                null,
                                output,
                                2 * wBase,
                                yPointer,
                                wBase,
                                yBaseTemp,
                                "process_text",
                            );
                            xPointer = xPointer + wMutu;
                            var ket = graph.insertVertex(
                                lane,
                                null,
                                note,
                                xPointer,
                                yPointer,
                                wNote,
                                yBaseTemp,
                                "process_text",
                            );
                            yTemp = yTemp + yBaseTemp;
                        }

                        // Off-Page Row
                        if (end != nActivity) {
                            xPointer = 0;
                            yPointer = 0;
                            num = "";
                            actL = "";
                            toolL = "";
                            timeL = "";
                            outputL = "";
                            noteL = "";
                            if (end == nActivity) {
                                num = end;
                                actL = activities[i - 1];
                                toolL = tools[i - 1];
                                timeL = times[i - 1];
                                outputL = outputs[i - 1];
                                noteL = notes[i - 1];
                            }
                            var lane = graph.insertVertex(
                                pool,
                                null,
                                "",
                                xPointer,
                                yTemp,
                                wTotal,
                                yOffPage,
                            );
                            var no = graph.insertVertex(
                                lane,
                                null,
                                num,
                                xPointer,
                                yPointer,
                                wNo,
                                yOffPage,
                            );
                            xPointer = xPointer + wNo;
                            var act = graph.insertVertex(
                                lane,
                                null,
                                actL,
                                xPointer,
                                yPointer,
                                wAct,
                                yOffPage,
                                "process_text",
                            );
                            xPointer = xPointer + wAct;
                            var actor = graph.insertVertex(
                                lane,
                                null,
                                "",
                                xPointer,
                                yPointer,
                                wActor,
                                yOffPage,
                                "process_text",
                            );
                            var botRow = [];
                            for (var j = 0; j < nActor; j++) {
                                botRow[j] = graph.insertVertex(
                                    actor,
                                    null,
                                    "",
                                    j * wBase,
                                    yPointer,
                                    wBase,
                                    yOffPage,
                                );
                            }
                            xPointer = xPointer + wActor;
                            var mutubaku = graph.insertVertex(
                                lane,
                                null,
                                "",
                                xPointer,
                                yPointer,
                                wMutu,
                                yOffPage,
                                "process_text",
                            );
                            var syarat = graph.insertVertex(
                                mutubaku,
                                null,
                                toolL,
                                0,
                                yPointer,
                                wBase,
                                yOffPage,
                                "process_text",
                            );
                            var waktu = graph.insertVertex(
                                mutubaku,
                                null,
                                timeL,
                                wBase,
                                yPointer,
                                wBase,
                                yOffPage,
                                "process_text",
                            );
                            var keluaran = graph.insertVertex(
                                mutubaku,
                                null,
                                "",
                                2 * wBase,
                                yPointer,
                                wBase,
                                yOffPage,
                                "process_text",
                            );
                            xPointer = xPointer + wMutu;
                            var ket = graph.insertVertex(
                                lane,
                                null,
                                noteL,
                                xPointer,
                                yPointer,
                                wNote,
                                yOffPage,
                                "process_text",
                            );
                            yTemp = yTemp + yOffPage;
                        }

                        // ===========================
                        // 5. Pembuatan Flowchart
                        //var shape = createArray(nActivity, nActor);
                        var xStart = wNo + wAct;
                        yPointer = yHead;
                        // Start Off Page
                        if (start != 1) {
                            for (var z = 0; z < nActor; z++) {
                                xPointer = xStart;
                                if (graphLocation[start - 2][z] == 1) {
                                    var top = z;
                                    xPointer = xPointer + z * wBase;
                                    shape[nActivity][z] = graph.insertVertex(
                                        fcPool,
                                        null,
                                        "",
                                        xPointer + 25,
                                        yPointer + 10,
                                        50,
                                        25,
                                        "off-page",
                                    );
                                    // console.log('top=' + top);
                                    var d = 1;
                                    var point0 = graph.insertVertex(
                                        shape[nActivity][z],
                                        null,
                                        "",
                                        0,
                                        0.5,
                                        d,
                                        d,
                                        "portConstraint=west;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;" +
                                            "routingCenterX=1;routingCenterY=0;",
                                        true,
                                    );
                                    point0.geometry.offset = new mxPoint(-d, -(0.5 * d));
                                    var point1 = graph.insertVertex(
                                        shape[nActivity][z],
                                        null,
                                        "",
                                        1,
                                        0.5,
                                        d,
                                        d,
                                        "portConstraint=east;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;" +
                                            "routingCenterX=0;routingCenterY=0;",
                                        true,
                                    );
                                    point1.geometry.offset = new mxPoint(-d, -(0.5 * d));
                                    var point2 = graph.insertVertex(
                                        shape[nActivity][z],
                                        null,
                                        "",
                                        0.5,
                                        0,
                                        d,
                                        d,
                                        "portConstraint=north;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;",
                                        true,
                                    );
                                    point2.geometry.offset = new mxPoint(-(0.5 * d), -d);
                                    var point3 = graph.insertVertex(
                                        shape[nActivity][z],
                                        null,
                                        "",
                                        0.5,
                                        1,
                                        d,
                                        d,
                                        "portConstraint=south;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;",
                                        true,
                                    );
                                    point3.geometry.offset = new mxPoint(-(0.5 * d), -d);
                                } else {
                                    shape[nActivity][z] = 0;
                                    continue;
                                }
                            }
                            yPointer = yPointer + yOffPage;
                        }
                        for (var y = start - 1; y < end; y++) {
                            for (var z = 0; z < nActor; z++) {
                                xPointer = xStart;
                                if (graphShape[y][z] != 0) {
                                    xPointer = xPointer + z * wBase;

                                    // Mengatur ukuran shape
                                    var shapeWidth = 50;
                                    var shapeHeight = 25;
                                    if (graphShape[y][z] == "state") {
                                        shapeWidth = 50;
                                        shapeHeight = 30;
                                    } else if (graphShape[y][z] == "condition") {
                                        shapeWidth = 40;
                                        shapeHeight = 40;
                                    }

                                    var wCenter = wBase / 2;
                                    var yCenter = rowHeights[y] / 2;
                                    var xPoint = xPointer + (wCenter - shapeWidth / 2);
                                    var yPoint = yPointer + (yCenter - shapeHeight / 2);
                                    shape[y][z] = graph.insertVertex(
                                        fcPool,
                                        null,
                                        "",
                                        xPoint,
                                        yPoint,
                                        shapeWidth,
                                        shapeHeight,
                                        graphShape[y][z],
                                    );

                                    var d = 1;
                                    var point0 = graph.insertVertex(
                                        shape[y][z],
                                        null,
                                        "",
                                        0,
                                        0.5,
                                        d,
                                        d,
                                        "portConstraint=west;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;" +
                                            "routingCenterX=1;routingCenterY=0;",
                                        true,
                                    );
                                    point0.geometry.offset = new mxPoint(-d, -(0.5 * d));
                                    var point1 = graph.insertVertex(
                                        shape[y][z],
                                        null,
                                        "",
                                        1,
                                        0.5,
                                        d,
                                        d,
                                        "portConstraint=east;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;" +
                                            "routingCenterX=0;routingCenterY=0;",
                                        true,
                                    );
                                    point1.geometry.offset = new mxPoint(-d, -(0.5 * d));
                                    var point2 = graph.insertVertex(
                                        shape[y][z],
                                        null,
                                        "",
                                        0.5,
                                        0,
                                        d,
                                        d,
                                        "portConstraint=north;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;",
                                        true,
                                    );
                                    point2.geometry.offset = new mxPoint(-(0.5 * d), -d);
                                    var point3 = graph.insertVertex(
                                        shape[y][z],
                                        null,
                                        "",
                                        0.5,
                                        1,
                                        d,
                                        d,
                                        "portConstraint=south;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;",
                                        true,
                                    );
                                    point3.geometry.offset = new mxPoint(-(0.5 * d), -d);
                                    if (graphShape[y][z] == "condition") {
                                        var point4 = graph.insertVertex(
                                            shape[y][z],
                                            null,
                                            "",
                                            0.25,
                                            0.25,
                                            d,
                                            d,
                                            "portConstraint=west;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;" +
                                                "routingCenterX=1;routingCenterY=0;",
                                            true,
                                        );
                                        point4.geometry.offset = new mxPoint(
                                            -d,
                                            -(0.5 * d),
                                        );
                                    } else {
                                        var point4 = graph.insertVertex(
                                            shape[y][z],
                                            null,
                                            "",
                                            0,
                                            0.5,
                                            d,
                                            d,
                                            "portConstraint=west;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;" +
                                                "routingCenterX=1;routingCenterY=0;",
                                            true,
                                        );
                                        point4.geometry.offset = new mxPoint(
                                            -d,
                                            -(0.5 * d),
                                        );
                                    }

                                    // Membuat koneksi antar shape
                                    if (start != 1 && y == start - 1) {
                                        graph.insertEdge(
                                            fcPool,
                                            null,
                                            null,
                                            shape[nActivity][top].getChildAt(3),
                                            shape[y][z].getChildAt(2),
                                        );
                                    }
                                } else {
                                    shape[y][z] = 0;
                                    continue;
                                }
                            }
                            yPointer = yPointer + rowHeights[y];
                        }
                        if (end != nActivity) {
                            for (var z = 0; z < nActor; z++) {
                                xPointer = xStart;
                                if (graphLocation[end - 1][z] == 1) {
                                    xPointer = xPointer + z * wBase;
                                    shape[nActivity + 1][z] = graph.insertVertex(
                                        fcPool,
                                        null,
                                        "",
                                        xPointer + 25,
                                        yPointer + 12,
                                        50,
                                        25,
                                        "off-page",
                                    );
                                    var d = 1;
                                    var point0 = graph.insertVertex(
                                        shape[nActivity + 1][z],
                                        null,
                                        "",
                                        0,
                                        0.5,
                                        d,
                                        d,
                                        "portConstraint=west;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;" +
                                            "routingCenterX=1;routingCenterY=0;",
                                        true,
                                    );
                                    point0.geometry.offset = new mxPoint(-d, -(0.5 * d));
                                    var point1 = graph.insertVertex(
                                        shape[nActivity + 1][z],
                                        null,
                                        "",
                                        1,
                                        0.5,
                                        d,
                                        d,
                                        "portConstraint=east;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;" +
                                            "routingCenterX=0;routingCenterY=0;",
                                        true,
                                    );
                                    point1.geometry.offset = new mxPoint(-d, -(0.5 * d));
                                    var point2 = graph.insertVertex(
                                        shape[nActivity + 1][z],
                                        null,
                                        "",
                                        0.5,
                                        0,
                                        d,
                                        d,
                                        "portConstraint=north;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;",
                                        true,
                                    );
                                    point2.geometry.offset = new mxPoint(-(0.5 * d), -d);
                                    var point3 = graph.insertVertex(
                                        shape[nActivity + 1][z],
                                        null,
                                        "",
                                        0.5,
                                        1,
                                        d,
                                        d,
                                        "portConstraint=south;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;",
                                        true,
                                    );
                                    point3.geometry.offset = new mxPoint(-(0.5 * d), -d);
                                    graph.insertEdge(
                                        fcPool,
                                        null,
                                        null,
                                        shape[end - 1][z].getChildAt(3),
                                        shape[nActivity + 1][z].getChildAt(2),
                                    );
                                    // console.log('created bot shape');
                                } else {
                                    shape[nActivity + 1][z] = 0;
                                    continue;
                                }
                            }
                            yPointer = yPointer + yOffPage;
                        }
                        // console.log('Shape');
                        // console.log(shape);

                        // Membuat garis penghubung (connector)
                        var leftDot = 0;
                        var rightDot = 0;
                        // console.log('Dot: ' + leftDot + '-' + rightDot);
                        for (var k = start - 1; k < end; k++) {
                            var sTemp = 0;
                            var first = true;
                            for (var l = 0; l < nActor; l++) {
                                /* Horizontal Line
                                if (first && shape[k][l] != 0) {
                                    sTemp = shape[k][l];
                                    first = false;
                                } else if (!first && shape[k][l] != 0) {
                                    //graph.insertEdge(fcPool, null, null, sTemp, shape[k][l]);
                                    graph.insertEdge(fcPool, null, null, sTemp.getChildAt(1), shape[k][l].getChildAt(0));
                                    sTemp = shape[k][l];
                                }
                                 */

                                // Vertical Line - Modified version that maintains both normal connections and condition false paths
                                for (var k = start - 1; k < end; k++) {
                                    // Pertama, tangani koneksi normal (termasuk "Ya" dari condition)
                                    for (var l = 0; l < nActor; l++) {
                                        if (
                                            shape[k][l] != 0 &&
                                            graphShape[k][l] != "condition"
                                        ) {
                                            // Untuk shape biasa, buat koneksi ke semua shape di aktivitas berikutnya
                                            for (var m = 0; m < nActor; m++) {
                                                if (k + 1 < end && shape[k + 1][m] != 0) {
                                                    graph.insertEdge(
                                                        fcPool,
                                                        null,
                                                        null,
                                                        shape[k][l].getChildAt(3),
                                                        shape[k + 1][m].getChildAt(2),
                                                    );
                                                }
                                            }
                                        } else if (graphShape[k][l] == "condition") {
                                            // Untuk condition shape, buat koneksi "Ya" ke semua shape di aktivitas berikutnya
                                            for (var m = 0; m < nActor; m++) {
                                                if (k + 1 < end && shape[k + 1][m] != 0) {
                                                    graph.insertEdge(
                                                        fcPool,
                                                        null,
                                                        "Sesuai",
                                                        shape[k][l].getChildAt(3),
                                                        shape[k + 1][m].getChildAt(2),
                                                        "verticalAlign=bottom;align=right",
                                                    );
                                                }
                                            }
                                        }
                                    }

                                    // Kedua, tangani khusus untuk kondisi "Tidak" (false path)
                                    for (var l = 0; l < nActor; l++) {
                                        if (graphShape[k][l] == "condition") {
                                            convTo2Dim(falseData[k][l]);
                                            try {
                                                if (leftDot == 0) {
                                                    graph.insertEdge(
                                                        fcPool,
                                                        null,
                                                        "Tidak\nsesuai",
                                                        shape[k][l].getChildAt(0),
                                                        shape[falseY][falseX].getChildAt(0),
                                                        "verticalAlign=bottom;align=left",
                                                    );
                                                    leftDot = 1;
                                                } else if (rightDot == 0) {
                                                    graph.insertEdge(
                                                        fcPool,
                                                        null,
                                                        "Tidak\nsesuai",
                                                        shape[k][l].getChildAt(1),
                                                        shape[falseY][falseX].getChildAt(1),
                                                        "verticalAlign=top;align=right",
                                                    );
                                                    rightDot = 1;
                                                } else {
                                                    graph.insertEdge(
                                                        fcPool,
                                                        null,
                                                        "Tidak\nsesuai",
                                                        shape[k][l].getChildAt(0),
                                                        shape[falseY][falseX].getChildAt(0),
                                                        "verticalAlign=bottom;align=left",
                                                    );
                                                    leftDot = 1;
                                                    rightDot = 0;
                                                }
                                            } catch {
                                                console.log(
                                                    "Condition in first row or target not found",
                                                );
                                            }
                                        }
                                    }
                                }



                                /* if (graphShape[k][l] == 'condition') {
                                    convTo2Dim(falseData[k][l]);
                                    if (graphShape[falseY][falseX] == 'condition') {
                                        graph.insertEdge(fcPool, null, 'Tidak', shape[k][l].getChildAt(0), shape[falseY][falseX].getChildAt(3), 'verticalAlign=top;align=right');
                                    } else {
                                        graph.insertEdge(fcPool, null, 'Tidak', shape[k][l].getChildAt(0), shape[falseY][falseX].getChildAt(0), 'align=left');
                                    }
                                } */
                            }
                        }
                    } finally {
                        // Updates the display
                        graph.getModel().endUpdate();
                        const jsonBody = JSON.stringify(
                            {
                                nActor: nActor,
                                actorName: actorName,
                                nActivity: nActivity,
                                rowHeights: rowHeights,
                                activities: activities,
                                tools: tools,
                                times: times,
                                outputs: outputs,
                                notes: notes,
                                graphLocation: graphLocation,
                                graphShape: graphShape,
                                shape: extractCellData(shape),
                                falseData: falseData,
                                actorLoc: extractCellData(actorLoc), // Gunakan fungsi filter khusus
                            },
                            null,
                        );
                        console.log("Type of data draw():", typeof jsonBody);
                        console.log("data draw:", jsonBody);
                        return jsonBody;

                        // Fungsi untuk mengekstrak data penting dari mxCell
                        function extractCellData(cells) {
                            return cells.map((row) => {
                                return row.map((cell) => {
                                    if (!cell) return null;
                                    return {
                                        id: cell.getId(),
                                        value: cell.getValue(),
                                        geometry: cell.getGeometry()
                                            ? {
                                                  x: cell.getGeometry().x,
                                                  y: cell.getGeometry().y,
                                                  width: cell.getGeometry().width,
                                                  height: cell.getGeometry().height,
                                              }
                                            : null,
                                    };
                                });
                            });
                        }
                    }
                }
            }

            function convTo2Dim(x) {
                falseX = (x % nActor) - 1;
                falseY = Math.floor(x / nActor);
                if (falseX == -1) {
                    falseX = falseX + nActor;
                    falseY = falseY - 1;
                }
            }
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
