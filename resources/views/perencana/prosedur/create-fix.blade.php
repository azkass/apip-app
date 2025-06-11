@extends('layouts.app')
@section('content')
    <!-- Form Pelaksana -->
<div class="bg-white rounded-md mb-4 ">
    <div class="p-4" id="formContainer">
        <h2 class="text-xl font-semibold mb-4">Tambah Pelaksana</h2>
        <template id="formTemplate">
            <div class="flex items-center mb-2 form-item w-96 rounded-sm">
                <label class="block w-28 text-base font-medium text-gray-700">Pelaksana <span class="actor-number">1</span> :</label>
                <select class="form-select w-52 rounded-md border border-gray-300 shadow-sm py-1 px-3 ml-2" onchange="handleActorSelection(this)">
                    <option value="" selected disabled>-- Pilih Aktor --</option>
                    <option value="Inspektur Wilayah">Inspektur Wilayah</option>
                    <option value="Pengendali Teknis">Pengendali Teknis</option>
                    <option value="Ketua Tim">Ketua Tim</option>
                    <option value="Anggota Tim">Anggota Tim</option>
                    <option value="new-actor">Aktor Baru</option>
                </select>
                <!-- <input type="text" class="hidden custom-actor-input w-52 rounded-md border-gray-300 shadow-sm py-1 px-3 ml-2" placeholder="Masukkan aktor baru"> -->
            </div>
        </template>
    </div>
        <div id="formContainer"  class="flex justify-between p-4 w-full">
            <div>
                <button onclick="addActor(this)" class="cursor-pointer bg-blue-500 hover:bg-blue-600 h-10 text-base text-white px-4 py-2 rounded-sm mb-2 mr-2">Tambah Pelaksana</button>
                <button onclick="deleteLastForm()" class="cursor-pointer bg-red-500 hover:bg-red-600 h-10 text-base text-white py-2 px-4 rounded">Hapus Pelaksana</button>
            </div>
            <div>
                <button onclick="saveActor()" class="cursor-pointer bg-green-600 hover:bg-green-700 h-10 text-base text-white px-4 py-2 rounded-sm">Simpan</button>
            </div>
        </div>
</div>

    <!-- Form Diagram Details -->
    <div id="diagramSection" class="mb-5 p-4 bg-white rounded-lg hidden">
        <h2 class="text-xl font-semibold mb-3">Detail Diagram</h2>
        <div id="diagramTable" class="mb-4">
            <!-- Diagram table will be added here -->
        </div>
        <div class="flex justify-between w-full">
            <div>
                <button onclick="addActivity()" class="bg-blue-500 hover:bg-blue-600 cursor-pointer h-10 text-base text-white py-2 px-4 rounded-sm mb-2 mr-2">Tambah Aktivitas</button>
                <button onclick="removeActivity()" class="bg-red-500 hover:bg-red-600 cursor-pointer h-10 text-base text-white py-2 px-4 rounded-sm mr-2">Hapus Aktivitas</button>
            </div>
            <button onclick="preview()" class="bg-green-600 hover:bg-green-700 cursor-pointer h-10 text-base text-white py-2 px-4 rounded-sm">Preview Diagram</button>
        </div>
    </div>

    <div id="previewBox" class="hidden p-4 bg-white rounded-lg">
        <h2 class="text-xl font-semibold mb-3">Diagram Preview</h2>
        <div id="graphContainerBox" class="overflow-auto">
            <div id="graphContainer"></div>
        </div>
    </div>

    <script src="https://jgraph.github.io/mxgraph/javascript/mxClient.min.js"></script>

    <script>
    let formCount = 0;
    var nActor = 0;
    var nAction = 0;
    var nActivity = 1; // Default 1 activity
    var actorNames = [];
    var graphLocation;
    var graphType = [0];
    var graphShape;
    var actorName = [];
    var activities = ['']; // Initialize with one empty activity
    var actorLoc = [];
    var shape = [];
    var tools = [''];
    var times = [''];
    var outputs = [''];
    var notes = [''];
    var rowHeights = [];
    var tdBase = "";
    var falseData;
    var falseTarget;
    var falseX = 0;
    var falseY = 0;
    var shapeSelections = [];
    var falseToSelections = [];

    // Tambahkan form pertama saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        // addActor();
    });

    function handleActorSelection(selectElement) {
        const parentDiv = selectElement.parentElement;
        const customActorInput = parentDiv.querySelector('.custom-actor-input');
        const actorNumberSpan = parentDiv.querySelector('.actor-number');

        if (selectElement.value === 'new-actor') {
            // Tampilkan input teks dan sembunyikan select
            selectElement.classList.add('hidden');
            customActorInput.classList.remove('hidden');
            customActorInput.focus();

            // Tambahkan event listener untuk menyimpan nilai saat input selesai
            customActorInput.addEventListener('blur', function() {
                if (this.value.trim() !== '') {
                    // Buat opsi baru dengan nilai yang dimasukkan pengguna
                    const newOption = document.createElement('option');
                    newOption.value = this.value;
                    newOption.textContent = this.value;

                    // Sisipkan sebelum opsi "Aktor Baru"
                    const newActorOption = Array.from(selectElement.options).find(opt => opt.value === 'new-actor');
                    selectElement.insertBefore(newOption, newActorOption);

                    // Pilih opsi yang baru dibuat
                    selectElement.value = this.value;

                    // Sembunyikan input dan tampilkan select kembali
                    this.classList.add('hidden');
                    selectElement.classList.remove('hidden');
                } else {
                    // Jika input kosong, kembalikan ke select tanpa menambahkan opsi baru
                    this.classList.add('hidden');
                    selectElement.classList.remove('hidden');
                    selectElement.value = '';
                }
            });

            // Tambahkan juga handler untuk tombol Enter
            customActorInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    this.blur(); // Trigger blur event yang sudah kita handle
                }
            });
        } else {
            // Pastikan input teks tersembunyi jika memilih opsi lain
            customActorInput.classList.add('hidden');
            selectElement.classList.remove('hidden');
        }
    }

    function addActor(btn = null) {
        const formContainer = document.querySelector('#formContainer');
        const template = document.querySelector('#formTemplate');

        formCount++;
        const formClone = template.content.cloneNode(true);
        formClone.querySelector('.actor-number').textContent = formCount;

        formContainer.appendChild(formClone);
    }

    function deleteLastForm() {
        const formItems = document.querySelectorAll('#formContainer .form-item');

        if (formItems.length <= 1) {
            alert('Minimal terdapat satu pelaksana');
            return false;
        }

        formItems[formItems.length - 1].remove();
        formCount--;
        updateActorNumbers();
        return true;
    }

    function updateActorNumbers(container) {
        const actorNumbers = container.querySelectorAll('.actor-number');
        actorNumbers.forEach((number, index) => {
            number.textContent = index + 1;
        });
    }

    function saveActor() {
        const formContainer = document.querySelector('#formContainer');
        const allSelects = formContainer.querySelectorAll('select');

        // Cek apakah ada select yang masih default (-- Pilih Aktor --)
        const hasUnselected = Array.from(allSelects).some(select =>
            select.value === "" || select.value === null
        );

        if (hasUnselected) {
            const unselectedIndexes = Array.from(allSelects)
                .map((select, index) => select.value === "" ? index + 1 : -1)
                .filter(i => i !== -1);

            alert(`Harap pilih aktor untuk Pelaksana ${unselectedIndexes.join(', ')}`);
            return false;
        }

        // Lanjutkan dengan kode penyimpanan yang ada
        const newNActor = formContainer.querySelectorAll('.form-item').length;
        const newActorNames = Array.from(allSelects).map(select => select.value);

        // Update actor count
        const prevNActor = nActor;
        nActor = newNActor;
        actorNames = newActorNames;

        // Adjust selections jika jumlah aktor berubah
        if (prevNActor !== nActor) {
            shapeSelections = shapeSelections.map(row =>
                Array(nActor).fill().map((_, i) => i < row.length ? row[i] : '0')
            );
            falseToSelections = falseToSelections.map(row =>
                Array(nActor).fill().map((_, i) => i < row.length ? row[i] : '')
            );
        }

        // console.log(JSON.stringify({
        //     nActivity,
        //     nActor,
        //     actorNames
        // }, null, 2));

        setupDiagramTable();
    }

    function addActivity() {
        // Cek apakah ada aktivitas yang memiliki opsi "Selesai"
        for (let i = 0; i < nActivity; i++) {
            if (shapeSelections[i].includes('4')) {
                alert('Tidak dapat menambah aktivitas karena sudah ada opsi "Selesai"!');
                return;
            }
        }
        nActivity++;
        activities.push('');
        tools.push('');
        times.push('');
        outputs.push('');
        notes.push('');
        shapeSelections.push(Array(nActor).fill('0'));
        falseToSelections.push(Array(nActor).fill(''));
        setupDiagramTable();
    }

    function removeActivity() {
        if (nActivity > 1) {
            nActivity--;
            activities.pop();
            tools.pop();
            times.pop();
            outputs.pop();
            notes.pop();
            shapeSelections.pop();
            falseToSelections.pop();
            setupDiagramTable();
        } else {
            alert('Minimal terdapat 1 Aktivitas');
        }
    }



    function setupDiagramTable() {
            if (actorNames.length === 0) {
                alert('Silakan simpan nama pelaksana terlebih dahulu!');
                return;
            }

            // Simpan data sebelum render ulang dengan cara lebih efisien
            for (let i = 0; i < nActivity; i++) {
                const actNum = i + 1;
                activities[i] = document.getElementById(`act-${actNum}`)?.value || '';
                tools[i] = document.getElementById(`tool-${actNum}`)?.value || '';
                times[i] = document.getElementById(`time-${actNum}`)?.value || '';
                outputs[i] = document.getElementById(`output-${actNum}`)?.value || '';
                notes[i] = document.getElementById(`note-${actNum}`)?.value || '';

                const currentShapes = [];
                const currentFalseTos = [];
                for (let j = 0; j < nActor; j++) {
                    const actorNum = j + 1;
                    currentShapes[j] = document.getElementById(`gShape-${actNum}-${actorNum}`)?.value || '0';
                    currentFalseTos[j] = document.getElementById(`falseTo-${actNum}-${actorNum}`)?.value || '';
                }
                shapeSelections[i] = currentShapes;
                falseToSelections[i] = currentFalseTos;
            }

            const tableDiv = document.getElementById('diagramTable');

            // Gunakan DocumentFragment untuk mengurangi reflow
            const fragment = document.createDocumentFragment();
            const table = document.createElement('table');
            table.id = 'diagramTable';
            table.className = 'w-full border-collapse';

            // Buat header dengan string template
            const headers = ['No', 'Aktivitas', ...actorNames, 'Kelengkapan', 'Waktu (Jam)', 'Output', 'Keterangan'];
            const headerRow = document.createElement('tr');
            headerRow.className = 'bg-gray-100';
            headerRow.innerHTML = headers.map(header => `<th class="border p-2">${header}</th>`).join('');
            table.appendChild(headerRow);

            // Cek apakah ada aktivitas yang memiliki opsi "Selesai"
            let hasSelesai = false;
            for (let i = 0; i < nActivity; i++) {
                if (shapeSelections[i].includes('4')) {
                    hasSelesai = true;
                    break;
                }
            }

            // Buat baris aktivitas
            for (let i = 1; i <= nActivity; i++) {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';

                // Temukan indeks pertama dengan shape khusus
                const firstSpecialIndex = shapeSelections[i-1].findIndex(
                    shape => shape === '1' || shape === '3' || shape === '4'
                );

                // Buat kolom untuk setiap aktor
                const actorColumns = Array(nActor).fill().map((_, j) => {
                    const actorNum = j + 1;
                    const isDisabled = firstSpecialIndex >= 0 && j !== firstSpecialIndex;

                    return `
                        <td class="border p-2">
                            <select id="gShape-${i}-${actorNum}" onchange="setupDiagramTable()"
                                class="w-full p-1 border rounded ${isDisabled ? 'opacity-50' : ''}" ${isDisabled ? 'disabled' : ''}>
                                <option value="0" ${shapeSelections[i-1][j] === '0' ? 'selected' : ''}>Tidak ada</option>
                                <option value="1" ${shapeSelections[i-1][j] === '1' ? 'selected' : ''}>Mulai</option>
                                <option value="2" ${shapeSelections[i-1][j] === '2' ? 'selected' : ''}>Proses</option>
                                <option value="3" ${shapeSelections[i-1][j] === '3' ? 'selected' : ''}>Pilihan</option>
                                <option value="4" ${shapeSelections[i-1][j] === '4' ? 'selected' : ''}>Selesai</option>
                            </select>

                            <div id="f-${i}-${actorNum}" class="form-group ${shapeSelections[i-1][j] === '3' ? '' : 'hidden'} mt-2">
                                <label for="falseTo-${i}-${actorNum}" class="block text-xs text-gray-600 mb-1">Kondisi Salah ke:</label>
                                <select id="falseTo-${i}-${actorNum}" class="w-full p-1 border rounded text-xs">
                                    ${generateFalseOptions(i, actorNum, falseToSelections[i-1][j])}
                                </select>
                            </div>
                        </td>
                    `;
                }).join('');

                row.innerHTML = `
                    <td class="border p-2">${i}</td>
                    <td class="border p-2"><textarea id="act-${i}" placeholder="Deskripsi Aktivitas" class="w-full p-1 border rounded">${activities[i-1]}</textarea></td>
                    ${actorColumns}
                    <td class="border p-2"><input type="text" id="tool-${i}" placeholder="Alat/bahan" class="w-full border rounded p-1 text-sm" value="${tools[i-1]}" /></td>
                    <td class="border p-2"><input type="text" id="time-${i}" placeholder="Waktu" class="w-full border rounded p-1 text-sm" value="${times[i-1]}" /></td>
                    <td class="border p-2"><input type="text" id="output-${i}" placeholder="Output" class="w-full border rounded p-1 text-sm" value="${outputs[i-1]}" /></td>
                    <td class="border p-2"><input type="text" id="note-${i}" placeholder="Catatan" class="w-full border rounded p-1 text-sm" value="${notes[i-1]}" /></td>
                `;

                table.appendChild(row);
            }

            fragment.appendChild(table);

            // Gunakan innerHTML sekali saja
            tableDiv.innerHTML = '';
            tableDiv.appendChild(fragment);

            document.getElementById('diagramSection').classList.remove('hidden');

            // Nonaktifkan tombol "Tambah Aktivitas" jika ada opsi "Selesai"
            const addButton = document.querySelector('button[onclick="addActivity()"]');
            if (hasSelesai) {
                addButton.disabled = true;
                addButton.classList.add('opacity-50', 'cursor-not-allowed');
                addButton.classList.remove('hover:bg-blue-600');
            } else {
                addButton.disabled = false;
                addButton.classList.remove('opacity-50', 'cursor-not-allowed');
                addButton.classList.add('hover:bg-blue-600');
            }
        }


    function generateFalseOptions(i, j, selectedValue) {
        let options = '<option value="" ' + (!selectedValue ? 'selected disabled' : '') + '>-- Pilih Tujuan --</option>';

        for (let row = 1; row <= nActivity; row++) {
            for (let col = 1; col <= nActor; col++) {
                // Skip current activity and actor
                if (row === i && col === j) continue;

                // Cek apakah aktor pada aktivitas ini memiliki nilai yang dipilih (bukan '0')
                if (shapeSelections[row-1] && shapeSelections[row-1][col-1] && shapeSelections[row-1][col-1] !== '0') {
                    const value = (row - 1) * nActor + col;
                    const actorName = actorNames[col-1] || 'Pelaksana ' + col;
                    const isSelected = value == selectedValue ? 'selected' : '';

                    options += `<option value="${value}" ${isSelected}>Aktivitas ${row}, ${actorName}</option>`;
                }
            }
        }

        // Jika tidak ada opsi selain default, tambahkan pesan
        if (options === '<option value="" selected disabled>-- Pilih Tujuan --</option>') {
            options = '<option value="" selected disabled>Tidak ada tujuan tersedia</option>';
        }

        return options;
    }

    function check(i, j) {
        const shape = document.getElementById("gShape-" + i + "-" + j).value;
        const f = document.getElementById("f-" + i + "-" + j);
        if (shape == '3') {
            f.classList.remove("hidden");
        } else {
            f.classList.add("hidden");
        }
    }

    function check2(act, actor) {
        nActivity = act;
        nActor = actor;
        for (i = 1; i <= nActivity; i++) {
            for (j = 1; j <= nActor; j++) {
                shape = document.getElementById("shape-" + i + "-" + j).innerHTML;
                f = document.getElementById("f-" + i + "-" + j);
                if (shape == '3') {
                    f.className = "form-group";
                } else {
                    f.className = "form-group hidden";
                }
                for (x = 0; x <= 3; x++) {
                    option = document.getElementById("option-" + i + "-" + j + "-" + x);
                    option.selected = "false";
                }
                option2 = document.getElementById("option-" + i + "-" + j + "-" + shape);
                option2.selected = "true";
            }
        }
    }

    function getRowHeight(row) {
        var activity = activities[row];
        var tool = tools[row];
        var time = times[row];
        var output = outputs[row];
        var note = notes[row];

        var height = 80;
        var maxHeight = 620;
        var contents = [activity, tool, time, output, note];
        contents.forEach(text => {
            var nRow = text.length / 16;
            var yRow = nRow * 13 + 20;
            if (yRow > maxHeight) yRow = maxHeight;
            if (yRow > height) height = yRow;
        });
        return height;
    }

    function loadData() {
        console.log("Loading page elements");
        graphShape = createArray(nActivity, nActor);
        graphLocation = createArray(nActivity, nActor);
        falseData = createArray(nActivity, nActor);
        falseTarget = createArray(nActivity, nActor);
        var count;

        actorName = actorNames; // Use the saved actorNames

        for (i = 1; i <= nActivity; i++) {
            count = 1;
            for (j = 1; j <= nActor; j++) {
                falseTarget[i - 1][j - 1] = 0;
                shape = document.getElementById("gShape-" + i + "-" + j).value;
                switch (shape) {
                    case '1':
                        graphShape[i - 1][j - 1] = 'state';
                        break;
                    case '2':
                        graphShape[i - 1][j - 1] = 'process';
                        break;
                    case '3':
                        graphShape[i - 1][j - 1] = 'condition';
                        falseData[i - 1][j - 1] = document.getElementById("falseTo-" + i + "-" + j).value;
                        convTo2Dim(falseData[i - 1][j - 1]);
                        falseTarget[falseY][falseX] = 1;
                        break;
                    case '4':
                        graphShape[i - 1][j - 1] = 'state';
                        break;
                    default:
                        graphShape[i - 1][j - 1] = 0;
                        break;
                }
                if (shape != '0' && falseTarget[i - 1][j - 1] == 0) {
                    graphLocation[i - 1][j - 1] = count;
                    count++;
                } else {
                    graphLocation[i - 1][j - 1] = 0;
                }
            }
            activities[i - 1] = document.getElementById("act-" + i).value;
            tools[i - 1] = document.getElementById("tool-" + i).value;
            times[i - 1] = document.getElementById("time-" + i).value;
            outputs[i - 1] = document.getElementById("output-" + i).value;
            notes[i - 1] = document.getElementById("note-" + i).value;

            rowHeights.push(getRowHeight(i - 1));
        }
        console.log("All components are loaded");
    }

    function preview() {
        // Display Preview
        document.getElementById('previewBox').classList.remove('hidden');

        // Save all data that we need to display
        loadData();

        // Prepare main container
        var totalHeight = 0;
        var mainContainer = document.getElementById('graphContainer');
        mainContainer.innerHTML = '';

        var start = 1;
        var end = 1;
        var page = 0;
        actorLoc = createArray(nActivity + 1, nActor);
        shape = createArray(nActivity + 2, nActor);
        var maxWidth = (nActor + 6) * 120;
        var maxHeight = 820;
        while (end <= nActivity) {
            var height = 150;
            start = end;
            while (true && end <= nActivity) {
                page++;
                var estimatedHeight = height + rowHeights[end - 1];
                if (estimatedHeight >= maxHeight) break;

                height = estimatedHeight;
                end++;
            }
            if (start > 1 && end < nActivity) {
                height += 50;
            }
            totalHeight += height;

            // Draw page
            console.log('Start ' + start + ' End ' + (end - 1) + ' Height ' + height);
            var container = document.createElement("div");
            container.style = 'position:relative;overflow:hidden;width:'+maxWidth+'px;height:'+height+'px;border:white dotted 1px;cursor:default;';
            mainContainer.append(container);
            draw(container, start, end - 1);
        }

        // Recalculate box height
        var mainContainerBox = document.getElementById('graphContainerBox');
        mainContainerBox.style = 'height:' + totalHeight + 'px;';
        return true;
    }

    function draw(container, start, end) {
        // =====================================
        // 1. Inisialisasi dan Konfigurasi Dasar

        // Reset the preview container
        container.innerHTML = "";
        let pageSize = 5;

        // Checks if the browser is supported
        if (!mxClient.isBrowserSupported()) {
            // Displays an error message if the browser is not supported.
            mxUtils.error('Browser is not supported!', 200, false);
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
            var graph = new mxGraph(container);
            graph.setHtmlLabels(true);
            // Graph configure for Contstraint
            graph.disconnectOnMove = false;
            graph.foldingEnabled = false;
            graph.cellsResizable = false;
            graph.extendParents = false;
            graph.setConnectable(true);
            // Implements perimeter-less connection points as fixed points (computed before the edge style).
            graph.view.updateFixedTerminalPoint = function (edge, terminal, source, constraint) {
                mxGraphView.prototype.updateFixedTerminalPoint.apply(this, arguments);

                var pts = edge.absolutePoints;
                var pt = pts[(source) ? 0 : pts.length - 1];

                if (terminal != null && pt == null && this.getPerimeterFunction(terminal) == null) {
                    edge.setAbsoluteTerminalPoint(new mxPoint(this.getRoutingCenterX(terminal),
                        this.getRoutingCenterY(terminal)), source)
                }
            };

            graph.isCellEditable = function (cell) {
                return !this.model.isEdge(cell);
            };


            // ==============================
            // 3. Definisi Shape
            var style = graph.getStylesheet().getDefaultVertexStyle();
            style[mxConstants.STYLE_SHAPE] = mxConstants.SHAPE_RECTANGLE;
            style[mxConstants.STYLE_PERIMETER] = mxPerimeter.RectanglePerimeter;
            style[mxConstants.STYLE_FONTSIZE] = 10;
            style[mxConstants.STYLE_ROUNDED] = false;
            style[mxConstants.STYLE_VERTICAL_ALIGN] = 'middle';
            style[mxConstants.STYLE_MOVEABLE] = 0;
            style[mxConstants.STYLE_RESIZABLE] = 0;
            style[mxConstants.STYLE_EDITABLE] = 0;
            style[mxConstants.STYLE_FONTCOLOR] = 'black';
            style[mxConstants.STYLE_STROKECOLOR] = 'black';
            style[mxConstants.STYLE_SPACING_TOP] = 5;
            style[mxConstants.STYLE_SPACING_LEFT] = 5;
            style[mxConstants.STYLE_SPACING_RIGHT] = 5;
            style[mxConstants.STYLE_SPACING_BOTTOM] = 5;
            style[mxConstants.STYLE_FILLCOLOR] = 'white';
            style[mxConstants.STYLE_WHITE_SPACE] = 'wrap';
            graph.getStylesheet().putCellStyle('process', style);

            style = mxUtils.clone(style);
            style[mxConstants.STYLE_VERTICAL_ALIGN] = 'top';
            style[mxConstants.STYLE_ALIGN] = 'left';
            graph.getStylesheet().putCellStyle('process_text', style);

            style = mxUtils.clone(style);
            style[mxConstants.STYLE_SHAPE] = mxConstants.SHAPE_RECTANGLE; // Ubah dari ELLIPSE ke RECTANGLE
            style[mxConstants.STYLE_ROUNDED] = true; // Tambahkan rounded corners
            style[mxConstants.STYLE_ARCSIZE] = 50; // Nilai kelengkungan sudut
            style[mxConstants.STYLE_PERIMETER] = mxPerimeter.RectanglePerimeter; // Tambahkan perimeter
            delete style[mxConstants.STYLE_STARTSIZE];
            style[mxConstants.STYLE_FONTCOLOR] = 'black';
            style[mxConstants.STYLE_STROKECOLOR] = 'black';
            style[mxConstants.STYLE_LABEL_BACKGROUNDCOLOR] = 'white';
            style[mxConstants.STYLE_FILLCOLOR] = 'white';
            graph.getStylesheet().putCellStyle('state', style);

            style = mxUtils.clone(style);
            style[mxConstants.STYLE_SHAPE] = mxConstants.SHAPE_RHOMBUS;
            style[mxConstants.STYLE_PERIMETER] = mxPerimeter.RhombusPerimeter;
            style[mxConstants.STYLE_VERTICAL_ALIGN] = 'top';
            delete style[mxConstants.STYLE_ROUNDED];
            style[mxConstants.STYLE_SPACING_TOP] = 40;
            style[mxConstants.STYLE_SPACING_RIGHT] = 64;
            graph.getStylesheet().putCellStyle('condition', style);

            style = mxUtils.clone(style);
            style[mxConstants.STYLE_SHAPE] = mxConstants.SHAPE_IMAGE;
            style[mxConstants.STYLE_PERIMETER] = mxPerimeter.RectanglePerimeter;
            style[mxConstants.STYLE_IMAGE] = 'https://jgraph.github.io/mxgraph/javascript/examples/images/offpage.png';
            style[mxConstants.STYLE_FONTSIZE] = 14;
            style[mxConstants.STYLE_FONTSTYLE] = 1;
            delete style[mxConstants.STYLE_SPACING_RIGHT];
            graph.getStylesheet().putCellStyle('off-page', style);

            style = graph.getStylesheet().getDefaultEdgeStyle();
            style[mxConstants.STYLE_EDGE] = 'orthogonalEdgeStyle';
            style[mxConstants.STYLE_ENDARROW] = mxConstants.ARROW_BLOCK;
            style[mxConstants.STYLE_ROUNDED] = false;
            delete style[mxConstants.STYLE_FILLCOLOR];
            style[mxConstants.STYLE_FONTCOLOR] = 'black';
            style[mxConstants.STYLE_STROKECOLOR] = 'black';
            style[mxConstants.STYLE_LABEL_BACKGROUNDCOLOR] = 'white';
            //style[mxConstants.STYLE_VERTICAL_LABEL_POSITION] = 'ALIGN_BOTTOM';

            style = mxUtils.clone(style);
            style[mxConstants.STYLE_EDGE] = mxEdgeStyle.SideToSide;
            graph.getStylesheet().putCellStyle('side', style);

            // Implements the connect preview
            graph.connectionHandler.createEdgeState = function (me) {
                var edge = graph.createEdge(null, null, null, null, null);

                return new mxCellState(this.graph.view, edge, this.graph.getCellStyle(edge));
            };

            // Gets the default parent for inserting new cells. This
            // is normally the first child of the root (ie. layer 0).
            var parent = graph.getDefaultParent();

            // ====================================
            // 4. Pembuatan Struktur Garis Tabel

            // Adds cells to the model in a single step
            graph.getModel().beginUpdate();
            try {
                // Membuat style baru (bisa juga memodifikasi existing style)
                  const vertexStyle = graph.getStylesheet().getDefaultVertexStyle();
                  vertexStyle[mxConstants.STYLE_FONTSIZE] = 14;
                  vertexStyle[mxConstants.STYLE_FONTFAMILY] = 'Times New Roman';

                // Menentukan ukuran dan tata letak semua komponen tabel.
                var xPointer = 0;  // Penanda posisi X saat ini
                var yPointer = 0;  // Penanda posisi Y saat ini

                // Menentukan lebar kolom
                var wBase = 100;         // Lebar dasar per aktor
                var wNo = 40;            // Lebar kolom "No."
                var wAct = 120;          // Lebar kolom "Aktivitas"
                var wActor = wBase * nActor; // Lebar total kolom "Pelaksana"
                var wMutu = wBase * 3;   // Lebar kolom "Mutu Baku" (3 sub-kolom)
                var wNote = 120;         // Lebar kolom "Keterangan"
                var wTotal = wNo + wAct + wActor + wMutu + wNote; // Lebar total tabel

                // Menentukan tinggi setiap baris
                var yHeadTop = 25;       // Tinggi bagian atas header
                var yHeadBottom = 55;    // Tinggi bagian bawah header
                var yHead = yHeadTop + yHeadBottom; // Tinggi total header
                var yOffPage = 50;       // Tinggi connector off-page

                // Menghitung tinggi total tabel
                var yTotal = yHead; // Tinggi total tabel
                for (let i = start; i <= end; i++) {
                    yTotal = yTotal + rowHeights[i-1];
                }
                if (start != 1) {
                    yTotal = yTotal + yOffPage;
                }
                if (end != nActivity) {
                    yTotal = yTotal + yOffPage;
                }

                // Pembuatan Container Utama
                var pool = graph.insertVertex(parent, null, '', xPointer, yPointer, wTotal, yTotal, 'strokeColor=none;');
                var fcPool = graph.insertVertex(parent, null, '', xPointer, yPointer, wTotal, yTotal, 'fillOpacity=0;strokeColor=none;');
                var notouch = graph.insertVertex(parent, null, '', xPointer, yPointer, wTotal, yTotal, 'fillOpacity=0;editable=0;movable=0;strokeColor=none;');
                pool.setConnectable(false);

                // Pembuatan Garis Header Tabel
                var lane1 = graph.insertVertex(pool, null, '', xPointer, yPointer, wTotal, yHead);

                // Pembuatan garis kolom pada header tabel
                var no = graph.insertVertex(lane1, null, 'No.', xPointer, yPointer, wNo, yHead);
                xPointer = xPointer + wNo;
                var act = graph.insertVertex(lane1, null, 'Aktivitas', xPointer, yPointer, wAct, yHead);
                xPointer = xPointer + wAct;
                var actor = graph.insertVertex(lane1, null, 'Pelaksana', xPointer, yPointer, wActor, yHead, 'verticalAlign=top');
                var actorList = [0];
                for (var i = 1; i <= nActor; i++) {
                    actorList[i] = graph.insertVertex(actor, null, actorName[i - 1], (i - 1) * wBase, yHeadTop, wBase, yHeadBottom);
                }
                xPointer = xPointer + wActor;
                var mutubaku = graph.insertVertex(lane1, null, 'Mutu Baku', xPointer, yPointer, wMutu, yHead, 'verticalAlign=top');
                var syarat = graph.insertVertex(mutubaku, null, 'Kelengkapan', 0, yHeadTop, wBase, yHeadBottom);
                var waktu = graph.insertVertex(mutubaku, null, 'Waktu', wBase, yHeadTop, wBase, yHeadBottom);
                var keluaran = graph.insertVertex(mutubaku, null, 'Output', 2 * wBase, yHeadTop, wBase, yHeadBottom);
                xPointer = xPointer + wMutu;
                var ket = graph.insertVertex(lane1, null, 'Keterangan', xPointer, yPointer, wNote, yHead);

                var yTemp = yPointer + yHead; // Posisi Y baru setelah header

                // Pembuatan Body Tabel
                //actorLoc = createArray(nActivity + 1, nActor);

                // Start Off Page
                if (start != 1) {
                    xPointer = 0;
                    yPointer = 0;

                    // Pembuatan off-page connectors:
                    var lane = graph.insertVertex(pool, null, '', xPointer, yTemp, wTotal, yOffPage);

                    var no = graph.insertVertex(lane, null, '', xPointer, yPointer, wNo, yOffPage);
                    xPointer = xPointer + wNo;
                    var act = graph.insertVertex(lane, null, '', xPointer, yPointer, wAct, yOffPage, 'process_text');
                    xPointer = xPointer + wAct;
                    var actor = graph.insertVertex(lane, null, '', xPointer, yPointer, wActor, yOffPage, 'process_text');
                    var topRow = [];
                    for (var j = 0; j < nActor; j++) {
                        topRow[j] = graph.insertVertex(actor, null, '', j * wBase, yPointer, wBase, yOffPage);
                    }
                    xPointer = xPointer + wActor;
                    var mutubaku = graph.insertVertex(lane, null, '', xPointer, yPointer, wMutu, yOffPage, 'process_text');
                    var syarat = graph.insertVertex(mutubaku, null, '', 0, yPointer, wBase, yOffPage, 'process_text');
                    var waktu = graph.insertVertex(mutubaku, null, '', wBase, yPointer, wBase, yOffPage, 'process_text');
                    var keluaran = graph.insertVertex(mutubaku, null, '', 2 * wBase, yPointer, wBase, yOffPage, 'process_text');
                    xPointer = xPointer + wMutu;
                    var ket = graph.insertVertex(lane, null, '', xPointer, yPointer, wNote, yOffPage, 'process_text');
                    yTemp = yTemp + yOffPage;
                }

                // Data Row
                for (var i = start; i <= end; i++) {
                    xPointer = 0;
                    yPointer = 0;
                    var activity = activities[i-1];
                    var tool = tools[i - 1];
                    var time = times[i - 1];
                    var output = outputs[i - 1];
                    var note = notes[i - 1];

                    var yBaseTemp = rowHeights[i - 1];

                    var lane = graph.insertVertex(pool, null, '', xPointer, yTemp, wTotal, yBaseTemp);
                    var no = graph.insertVertex(lane, null, i, xPointer, yPointer, wNo, yBaseTemp);
                    xPointer = xPointer + wNo;
                    var act = graph.insertVertex(lane, null, activity, xPointer, yPointer, wAct, yBaseTemp, 'process_text');
                    xPointer = xPointer + wAct;
                    var actor = graph.insertVertex(lane, null, '', xPointer, yPointer, wActor, yBaseTemp, 'process_text');
                    for (var j = 0; j < nActor; j++) {
                        actorLoc[i - 1][j] = graph.insertVertex(actor, null, '', j * wBase, yPointer, wBase, yBaseTemp);
                    }
                    xPointer = xPointer + wActor;
                    var mutubaku = graph.insertVertex(lane, null, '', xPointer, yPointer, wMutu, yBaseTemp, 'process_text');
                    var syarat = graph.insertVertex(mutubaku, null, tool, 0, yPointer, wBase, yBaseTemp, 'process_text');
                    var waktu = graph.insertVertex(mutubaku, null, time, wBase, yPointer, wBase, yBaseTemp, 'process_text');
                    var keluaran = graph.insertVertex(mutubaku, null, output, 2 * wBase, yPointer, wBase, yBaseTemp, 'process_text');
                    xPointer = xPointer + wMutu;
                    var ket = graph.insertVertex(lane, null, note, xPointer, yPointer, wNote, yBaseTemp, 'process_text');
                    yTemp = yTemp + yBaseTemp;
                }

                // Off-Page Row
                if (end != nActivity) {
                    xPointer = 0;
                    yPointer = 0;
                    num = '';
                    actL = '';
                    toolL = '';
                    timeL = '';
                    outputL = '';
                    noteL = '';
                    if (end == nActivity) {
                        num = end;
                        actL = activities[i - 1];
                        toolL = tools[i - 1];
                        timeL = times[i - 1];
                        outputL = outputs[i - 1];
                        noteL = notes[i - 1];
                    }
                    var lane = graph.insertVertex(pool, null, '', xPointer, yTemp, wTotal, yOffPage);
                    var no = graph.insertVertex(lane, null, num, xPointer, yPointer, wNo, yOffPage);
                    xPointer = xPointer + wNo;
                    var act = graph.insertVertex(lane, null, actL, xPointer, yPointer, wAct, yOffPage, 'process_text');
                    xPointer = xPointer + wAct;
                    var actor = graph.insertVertex(lane, null, '', xPointer, yPointer, wActor, yOffPage, 'process_text');
                    var botRow = [];
                    for (var j = 0; j < nActor; j++) {
                        botRow[j] = graph.insertVertex(actor, null, '', j * wBase, yPointer, wBase, yOffPage);
                    }
                    xPointer = xPointer + wActor;
                    var mutubaku = graph.insertVertex(lane, null, '', xPointer, yPointer, wMutu, yOffPage, 'process_text');
                    var syarat = graph.insertVertex(mutubaku, null, toolL, 0, yPointer, wBase, yOffPage, 'process_text');
                    var waktu = graph.insertVertex(mutubaku, null, timeL, wBase, yPointer, wBase, yOffPage, 'process_text');
                    var keluaran = graph.insertVertex(mutubaku, null, '', 2 * wBase, yPointer, wBase, yOffPage, 'process_text');
                    xPointer = xPointer + wMutu;
                    var ket = graph.insertVertex(lane, null, noteL, xPointer, yPointer, wNote, yOffPage, 'process_text');
                    yTemp = yTemp + yOffPage;
                }


                // ===========================
                // 5. Pembuatan Flowchart
                //var shape = createArray(nActivity, nActor);
                xStart = wNo + wAct;
                yPointer = yHead;
                // Start Off Page
                if (start != 1) {
                    for (var z = 0; z < nActor; z++) {
                        xPointer = xStart;
                        if (graphLocation[start - 2][z] == 1) {
                            var top = z;
                            xPointer = xPointer + (z * wBase);
                            shape[nActivity][z] = graph.insertVertex(fcPool, null, '', xPointer + 25, yPointer + 10, 50, 25, 'off-page');
                            // console.log('top=' + top);
                            var d = 1;
                            var point0 = graph.insertVertex(shape[nActivity][z], null, '', 0, 0.5, d, d,
                                'portConstraint=west;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
                                'routingCenterX=1;routingCenterY=0;', true);
                            point0.geometry.offset = new mxPoint(-d, -(0.5 * d));
                            var point1 = graph.insertVertex(shape[nActivity][z], null, '', 1, 0.5, d, d,
                                'portConstraint=east;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
                                'routingCenterX=0;routingCenterY=0;', true);
                            point1.geometry.offset = new mxPoint(-d, -(0.5 * d));
                            var point2 = graph.insertVertex(shape[nActivity][z], null, '', 0.5, 0, d, d,
                                'portConstraint=north;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;', true);
                            point2.geometry.offset = new mxPoint(-(0.5 * d), -d);
                            var point3 = graph.insertVertex(shape[nActivity][z], null, '', 0.5, 1, d, d,
                                'portConstraint=south;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;', true);
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
                            xPointer = xPointer + (z * wBase);
                            var shapeWidth = 50;
                            var shapeHeight = 25;
                            if (graphShape[y][z] == 'state') {
                                shapeWidth = 50;
                                shapeHeight = 30;
                            }

                            var wCenter = wBase / 2;
                            var yCenter = rowHeights[y] / 2;
                            var xPoint = xPointer + (wCenter - shapeWidth / 2);
                            var yPoint = yPointer + (yCenter - shapeHeight / 2) + 7;
                            shape[y][z] = graph.insertVertex(fcPool, null, '', xPoint, yPoint, shapeWidth, shapeHeight, graphShape[y][z]);

                            var d = 1;
                            var point0 = graph.insertVertex(shape[y][z], null, '', 0, 0.5, d, d,
                                'portConstraint=west;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
                                'routingCenterX=1;routingCenterY=0;', true);
                            point0.geometry.offset = new mxPoint(-d, -(0.5 * d));
                            var point1 = graph.insertVertex(shape[y][z], null, '', 1, 0.5, d, d,
                                'portConstraint=east;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
                                'routingCenterX=0;routingCenterY=0;', true);
                            point1.geometry.offset = new mxPoint(-d, -(0.5 * d));
                            var point2 = graph.insertVertex(shape[y][z], null, '', 0.5, 0, d, d,
                                'portConstraint=north;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;', true);
                            point2.geometry.offset = new mxPoint(-(0.5 * d), -d);
                            var point3 = graph.insertVertex(shape[y][z], null, '', 0.5, 1, d, d,
                                'portConstraint=south;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;', true);
                            point3.geometry.offset = new mxPoint(-(0.5 * d), -d);
                            if (graphShape[y][z] == 'condition') {
                                var point4 = graph.insertVertex(shape[y][z], null, '', 0.25, 0.25, d, d,
                                    'portConstraint=west;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
                                    'routingCenterX=1;routingCenterY=0;', true);
                                point4.geometry.offset = new mxPoint(-d, -(0.5 * d));
                            } else {
                                var point4 = graph.insertVertex(shape[y][z], null, '', 0, 0.5, d, d,
                                    'portConstraint=west;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
                                    'routingCenterX=1;routingCenterY=0;', true);
                                point4.geometry.offset = new mxPoint(-d, -(0.5 * d));
                            }

                            // Membuat koneksi antar shape
                            if (start != 1 && y == start - 1) {
                                graph.insertEdge(fcPool, null, null, shape[nActivity][top].getChildAt(3), shape[y][z].getChildAt(2));
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
                            xPointer = xPointer + (z * wBase);
                            shape[nActivity + 1][z] = graph.insertVertex(fcPool, null, '', xPointer + 25, yPointer + 12, 50, 25, 'off-page');
                            var d = 1;
                            var point0 = graph.insertVertex(shape[nActivity + 1][z], null, '', 0, 0.5, d, d,
                                'portConstraint=west;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
                                'routingCenterX=1;routingCenterY=0;', true);
                            point0.geometry.offset = new mxPoint(-d, -(0.5 * d));
                            var point1 = graph.insertVertex(shape[nActivity + 1][z], null, '', 1, 0.5, d, d,
                                'portConstraint=east;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
                                'routingCenterX=0;routingCenterY=0;', true);
                            point1.geometry.offset = new mxPoint(-d, -(0.5 * d));
                            var point2 = graph.insertVertex(shape[nActivity + 1][z], null, '', 0.5, 0, d, d,
                                'portConstraint=north;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;', true);
                            point2.geometry.offset = new mxPoint(-(0.5 * d), -d);
                            var point3 = graph.insertVertex(shape[nActivity + 1][z], null, '', 0.5, 1, d, d,
                                'portConstraint=south;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;', true);
                            point3.geometry.offset = new mxPoint(-(0.5 * d), -d);
                            graph.insertEdge(fcPool, null, null, shape[end - 1][z].getChildAt(3), shape[nActivity + 1][z].getChildAt(2));
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

                // Connector
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
                                if (shape[k][l] != 0 && graphShape[k][l] != 'condition') {
                                    // Untuk shape biasa, buat koneksi ke semua shape di aktivitas berikutnya
                                    for (var m = 0; m < nActor; m++) {
                                        if (k + 1 < end && shape[k + 1][m] != 0) {
                                            graph.insertEdge(fcPool, null, null, shape[k][l].getChildAt(3), shape[k + 1][m].getChildAt(2));
                                        }
                                    }
                                }
                                else if (graphShape[k][l] == 'condition') {
                                    // Untuk condition shape, buat koneksi "Ya" ke semua shape di aktivitas berikutnya
                                    for (var m = 0; m < nActor; m++) {
                                        if (k + 1 < end && shape[k + 1][m] != 0) {
                                            graph.insertEdge(fcPool, null, 'Ya', shape[k][l].getChildAt(3), shape[k + 1][m].getChildAt(2), 'verticalAlign=bottom;align=right');
                                        }
                                    }
                                }
                            }

                            // Kedua, tangani khusus untuk kondisi "Tidak" (false path)
                            for (var l = 0; l < nActor; l++) {
                                if (graphShape[k][l] == 'condition') {
                                    convTo2Dim(falseData[k][l]);
                                    try {
                                        if (leftDot == 0) {
                                            graph.insertEdge(fcPool, null, 'Tidak', shape[k][l].getChildAt(0), shape[falseY][falseX].getChildAt(0), 'verticalAlign=bottom;align=left');
                                            leftDot = 1;
                                        } else if (rightDot == 0) {
                                            graph.insertEdge(fcPool, null, 'Tidak', shape[k][l].getChildAt(1), shape[falseY][falseX].getChildAt(1), 'verticalAlign=top;align=right');
                                            rightDot = 1;
                                        } else {
                                            graph.insertEdge(fcPool, null, 'Tidak', shape[k][l].getChildAt(0), shape[falseY][falseX].getChildAt(0), 'verticalAlign=bottom;align=left');
                                            leftDot = 1;
                                            rightDot = 0;
                                        }
                                    } catch {
                                        console.log("Condition in first row or target not found");
                                    }
                                }
                            }
                        }

                        // Condition Line
                        if (graphShape[k][l] == 'condition') {
                            convTo2Dim(falseData[k][l]);
                            try {
                                if (leftDot == 0) {
                                    graph.insertEdge(fcPool, null, 'Tidak', shape[k][l].getChildAt(0), shape[falseY][falseX].getChildAt(0), 'verticalAlign=bottom;align=left');
                                    leftDot = 1;
                                    // console.log('Dot: ' + leftDot + '-' + rightDot);
                                } else if (rightDot == 0) {
                                    graph.insertEdge(fcPool, null, 'Tidak', shape[k][l].getChildAt(1), shape[falseY][falseX].getChildAt(1), 'verticalAlign=top;align=right');
                                    rightDot = 1;
                                    // console.log('Dot: ' + leftDot + '-' + rightDot);
                                } else {
                                    graph.insertEdge(fcPool, null, 'Tidak', shape[k][l].getChildAt(0), shape[falseY][falseX].getChildAt(0), 'verticalAlign=bottom;align=left');
                                    leftDot = 1;
                                    rightDot = 0;
                                    // console.log('Reset Dot: ' + leftDot + '-' + rightDot);
                                }
                            } catch {
                                // console.log("Condition in first row");
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

    function createArray(length) {
        var arr = new Array(length || 0),
            i = length;

        if (arguments.length > 1) {
            var args = Array.prototype.slice.call(arguments, 1);
            while (i--) arr[length - 1 - i] = createArray.apply(this, args);
        }

        return arr;
    }
    </script>
</div>

@endsection
