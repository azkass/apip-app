var formCount = 0;
var nActor = 0;
var nAction = 0;
var nActivity = 1; // Default 1 activity
var actorNames = [];
var graphLocation;
var graphType = [0];
var graphShape;
var actorName = [];
var activities = [""]; // Initialize with one empty activity
var actorLoc = [];
var shape = [];
var tools = [""];
var times = [""];
var outputs = [""];
var notes = [""];
var rowHeights = [];
var tdBase = "";
var falseData;
var falseTarget;
var falseX = 0;
var falseY = 0;
var shapeSelections = [];
var falseToSelections = [];

// =====================
// Konstanta & Helper Umum
// =====================

// Tinggi maksimum satu halaman diagram
// Legal landscape: 14" x 8.5" (355.6 mm x 215.9 mm) - scaled down 75%
const scale = 1;
const pageW = Math.round(14 * 96 * scale); // 1344 px
const pageH = Math.round(8.5 * 96 * scale); // 612 px
const MAX_PAGE_HEIGHT = 790;
const PAGE_WIDTH = Math.round(14 * 96 * scale); // 14 inch × 96 dpi = 1344 px

/**
 * Pastikan `rowHeights` terisi. Jika kosong, hitung otomatis.
 */
function ensureRowHeights() {
    if (!rowHeights || rowHeights.length === 0) {
        rowHeights = [];
        for (let i = 0; i < nActivity; i++) {
            rowHeights.push(getRowHeight(i));
        }
    }
}

/**
 * Membagi aktivitas menjadi beberapa halaman.
 * @returns {Array<{start:number,end:number,height:number}>}
 */
function paginateActivities(maxHeight = MAX_PAGE_HEIGHT) {
    const pages = [];
    let start = 1;

    while (start <= nActivity) {
        let end = start;
        let height = 150; // header estimasi

        while (end <= nActivity) {
            const estimatedHeight = height + rowHeights[end - 1];
            if (estimatedHeight >= maxHeight) break;
            height = estimatedHeight;
            end++;
        }

        // Tambah ruang off-page connector bila bukan halaman pertama/terakhir
        if (start > 1 && end <= nActivity) {
            height += 50;
        }

        pages.push({ start, end: end - 1, height });
        start = end;
    }

    return pages;
}

export function addCustomActor(selectElement) {
    if (!selectElement?.parentElement) {
        console.error("Invalid select element or missing parent");
        return;
    }

    const parentDiv = selectElement.parentElement;
    const input = parentDiv.querySelector(".custom-actor-input");

    if (!input) {
        console.error("Custom actor input not found");
        return;
    }

    // Fungsi untuk kapitalisasi awal setiap kata
    const capitalizeWords = (str) =>
        str
            .toLowerCase()
            .split(" ")
            .filter(Boolean)
            .map((word) => word[0].toUpperCase() + word.slice(1))
            .join(" ");

    if (selectElement.value === "new-actor") {
        // Tampilkan input custom, sembunyikan select
        selectElement.classList.add("hidden");
        input.classList.remove("hidden");
        input.focus();

        const handleBlur = () => {
            const rawValue = input.value.trim();

            if (rawValue !== "") {
                const value = capitalizeWords(rawValue);

                // Cek jika sudah ada opsi yang sama
                const existingOption = Array.from(selectElement.options).find(
                    (opt) => opt.value.toLowerCase() === value.toLowerCase(),
                );

                if (!existingOption) {
                    const newOption = document.createElement("option");
                    newOption.value = value;
                    newOption.textContent = value;

                    const insertBeforeOption = Array.from(
                        selectElement.options,
                    ).find((opt) => opt.value === "new-actor");
                    selectElement.insertBefore(newOption, insertBeforeOption);
                }

                selectElement.value = value;
            } else {
                selectElement.value = "";
            }

            input.classList.add("hidden");
            selectElement.classList.remove("hidden");

            // Hapus event listener setelah dipakai
            input.removeEventListener("blur", handleBlur);
        };

        input.addEventListener("blur", handleBlur);

        input.addEventListener("keypress", (e) => {
            if (e.key === "Enter") input.blur();
        });
    } else {
        // Reset: sembunyikan input, tampilkan select
        input.classList.add("hidden");
        selectElement.classList.remove("hidden");
    }
}

export function addActor(btn = null) {
    const formContainer = document.querySelector("#formContainer");
    const template = document.querySelector("#formTemplate");
    formCount++;
    const formClone = template.content.cloneNode(true);
    formClone.querySelector(".actor-number").textContent = formCount;
    formContainer.appendChild(formClone);
}

export function deleteLastActor() {
    const formItems = document.querySelectorAll("#formContainer .form-item");
    if (formItems.length <= 1) {
        alert("Minimal terdapat satu pelaksana");
        return false;
    }
    formItems[formItems.length - 1].remove();
    formCount--;
    updateActorNumbers(formContainer);
    return true;
}

export function updateActorNumbers(container) {
    const actorNumbers = container.querySelectorAll(".actor-number");
    actorNumbers.forEach((number, index) => {
        number.textContent = index + 1;
    });
}

export function saveActor() {
    const formContainer = document.querySelector("#formContainer");
    const allSelects = formContainer.querySelectorAll("select");
    // Cek apakah ada select yang masih default (-- Pilih Aktor --)
    const hasUnselected = Array.from(allSelects).some(
        (select) => select.value === "" || select.value === null,
    );
    if (hasUnselected) {
        const unselectedIndexes = Array.from(allSelects)
            .map((select, index) => (select.value === "" ? index + 1 : -1))
            .filter((i) => i !== -1);
        alert(
            `Harap pilih aktor untuk Pelaksana ${unselectedIndexes.join(", ")}`,
        );
        return false;
    }
    // Lanjutkan dengan kode penyimpanan yang ada
    const newNActor = formContainer.querySelectorAll(".form-item").length;
    const newActorNames = Array.from(allSelects).map((select) => select.value);
    // Update actor count
    const prevNActor = nActor;
    nActor = newNActor;
    actorNames = newActorNames;
    // Adjust selections jika jumlah aktor berubah
    if (prevNActor !== nActor) {
        // Simpan data aktivitas jika kita sedang memuat data existing
        const savedActivities = [...activities];
        const savedTools = [...tools];
        const savedTimes = [...times];
        const savedOutputs = [...outputs];
        const savedNotes = [...notes];

        // Perbarui shape selections untuk jumlah aktor baru
        shapeSelections = shapeSelections.map((row) =>
            Array(nActor)
                .fill()
                .map((_, i) => (i < row.length ? row[i] : "0")),
        );
        falseToSelections = falseToSelections.map((row) =>
            Array(nActor)
                .fill()
                .map((_, i) => (i < row.length ? row[i] : "")),
        );

        // Jika flag doNotOverwriteActivities aktif, kembalikan data aktivitas yang disimpan
        if (window.doNotOverwriteActivities) {
            activities = savedActivities;
            tools = savedTools;
            times = savedTimes;
            outputs = savedOutputs;
            notes = savedNotes;
        }
    }

    setupActivityForm();
    return true;
}

export function addActivity() {
    // Cek apakah ada aktivitas yang memiliki opsi "Selesai"
    for (let i = 0; i < nActivity; i++) {
        if (shapeSelections[i].includes("4")) {
            alert(
                'Tidak dapat menambah aktivitas karena sudah ada opsi "Selesai"!',
            );
            return;
        }
    }
    nActivity++;
    activities.push("");
    tools.push("");
    times.push("");
    outputs.push("");
    notes.push("");
    shapeSelections.push(Array(nActor).fill("0"));
    falseToSelections.push(Array(nActor).fill(""));
    setupActivityForm();
}

export function deleteLastActivity() {
    if (nActivity > 1) {
        nActivity--;
        activities.pop();
        tools.pop();
        times.pop();
        outputs.pop();
        notes.pop();
        shapeSelections.pop();
        falseToSelections.pop();
        setupActivityForm();
    } else {
        alert("Minimal terdapat 1 Aktivitas");
    }
}

export function loadExistingData(jsonData) {
    if (!jsonData) {
        console.error("No JSON data provided to loadExistingData");
        return false;
    }

    try {
        // Load actor data
        if (jsonData.actorName && Array.isArray(jsonData.actorName)) {
            actorNames = jsonData.actorName;
            nActor = jsonData.nActor || actorNames.length;
            actorName = actorNames;
        }

        // Load activity data
        if (jsonData.activities && Array.isArray(jsonData.activities)) {
            // Simpan ke variabel global
            window.isLoadingExistingData = true;
            activities = jsonData.activities;
            tools = jsonData.tools || Array(activities.length).fill("");
            times = jsonData.times || Array(activities.length).fill("");
            outputs = jsonData.outputs || Array(activities.length).fill("");
            notes = jsonData.notes || Array(activities.length).fill("");
            nActivity = jsonData.nActivity || activities.length;

            // Initialize shape selections from graphShape if available
            if (jsonData.graphShape && Array.isArray(jsonData.graphShape)) {
                shapeSelections = [];
                for (let i = 0; i < jsonData.graphShape.length; i++) {
                    const row = [];
                    for (let j = 0; j < jsonData.graphShape[i].length; j++) {
                        let shapeValue = "0"; // default
                        switch (jsonData.graphShape[i][j]) {
                            case "state":
                                if (i === 0) {
                                    shapeValue = "1"; // Mulai
                                } else {
                                    // Check if this is the last activity with state
                                    let isLast = true;
                                    for (
                                        let k = i + 1;
                                        k < jsonData.graphShape.length;
                                        k++
                                    ) {
                                        if (
                                            jsonData.graphShape[k][j] ===
                                            "state"
                                        ) {
                                            isLast = false;
                                            break;
                                        }
                                    }
                                    shapeValue = isLast ? "4" : "2"; // Selesai or Proses
                                }
                                break;
                            case "process":
                                shapeValue = "2"; // Proses
                                break;
                            case "condition":
                                shapeValue = "3"; // Pilihan
                                break;
                            default:
                                shapeValue = "0"; // Tidak ada
                        }
                        row.push(shapeValue);
                    }
                    shapeSelections.push(row);
                }
            } else {
                // Create default shape selections
                shapeSelections = Array(nActivity)
                    .fill()
                    .map(() => Array(nActor).fill("0"));
            }

            // Initialize false to selections
            if (jsonData.falseData && Array.isArray(jsonData.falseData)) {
                falseToSelections = jsonData.falseData.map((row) => {
                    return row.map((cell) => cell || "");
                });
            } else {
                falseToSelections = Array(nActivity)
                    .fill()
                    .map(() => Array(nActor).fill(""));
            }
        }

        rowHeights = jsonData.rowHeights || [];
        graphLocation = jsonData.graphLocation;
        graphShape = jsonData.graphShape;

        // Setup the activity form with loaded data
        setupActivityForm();

        // Reset loading flag after setup
        window.isLoadingExistingData = false;

        // Explicitly make the diagram section visible
        const diagramSection = document.getElementById("diagramSection");
        if (diagramSection) {
            diagramSection.classList.remove("hidden");
        } else {
            console.error("Could not find diagram section element");
        }

        return true;
    } catch (e) {
        console.error("Error loading existing data:", e);
        window.isLoadingExistingData = false;
        return false;
    }
}

export function setupActivityForm() {
    // Validasi awal: pastikan daftar aktor sudah diisi
    if (actorNames.length === 0) {
        alert("Silakan simpan nama pelaksana terlebih dahulu!");
        return;
    }

    // Ambil nilai input hanya jika bukan sedang memuat data
    if (!window.isLoadingExistingData) {
        for (let i = 0; i < nActivity; i++) {
            const actNum = i + 1;
            const getVal = (id) => document.getElementById(id)?.value || "";

            activities[i] = getVal(`act-${actNum}`);
            tools[i] = getVal(`tool-${actNum}`);
            times[i] = getVal(`time-${actNum}`).replace(/ jam/g, "");
            outputs[i] = getVal(`output-${actNum}`);
            notes[i] = getVal(`note-${actNum}`);

            shapeSelections[i] = [];
            falseToSelections[i] = [];
            for (let j = 0; j < nActor; j++) {
                const actorNum = j + 1;
                shapeSelections[i][j] =
                    getVal(`gShape-${actNum}-${actorNum}`) || "0";
                falseToSelections[i][j] = getVal(
                    `falseTo-${actNum}-${actorNum}`,
                );
            }
        }
    }

    // Persiapkan DOM fragment & tabel
    const tableDiv = document.getElementById("diagramTable");
    const fragment = document.createDocumentFragment();
    const table = document.createElement("table");
    table.id = "diagramTable";
    table.className = "w-full border-collapse";

    // Header tabel
    const headers = [
        { text: "No." },
        { text: "Aktivitas" },
        ...actorNames.map((name) => ({ text: name })),
        { text: "Kelengkapan" },
        { text: "Waktu (Jam)" },
        { text: "Output" },
        { text: "Keterangan" },
    ];

    const headerRow = document.createElement("tr");
    headerRow.className = "bg-gray-100";
    headerRow.innerHTML = headers
        .map((h) => `<th class="border p-2">${h.text}</th>`)
        .join("");
    table.appendChild(headerRow);

    // Deteksi apakah ada aktivitas yang mengandung "Selesai"
    const hasSelesai = shapeSelections.some((row) => row.includes("4"));

    // Buat baris aktivitas
    for (let i = 1; i <= nActivity; i++) {
        const row = document.createElement("tr");
        row.className = "hover:bg-gray-50";

        const shapeRow = shapeSelections[i - 1];
        const firstSpecial = shapeRow.findIndex((v) =>
            ["1", "3", "4"].includes(v),
        );

        const actorCells = shapeRow
            .map((val, j) => {
                const actorNum = j + 1;
                const disabled = firstSpecial !== -1 && j !== firstSpecial;
                const showFalseTo = val === "3";
                const options = [
                    { value: "0", label: "Tidak ada", show: true },
                    { value: "1", label: "Mulai", show: i === 1 },
                    { value: "2", label: "Proses", show: i !== 1 },
                    { value: "3", label: "Pilihan", show: i !== 1 },
                    { value: "4", label: "Selesai", show: i === nActivity },
                ]
                    .filter((opt) => opt.show)
                    .map(
                        (opt) =>
                            `<option value="${opt.value}" ${val === opt.value ? "selected" : ""}>${opt.label}</option>`,
                    )
                    .join("");

                return `
                <td class="border p-2">
                    <select id="gShape-${i}-${actorNum}" class="w-full p-1 border rounded ${disabled ? "opacity-50" : ""}" ${disabled ? "disabled" : ""}>
                        ${options}
                    </select>
                    <div id="f-${i}-${actorNum}" class="form-group ${showFalseTo ? "" : "hidden"} mt-2">
                        <label class="block text-xs text-gray-600 mb-1">Kondisi Salah ke:</label>
                        <select id="falseTo-${i}-${actorNum}" class="w-full p-1 border rounded text-xs">
                            ${generateFalseOptions(i, actorNum, falseToSelections[i - 1][j])}
                        </select>
                    </div>
                </td>`;
            })
            .join("");

        row.innerHTML = `
            <td class="border p-2">${i}</td>
            <td class="border p-2"><textarea id="act-${i}" class="w-full p-1 border rounded" placeholder="Deskripsi Aktivitas" autocomplete="off">${activities[i - 1]}</textarea></td>
            ${actorCells}
            <td class="border p-2"><input id="tool-${i}" class="w-full border rounded p-1 text-sm" value="${tools[i - 1]}" placeholder="Alat/bahan" autocomplete="off" /></td>
            <td class="border p-2"><input id="time-${i}" class="w-full border rounded p-1 text-sm" value="${times[i - 1] ? times[i - 1] + " jam" : ""}" placeholder="Waktu (Jam)" autocomplete="off" /></td>
            <td class="border p-2"><input id="output-${i}" class="w-full border rounded p-1 text-sm" value="${outputs[i - 1]}" placeholder="Output" autocomplete="off" /></td>
            <td class="border p-2"><input id="note-${i}" class="w-full border rounded p-1 text-sm" value="${notes[i - 1]}" placeholder="Catatan" autocomplete="off" /></td>
        `;
        table.appendChild(row);
    }

    fragment.appendChild(table);
    tableDiv.innerHTML = "";
    tableDiv.appendChild(fragment);
    document.getElementById("diagramSection").classList.remove("hidden");

    // Event listener untuk SEMUA DROPDOWN SHAPE
    tableDiv.querySelectorAll('select[id^="gShape-"]').forEach((select) => {
        select.addEventListener("change", (e) => {
            // Logika disable dropdown lain jika perlu
            const [_, actNum, actorNum] = e.target.id.split("-");
            const selected = e.target.value;

            if (["1", "3", "4"].includes(selected)) {
                for (let j = 1; j <= nActor; j++) {
                    if (j.toString() !== actorNum) {
                        const other = document.getElementById(
                            `gShape-${actNum}-${j}`,
                        );
                        if (other) {
                            other.value = "0";
                            document
                                .getElementById(`f-${actNum}-${j}`)
                                ?.classList.add("hidden");
                        }
                    }
                }
            }
            setupActivityForm(); // PEMANGGILAN REFRESH
        });
    });

    // TAMBAHKAN: Event listener untuk DROPDOWN "KONDISI SALAH"
    tableDiv.querySelectorAll('select[id^="falseTo-"]').forEach((select) => {
        select.addEventListener("change", () => {
            setupActivityForm(); // PEMANGGILAN REFRESH
        });
    });

    // Event listener untuk input waktu
    tableDiv.querySelectorAll('input[id^="time-"]').forEach((input) => {
        input.addEventListener("focus", () => {
            input.value = input.value.replace(/ jam/g, "");
        });
        input.addEventListener("blur", () => {
            if (input.value && !isNaN(input.value.trim())) {
                input.value = input.value.trim() + " jam";
            }
        });
    });

    // Nonaktifkan tombol tambah jika sudah ada "Selesai"
    const addBtn = document.getElementById("add-activity");
    addBtn.disabled = hasSelesai;
    addBtn.classList.toggle("opacity-50", hasSelesai);
    addBtn.classList.toggle("cursor-not-allowed", hasSelesai);
    addBtn.classList.toggle("hover:bg-blue-600", !hasSelesai);
}

export function generateFalseOptions(i, j, selectedValue) {
    // Inisialisasi flag untuk memeriksa apakah ada opsi selain default
    let hasOption = false;

    // Inisialisasi opsi default pada dropdown
    let options = `<option value="" ${!selectedValue ? "selected disabled" : ""}>-- Pilih Tujuan --</option>`;

    // Loop aktivitas dimulai dari 2 hingga sebelum aktivitas saat ini (i)
    for (let row = 2; row < i; row++) {
        // Loop setiap aktor
        for (let col = 1; col <= nActor; col++) {
            // Lewati jika baris dan kolom saat ini adalah posisi asal
            if (row === i && col === j) continue;

            // Cek apakah shapeSelections memiliki nilai selain '0' untuk aktor ini di aktivitas tersebut
            if (
                shapeSelections?.[row - 1]?.[col - 1] &&
                shapeSelections[row - 1][col - 1] !== "0"
            ) {
                // Hitung nilai unik untuk opsi berdasarkan posisi
                const value = (row - 1) * nActor + col;

                // Ambil nama aktor, fallback ke "Pelaksana X" jika tidak tersedia
                const actorName = actorNames[col - 1] || `Pelaksana ${col}`;

                // Tandai jika opsi ini merupakan nilai yang sedang dipilih
                const selected = value == selectedValue ? "selected" : "";

                // Tambahkan opsi ke dropdown
                options += `<option value="${value}" ${selected}>Aktivitas ${row}, ${actorName}</option>`;

                // Tandai bahwa ada opsi selain default
                hasOption = true;
            }
        }
    }

    // Jika tidak ada opsi valid, tampilkan pesan bahwa tujuan tidak tersedia
    if (!hasOption) {
        options = `<option value="" selected disabled>Tidak ada tujuan tersedia</option>`;
    }

    // Kembalikan string HTML opsi untuk digunakan di elemen select
    return options;
}

export function getRowHeight(row) {
    var activity = activities[row];
    var tool = tools[row];
    var time = times[row];
    var output = outputs[row];
    var note = notes[row];

    var height = 80;
    var maxHeight = 620;
    var contents = [activity, tool, time, output, note];
    contents.forEach((text) => {
        var nRow = text.length / 16;
        var yRow = nRow * 13 + 20;
        if (yRow > maxHeight) yRow = maxHeight;
        if (yRow > height) height = yRow;
    });
    return height;
}

export function loadData() {
    console.log("Loading page elements");
    // Reset row heights to recalculate fresh on each load
    rowHeights = [];
    graphShape = createArray(nActivity, nActor);
    graphLocation = createArray(nActivity, nActor);
    falseData = createArray(nActivity, nActor);
    falseTarget = createArray(nActivity, nActor);
    var count;

    actorName = actorNames; // Use the saved actorNames

    for (let i = 1; i <= nActivity; i++) {
        count = 1;
        for (let j = 1; j <= nActor; j++) {
            falseTarget[i - 1][j - 1] = 0;
            shape = document.getElementById("gShape-" + i + "-" + j).value;
            switch (shape) {
                case "1":
                    graphShape[i - 1][j - 1] = "state";
                    break;
                case "2":
                    graphShape[i - 1][j - 1] = "process";
                    break;
                case "3":
                    graphShape[i - 1][j - 1] = "condition";
                    falseData[i - 1][j - 1] = document.getElementById(
                        "falseTo-" + i + "-" + j,
                    ).value;
                    convTo2Dim(falseData[i - 1][j - 1]);
                    falseTarget[falseY][falseX] = 1;
                    break;
                case "4":
                    graphShape[i - 1][j - 1] = "state";
                    break;
                default:
                    graphShape[i - 1][j - 1] = 0;
                    break;
            }
            if (shape != "0" && falseTarget[i - 1][j - 1] == 0) {
                graphLocation[i - 1][j - 1] = count;
                count++;
            } else {
                graphLocation[i - 1][j - 1] = 0;
            }
        }
        // Kapitalisasi huruf pertama aktivitas
        let actVal = document.getElementById("act-" + i).value;
        if (actVal && actVal.length > 0) {
            actVal = actVal.charAt(0).toUpperCase() + actVal.slice(1);
        }
        activities[i - 1] = actVal;
        tools[i - 1] = document.getElementById("tool-" + i).value;
        times[i - 1] = document.getElementById("time-" + i).value;
        outputs[i - 1] = document.getElementById("output-" + i).value;
        notes[i - 1] = document.getElementById("note-" + i).value;

        rowHeights.push(getRowHeight(i - 1));
    }
    // console.log(
    //     JSON.stringify(
    //         {
    //             nActivity,
    //             nActor,
    //             actorNames,
    //             activities,
    //             tools,
    //             times,
    //             outputs,
    //             notes,
    //         },
    //         null,
    //         2,
    //     ),
    // );
    //
}

export async function preview() {
    // Validasi sebelum lanjut preview
    const validationError = validateDiagram();
    if (validationError) {
        alert(validationError);
        return false;
    }
    // Tampilkan kotak preview
    document.getElementById("previewBox").classList.remove("hidden");

    // Kumpulkan data terbaru dari form
    loadData();

    // Render semua halaman dan ambil JSON terakhir
    const lastJsonBody = renderDetailPages(true);

    // Kirim data ke server
    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]',
    )?.content;
    if (!csrfToken) {
        console.error("CSRF token tidak ditemukan!");
        return;
    }

    try {
        const id =
            document.getElementById("prosedur-container").dataset.prosedurId;
        const response = await axios.put(
            `/prosedur-pengawasan/${id}/body`,
            { isi: lastJsonBody },
            {
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
            },
        );
        console.log("Data tersimpan:", response.data);
    } catch (error) {
        console.error("Gagal menyimpan:", error);
    }

    return true;
}

// Validasi diagram sebelum preview
function validateDiagram() {
    // 1. Cek false option: HANYA untuk shape 'condition' (graphShape[i][j] === 'condition')
    for (let i = 0; i < nActivity; i++) {
        for (let j = 0; j < nActor; j++) {
            if (graphShape?.[i]?.[j] === "condition") {
                // Cek apakah dropdown false option ini seharusnya punya opsi
                let hasOption = false;
                for (let row = 2; row < i + 1; row++) {
                    for (let col = 1; col <= nActor; col++) {
                        if (row === i + 1 && col === j + 1) continue;
                        if (
                            shapeSelections?.[row - 1]?.[col - 1] &&
                            shapeSelections[row - 1][col - 1] !== "0"
                        ) {
                            hasOption = true;
                        }
                    }
                }
                if (
                    hasOption &&
                    (!falseToSelections[i] ||
                        !falseToSelections[i][j] ||
                        falseToSelections[i][j] === "")
                ) {
                    return `Error: Aktivitas ${i + 1}, Pelaksana ${actorNames[j] || j + 1} (aktivitas "pilihan") belum memilih tujuan "Tidak".`;
                }
            }
        }
    }
    // 2. Cek setiap aktor punya minimal satu shape (selain '0')
    for (let j = 0; j < nActor; j++) {
        let found = false;
        for (let i = 0; i < nActivity; i++) {
            if (shapeSelections?.[i]?.[j] && shapeSelections[i][j] !== "0") {
                found = true;
                break;
            }
        }
        if (!found) {
            return `Error: Pelaksana ${actorNames[j] || j + 1} belum memiliki bentuk pada diagram.`;
        }
    }
    // 3. Aktivitas terakhir harus punya minimal satu shape 'Selesai' (4)
    // let lastHasFinish = false;
    // for (let j = 0; j < nActor; j++) {
    //     if (shapeSelections?.[nActivity - 1]?.[j] === "4") {
    //         lastHasFinish = true;
    //         break;
    //     }
    // }
    // if (!lastHasFinish) {
    //     return `Error: Aktivitas terakhir harus memiliki minimal satu pelaksana dengan bentuk 'Selesai'.`;
    // }
    // return null;
}

export function renderDetailPages(captureJson = false) {
    const mainContainer = document.getElementById("graphContainer");
    if (!mainContainer) {
        console.error("graphContainer not found");
        return;
    }

    // Clear previous drawings
    mainContainer.innerHTML = "";

    let totalHeight = 0;
    const maxWidth = PAGE_WIDTH; // align with cover width

    // Prepare shared arrays for draw()
    actorLoc = createArray(nActivity + 1, nActor);
    shape = createArray(nActivity + 2, nActor);

    // Pastikan rowHeights dan pagination siap
    ensureRowHeights();
    const pages = paginateActivities();

    let lastJsonBody = null;
    const padding = 35; // 10mm in pixels (96 DPI)

    pages.forEach(({ start, end, height }) => {
        totalHeight += height + padding * 2;

        const container = document.createElement("div");
        container.style = `position:relative;overflow:hidden;width:${maxWidth + 1 + padding * 2}px;height:${height + padding * 2}px;cursor:default;padding:${padding}px;box-sizing:border-box;`;
        mainContainer.append(container);

        const json = draw(container, start, end);
        if (captureJson) lastJsonBody = json;
    });

    // Adjust outer wrapper height if it exists
    const mainContainerBox = document.getElementById("graphContainerBox");
    if (mainContainerBox) {
        mainContainerBox.style = `height:${totalHeight}px;`;
    }

    return captureJson ? lastJsonBody : undefined;
}

export function convTo2Dim(x) {
    falseX = (x % nActor) - 1;
    falseY = Math.floor(x / nActor);
    if (falseX == -1) {
        falseX = falseX + nActor;
        falseY = falseY - 1;
    }
}

export function createArray(length) {
    var arr = new Array(length || 0),
        i = length;

    if (arguments.length > 1) {
        var args = Array.prototype.slice.call(arguments, 1);
        while (i--) arr[length - 1 - i] = createArray.apply(this, args);
    }

    return arr;
}

export function draw(container, start, end) {
    // =====================================
    // 1. Inisialisasi dan Konfigurasi Dasar

    // Reset the preview container
    container.innerHTML = "";
    let pageSize = 5;

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

        // container.style.border = "1px solid #808080";

        // =====================================
        // 2. Setup Graph Utama
        var graph = new mxGraph(container);
        graph.setHtmlLabels(true);
        graph.setEnabled(false);
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
        style[mxConstants.STYLE_FONTSIZE] = 14;
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
        style[mxConstants.STYLE_IMAGE] = "/img/off-page.png";
        style[mxConstants.STYLE_FONTSIZE] = 14;
        style[mxConstants.STYLE_FONTSTYLE] = 1;
        style[mxConstants.STYLE_STROKECOLOR] = "black";
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
            vertexStyle[mxConstants.STYLE_FONTFAMILY] = "Arial";

            // Menentukan ukuran dan tata letak semua komponen tabel.
            var xPointer = 0; // Penanda posisi X saat ini
            var yPointer = 0; // Penanda posisi Y saat ini

            // ============================
            // Menentukan lebar kolom dinamis
            // ============================

            var wBase = 100; // Lebar dasar per aktor (bisa disesuaikan)
            const wNo = 40; // "No." selalu tetap
            const wNote = 120; // "Keterangan" tetap

            // Nilai awal mutu = 3 × wBase
            var wMutu = wBase * 3;

            // Hitung ulang agar total sama dengan PAGE_WIDTH
            var wActor = wBase * nActor;
            var remaining = PAGE_WIDTH - (wNo + wMutu + wNote + wActor);

            // Sisakan ruang untuk kolom Aktivitas (minimal 100 px)
            var wAct = remaining >= 100 ? remaining : 100;

            // Jika remaining negatif (terlalu banyak aktor), perkecil wBase agar muat
            if (remaining < 100) {
                // Hitung wBase baru agar total pas dan wAct = 100
                wBase = Math.floor(
                    (PAGE_WIDTH - (wNo + 100 + wMutu + wNote)) / nActor,
                );
                if (wBase < 50) wBase = 50; // batas bawah

                wMutu = wBase * 3;
                wActor = wBase * nActor;
                wAct = PAGE_WIDTH - (wNo + wActor + wMutu + wNote);
            }

            var wTotal = PAGE_WIDTH; // Pastikan total = lebar halaman

            // Menentukan tinggi setiap baris
            var yHeadTop = 25; // Tinggi bagian atas header
            var yHeadBottom = 55; // Tinggi bagian bawah header
            var yNumberRow = 20; // Tinggi baris penomoran kolom
            var yHead = yHeadTop + yHeadBottom + yNumberRow; // Tinggi total header termasuk penomoran
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
                "No",
                xPointer,
                yPointer,
                wNo,
                yHeadTop + yHeadBottom,
            );
            xPointer = xPointer + wNo;
            var act = graph.insertVertex(
                lane1,
                null,
                "Aktivitas",
                xPointer,
                yPointer,
                wAct,
                yHeadTop + yHeadBottom,
            );
            xPointer = xPointer + wAct;
            var actor = graph.insertVertex(
                lane1,
                null,
                "Pelaksana",
                xPointer,
                yPointer,
                wActor,
                yHeadTop + yHeadBottom,
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
                yHeadTop + yHeadBottom,
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
                "Ket",
                xPointer,
                yPointer,
                wNote,
                yHeadTop + yHeadBottom,
            );

            // Pembuatan baris penomoran kolom (di bawah header)
            var columnNumber = 1;
            var xTemp = 0; // Reset xTemp ke posisi awal
            var yNumberPos = yPointer + yHeadTop + yHeadBottom; // Posisi Y untuk baris penomoran

            // Nomor untuk kolom No
            var numNo = graph.insertVertex(
                lane1,
                null,
                "(" + columnNumber + ")",
                xTemp,
                yNumberPos,
                wNo,
                yNumberRow,
                "align=center;verticalAlign=middle;fontSize=12;fontStyle=1",
            );
            xTemp += wNo;
            columnNumber++;

            // Nomor untuk kolom Aktivitas
            var numAct = graph.insertVertex(
                lane1,
                null,
                "(" + columnNumber + ")",
                xTemp,
                yNumberPos,
                wAct,
                yNumberRow,
                "align=center;verticalAlign=middle;fontSize=12;fontStyle=1",
            );
            xTemp += wAct;
            columnNumber++;

            // Nomor untuk kolom Actor (dinamis)
            for (var i = 1; i <= nActor; i++) {
                var numActor = graph.insertVertex(
                    lane1,
                    null,
                    "(" + columnNumber + ")",
                    xTemp + (i - 1) * wBase,
                    yNumberPos,
                    wBase,
                    yNumberRow,
                    "align=center;verticalAlign=middle;fontSize=12;fontStyle=1",
                );
                columnNumber++;
            }
            xTemp += wActor;

            // Nomor untuk kolom Kelengkapan
            var numKel = graph.insertVertex(
                lane1,
                null,
                "(" + columnNumber + ")",
                xTemp,
                yNumberPos,
                wBase,
                yNumberRow,
                "align=center;verticalAlign=middle;fontSize=12;fontStyle=1",
            );
            columnNumber++;

            // Nomor untuk kolom Waktu
            var numWaktu = graph.insertVertex(
                lane1,
                null,
                "(" + columnNumber + ")",
                xTemp + wBase,
                yNumberPos,
                wBase,
                yNumberRow,
                "align=center;verticalAlign=middle;fontSize=12;fontStyle=1",
            );
            columnNumber++;

            // Nomor untuk kolom Output
            var numOutput = graph.insertVertex(
                lane1,
                null,
                "(" + columnNumber + ")",
                xTemp + 2 * wBase,
                yNumberPos,
                wBase,
                yNumberRow,
                "align=center;verticalAlign=middle;fontSize=12;fontStyle=1",
            );
            xTemp += wMutu;
            columnNumber++;

            // Nomor untuk kolom Keterangan
            var numKet = graph.insertVertex(
                lane1,
                null,
                "(" + columnNumber + ")",
                xTemp,
                yNumberPos,
                wNote,
                yNumberRow,
                "align=center;verticalAlign=middle;fontSize=12;fontStyle=1",
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
                var num = "";
                var actL = "";
                var toolL = "";
                var timeL = "";
                var outputL = "";
                var noteL = "";
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
                            xPointer + 15,
                            yPointer + 10,
                            70,
                            35,
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
                            xPointer + 15,
                            yPointer + 10,
                            70,
                            35,
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

                    // Condition Line
                    if (graphShape[k][l] == "condition") {
                        convTo2Dim(falseData[k][l]);
                        try {
                            if (leftDot == 0) {
                                graph.insertEdge(
                                    fcPool,
                                    null,
                                    "Tidak",
                                    shape[k][l].getChildAt(0),
                                    shape[falseY][falseX].getChildAt(0),
                                    "verticalAlign=bottom;align=left",
                                );
                                leftDot = 1;
                                // console.log('Dot: ' + leftDot + '-' + rightDot);
                            } else if (rightDot == 0) {
                                graph.insertEdge(
                                    fcPool,
                                    null,
                                    "Tidak",
                                    shape[k][l].getChildAt(1),
                                    shape[falseY][falseX].getChildAt(1),
                                    "verticalAlign=top;align=right",
                                );
                                rightDot = 1;
                                // console.log('Dot: ' + leftDot + '-' + rightDot);
                            } else {
                                graph.insertEdge(
                                    fcPool,
                                    null,
                                    "Tidak",
                                    shape[k][l].getChildAt(0),
                                    shape[falseY][falseX].getChildAt(0),
                                    "verticalAlign=bottom;align=left",
                                );
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
            // console.log("Type of data draw():", typeof jsonBody);
            // console.log("data draw:", jsonBody);
            // console.log("Type of data draw():", typeof GraphData);
            // console.log("data draw:", GraphData);
            return jsonBody;
        }
    }
}
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

function initDetailPage() {
    // Cek jika kita berada di halaman detail yang memiliki data `prosedurDetailData`
    if (window.prosedurDetailData) {
        const GraphData = window.prosedurDetailData;

        // Pastikan data tidak kosong
        if (GraphData && Object.keys(GraphData).length > 0) {
            // Assign data dari global variable ke module-level variables
            nActor = GraphData.nActor;
            actorName = GraphData.actorName;
            nActivity = GraphData.nActivity;
            rowHeights = GraphData.rowHeights;
            activities = GraphData.activities;
            tools = GraphData.tools;
            times = GraphData.times;
            outputs = GraphData.outputs;
            notes = GraphData.notes;
            graphLocation = GraphData.graphLocation;
            graphShape = GraphData.graphShape;
            shape = GraphData.shape;
            falseData = GraphData.falseData;
            actorLoc = GraphData.actorLoc;

            // Render seluruh halaman SOP menggunakan pagination seperti pada preview
            renderDetailPages();
            // console.log("data db:", typeof GraphData);
            console.log("data isi:", GraphData);
        }
    }
}

document.addEventListener("DOMContentLoaded", initDetailPage);
