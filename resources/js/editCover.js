// === Dynamic Multi-Input Form Handler ===
// Section config: id & label
const sections = {
    dasarHukum: "Dasar Hukum",
    keterkaitan: "Keterkaitan",
    peringatan: "Peringatan",
    kualifikasi: "Kualifikasi Pelaksanaan",
    peralatan: "Peralatan/Perlengkapan",
    pencatatan: "Pencatatan dan Pendataan",
};

// Tambah input baru pada section
function addField(section) {
    const list = document.getElementById(section + "List");
    if (!list) return;
    const idx = list.children.length;
    const div = document.createElement("div");
    div.className = "flex items-center gap-2 mb-2";
    div.innerHTML = `<input type="text" name="${section}[]" class="form-input flex-1" placeholder="${sections[section]} ke-${idx + 1}" />`;
    list.appendChild(div);
    updateRemoveButton(section);
}

// Enable/disable tombol hapus sesuai jumlah input
function updateRemoveButton(section) {
    const list = document.getElementById(section + "List");
    const removeBtn = document.getElementById(section + "-remove-btn");
    if (!removeBtn) return;
    const inputCount = list.childElementCount;
    removeBtn.disabled = inputCount <= 1;
    removeBtn.classList.toggle("opacity-50", removeBtn.disabled);
    removeBtn.classList.toggle("cursor-not-allowed", removeBtn.disabled);
}

// Hapus input terakhir pada section
function removeLastField(section) {
    const list = document.getElementById(section + "List");
    if (!list || list.childElementCount <= 1) {
        alert(sections[section] + " minimal 1 item!");
        return;
    }
    list.lastElementChild.remove();
    updateRemoveButton(section);
}

// Inisialisasi input default & validasi submit
window.addEventListener("DOMContentLoaded", () => {
    for (const section in sections) {
        const list = document.getElementById(section + "List");
        if (!list) continue;
        let data = [];
        if (window.coverData && Array.isArray(window.coverData[section])) {
            data = window.coverData[section];
        } else if (window.coverData && typeof window.coverData[section] === "string" && window.coverData[section].length > 0) {
            try {
                data = JSON.parse(window.coverData[section]);
            } catch {
                data = [window.coverData[section]];
            }
        }
        if (!data || data.length === 0) data = [""];
        list.innerHTML = "";
        data.forEach((val, idx) => {
            const div = document.createElement("div");
            div.className = "flex items-center gap-2 mb-2";
            // Escape value agar aman
            const safeVal = (val ?? "").toString().replace(/"/g, "&quot;");
            div.innerHTML = `<input type="text" name="${section}[]" class="form-input flex-1" value="${safeVal}" placeholder="${sections[section]} ke-${idx + 1}" />`;
            list.appendChild(div);
        });
        updateRemoveButton(section);
    }
    // Cache form dan section element
    const form = document.getElementById("editCoverForm");
    const sectionLists = {};
    for (const section in sections) {
        sectionLists[section] = document.getElementById(section + "List");
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        let valid = true;
        let firstInvalid = null;
        const messages = [];
        for (const section in sections) {
            const list = sectionLists[section];
            const filled = Array.from(
                list.querySelectorAll('input[type="text"]'),
            ).filter((inp) => inp.value.trim() !== "");
            if (filled.length < 1) {
                valid = false;
                messages.push(
                    sections[section] + " wajib diisi minimal 1 item!",
                );
                if (!firstInvalid) firstInvalid = list;
            }
        }
        if (!valid) {
            alert(messages.join("\n"));
            if (firstInvalid)
                firstInvalid.scrollIntoView({
                    behavior: "smooth",
                    block: "center",
                });
            return;
        }
        // Kumpulkan data cover dari input dinamis
        const coverData = {};
        for (const section in sections) {
            coverData[section] = Array.from(
                sectionLists[section].querySelectorAll("input"),
            ).map((i) => i.value);
        }
        axios
            .put(form.action || window.location.href, {
                cover: JSON.stringify(coverData),
            })
            .then(function (res) {
                // Langsung gunakan data dari response PUT untuk generate graph
                generateCoverMxGraph(res.data);
                if (typeof showLanjutButton === "function") {
                    showLanjutButton();
                }
            })
            .catch(function (err) {
                let msg = "Gagal menyimpan data.";
                if (
                    err.response &&
                    err.response.data &&
                    err.response.data.errors
                ) {
                    msg +=
                        "\n" +
                        Object.values(err.response.data.errors)
                            .flat()
                            .join("\n");
                }
                alert(msg);
            });
    });
});

// Generate cover SOP menggunakan mxGraph
function generateCoverMxGraph(data) {
    const container = document.getElementById("coverContainer");
    container.innerHTML = "";

    // Legal landscape: 14" x 8.5" (355.6 mm x 215.9 mm) - scaled down 75%
    const scale = 1;
    const pageW = Math.round(14 * 96 * scale); // 1008 px
    const pageH = Math.round(8.5 * 96 * scale); // 612 px

    // Style kanvas
    container.style.background = "#fff";
    container.style.border = "1px solid #808080";
    container.style.width = pageW + "px";
    container.style.height = pageH + "px";
    container.style.overflow = "auto";
    container.style.margin = "0 auto";
    container.style.boxShadow = "0 4px 8px rgba(0,0,0,0.1)";

    // Inisialisasi mxGraph
    const graph = new mxGraph(container);
    graph.setEnabled(false);
    const parent = graph.getDefaultParent();
    graph.getModel().beginUpdate();

    try {
        // Margin kertas (scaled)
        const mX = Math.round(20 * scale),
            mY = Math.round(20 * scale);

        // Outer border
        graph.insertVertex(
            parent,
            null,
            "",
            mX,
            mY,
            pageW - 2 * mX,
            pageH - 2 * mY,
            "strokeColor=#000;fillColor=none;strokeWidth=1;",
        );

        // === HEADER SECTION ===
        const headerY = Math.round((mY + 10) * scale);
        const headerH = Math.round(120 * scale);

        // Logo BPS (kiri)
        graph.insertVertex(
            parent,
            null,
            "",
            Math.round((mX + 30) * scale),
            Math.round((headerY + 15) * scale),
            Math.round(80 * scale),
            Math.round(90 * scale),
            "shape=image;image=/img/Logo-BPS.png;",
        );

        // Judul organisasi (tengah)
        graph.insertVertex(
            parent,
            null,
            "BADAN PUSAT STATISTIK",
            Math.round((mX + 130) * scale),
            Math.round((headerY + 20) * scale),
            Math.round(400 * scale),
            Math.round(80 * scale),
            `fontSize=${Math.round(18 * scale)};fontStyle=1;align=center;verticalAlign=middle;strokeColor=none;fillColor=none;fontColor=#000;html=1;`,
        );

        // Tabel informasi SOP (kanan)
        const tableX = Math.round((mX + 550) * scale);
        const tableY = Math.round((headerY + 10) * scale);
        const tableW = Math.round(550 * scale);
        const tableH = Math.round(100 * scale);

        // Header tabel dengan border
        const cellH = Math.round(20 * scale);
        const labelW = Math.round(160 * scale);
        const valueW = tableW - labelW;

        const tableData = [
            ["Nomor SOP", data.nomor_sop || "-"],
            ["Tanggal Pembuatan", data.tanggal_pembuatan || "-"],
            ["Tanggal Revisi", data.tanggal_revisi || "-"],
            ["Tanggal Efektif", data.tanggal_efektif || "-"],
            ["Disahkan oleh", data.disahkan_oleh || "-"],
            ["Nama SOP", data.nama_sop || "-"],
        ];

        // Buat tabel dengan border
        let currentY = tableY;
        tableData.forEach(([label, value], index) => {
            const isNameSOP = label === "Nama SOP";
            const currentCellH = isNameSOP ? cellH * 2 : cellH;

            // Cell label
            graph.insertVertex(
                parent,
                null,
                label,
                tableX,
                currentY,
                labelW,
                currentCellH,
                `strokeColor=#000;fillColor=#ffffff;fontSize=${Math.round(12 * scale)};fontStyle=1;align=center;verticalAlign=middle;fontColor=#000000;`,
            );

            // Cell value
            graph.insertVertex(
                parent,
                null,
                value,
                tableX + labelW,
                currentY,
                valueW,
                currentCellH,
                `strokeColor=#000;fillColor=#ffffff;fontSize=${Math.round(12 * scale)};align=center;verticalAlign=middle;fontColor=#000000;${isNameSOP ? "fontStyle=1;" : ""}html=1;`,
            );
            currentY += currentCellH;
        });

        // === CONTENT SECTIONS ===
        let contentY = Math.round((headerY + headerH + 50) * scale);
        const contentAreaW = Math.round((pageW - 2 * mX - 20) * scale);
        const leftColW = Math.floor(contentAreaW * 0.52);
        const rightColW = contentAreaW - leftColW - Math.round(20 * scale);
        const leftColX = Math.round((mX + 20) * scale);
        const rightColX = leftColX + leftColW + Math.round(20 * scale);

        // Section styling
        const sectionStyle = `strokeColor=#000;fillColor=none;fontSize=${Math.round(11 * scale)};align=left;verticalAlign=top;fontColor=#000;html=1;whiteSpace=wrap;`;
        const titleStyle = `strokeColor=none;fillColor=none;fontSize=${Math.round(12 * scale)};fontStyle=1;align=left;verticalAlign=top;fontColor=#000;`;

        // Dasar Hukum (kiri atas)
        graph.insertVertex(
            parent,
            null,
            "Dasar Hukum:",
            leftColX,
            contentY,
            leftColW,
            Math.round(18 * scale),
            titleStyle,
        );

        let dasarHukumText = (data.dasarHukum || ["-"])
            .map((v, i) => `${i + 1}. ${v}`)
            .join("\n");

        graph.insertVertex(
            parent,
            null,
            dasarHukumText,
            leftColX,
            contentY + Math.round(18 * scale),
            leftColW,
            Math.round(85 * scale),
            sectionStyle,
        );

        // Kualifikasi Pelaksanaan (kanan atas)
        graph.insertVertex(
            parent,
            null,
            "Kualifikasi Pelaksanaan:",
            rightColX,
            contentY,
            rightColW,
            Math.round(18 * scale),
            titleStyle,
        );

        let kualifikasiText = (data.kualifikasi || ["-"])
            .map((v, i) => `${i + 1}. ${v}`)
            .join("\n");

        graph.insertVertex(
            parent,
            null,
            kualifikasiText,
            rightColX,
            contentY + Math.round(18 * scale),
            rightColW,
            Math.round(85 * scale),
            sectionStyle,
        );

        // Keterkaitan (kiri tengah)
        contentY += Math.round(110 * scale);
        graph.insertVertex(
            parent,
            null,
            "Keterkaitan:",
            leftColX,
            contentY,
            leftColW,
            Math.round(18 * scale),
            titleStyle,
        );

        let keterkaitanText = (data.keterkaitan || ["-"])
            .map((v, i) => `${i + 1}. ${v}`)
            .join("\n");

        graph.insertVertex(
            parent,
            null,
            keterkaitanText,
            leftColX,
            contentY + Math.round(18 * scale),
            leftColW,
            Math.round(85 * scale),
            sectionStyle,
        );

        // Peralatan/Perlengkapan (kanan tengah)
        graph.insertVertex(
            parent,
            null,
            "Peralatan/Perlengkapan:",
            rightColX,
            contentY,
            rightColW,
            Math.round(18 * scale),
            titleStyle,
        );

        let peralatanText = (data.peralatan || ["-"])
            .map((v, i) => `${i + 1}. ${v}`)
            .join("\n");

        graph.insertVertex(
            parent,
            null,
            peralatanText,
            rightColX,
            contentY + Math.round(18 * scale),
            rightColW,
            Math.round(85 * scale),
            sectionStyle,
        );

        // Peringatan (full width)
        contentY += Math.round(110 * scale);
        graph.insertVertex(
            parent,
            null,
            "Peringatan:",
            leftColX,
            contentY,
            contentAreaW,
            Math.round(18 * scale),
            titleStyle,
        );

        let peringatanText = (data.peringatan || ["-"]).join("\n");

        graph.insertVertex(
            parent,
            null,
            peringatanText,
            leftColX,
            contentY + Math.round(18 * scale),
            contentAreaW,
            Math.round(45 * scale),
            sectionStyle,
        );

        // Pencatatan dan Pendataan (full width)
        contentY += Math.round(70 * scale);
        graph.insertVertex(
            parent,
            null,
            "Pencatatan dan Pendataan:",
            leftColX,
            contentY,
            contentAreaW,
            Math.round(18 * scale),
            titleStyle,
        );

        let pencatatanText = (data.pencatatan || ["-"]).join("\n");

        graph.insertVertex(
            parent,
            null,
            pencatatanText,
            leftColX,
            contentY + Math.round(18 * scale),
            contentAreaW,
            Math.round(35 * scale),
            sectionStyle,
        );
    } finally {
        graph.getModel().endUpdate();
    }
}

// Ekspor fungsi yang dipakai HTML
document.addEventListener("DOMContentLoaded", () => {
    window.addField = addField;
    window.removeLastField = removeLastField;
});
window.generateCoverMxGraph = generateCoverMxGraph;
