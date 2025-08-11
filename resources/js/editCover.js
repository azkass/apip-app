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
// Sections where adding new inputs is disabled
const noAddSections = ["kualifikasi", "peringatan", "pencatatan"];

// Tambah input baru pada section
function addField(section) {
    if (noAddSections.includes(section)) return; // Disable add for specific sections
    const list = document.getElementById(section + "List");
    if (!list) return;
    const idx = list.children.length;
    const div = document.createElement("div");
    div.className = "flex items-center";
    div.innerHTML = `<input type="text" name="${section}[]" autocomplete="off" class="form-input flex-1 p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 autocomplete='off'" placeholder="${sections[section]} ke-${idx + 1}" />`;
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
        } else if (
            window.coverData &&
            typeof window.coverData[section] === "string" &&
            window.coverData[section].length > 0
        ) {
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
            div.className = "flex items-center";
            // Escape value agar aman
            const safeVal = (val ?? "").toString().replace(/"/g, "&quot;");
            div.innerHTML = `<input type="text" name="${section}[]" autocomplete="off" class="form-input mb-2 flex-1 p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200" value="${safeVal}" placeholder="${sections[section]} ke-${idx + 1}" />`;
            list.appendChild(div);
        });
        updateRemoveButton(section);
    }
    // Cache form dan section element
    const form = document.getElementById("editCoverForm");
    if (!form) {
        // Form tidak ada di halaman ini, keluar tanpa error
        return;
    }

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
            )
                .map((i) => i.value.trim())
                .filter((val) => val !== "");
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
    console.log("data cover:", data);
    const container = document.getElementById("coverContainer");
    container.innerHTML = "";

    // Legal landscape: 14" x 8.5" (355.6 mm x 215.9 mm) - scaled down 75%
    const scale = 1;
    const pageW = Math.round(14.8 * 96 * scale); // 1344 px
    const pageH = Math.round(8.5 * 96 * scale); // 612 px

    // Style kanvas
    container.style.background = "#fff";
    container.style.border = "1px solid #808080";
    container.style.width = pageW + "px";
    container.style.height = pageH + "px";
    container.style.boxSizing = "border-box";

    // Inisialisasi mxGraph
    const graph = new mxGraph(container);
    graph.setEnabled(false);

    // Konfigurasi default style untuk text wrapping
    graph.setHtmlLabels(true); // Enable HTML labels
    const defaultStyle = graph.getStylesheet().getDefaultVertexStyle();
    defaultStyle[mxConstants.STYLE_WHITE_SPACE] = "wrap";
    defaultStyle[mxConstants.STYLE_OVERFLOW] = "hidden";
    defaultStyle[mxConstants.STYLE_WORD_WRAP] = "break-word";
    defaultStyle[mxConstants.STYLE_SPACING_LEFT] = 3;
    defaultStyle[mxConstants.STYLE_FONTFAMILY] = "Arial";

    // Pastikan cell dapat menyesuaikan tinggi dengan konten
    graph.cellsResizable = false;
    graph.extendParents = true;
    graph.extendParentsOnAdd = true;
    graph.constrainChildren = false;
    graph.autoSizeCells = true;

    const parent = graph.getDefaultParent();
    graph.getModel().beginUpdate();

    try {
        // Margin kertas (scaled)
        const mX = Math.round(35 * scale);
        const mY = Math.round(35 * scale);

        // === HEADER SECTION ===
        const headerY = Math.round(mY * scale);
        const headerH = Math.round(280 * scale);
        const rightSectionX = Math.round(pageW / 2);
        const widthSection = Math.round((pageW - 2 * mX) / 2);
        const leftWidthSection = Math.round(widthSection - 20);
        const fontSize = Math.round(14 * scale);

        // Base styles dengan text wrapping yang tepat
        const baseStyle = `strokeColor=#000;fillColor=none;html=1;whiteSpace=wrap;fontSize=${fontSize};fontColor=#000000;`;
        const titleStyle =
            baseStyle + `align=left;verticalAlign=top;fontStyle=1;`;
        const sectionStyle = baseStyle + `align=left;verticalAlign=top;`;
        const cellStyle =
            baseStyle + `fillColor=#ffffff;align=left;verticalAlign=middle;`;

        // Header border
        graph.insertVertex(
            parent,
            null,
            "",
            mX,
            mY,
            widthSection,
            headerH,
            "strokeColor=#000;fillColor=none;strokeWidth=1;",
        );

        // Logo BPS (kiri)
        graph.insertVertex(
            parent,
            null,
            "",
            Math.round(leftWidthSection / 2 - 30),
            Math.round((headerY + 30) * scale),
            Math.round(170 * scale),
            Math.round(170 * scale),
            "shape=image;image=/img/Logo-BPS.png;",
        );

        // Text BPS & Inspektorat
        graph.insertVertex(
            parent,
            null,
            "BADAN PUSAT STATISTIK",
            Math.round((mX + 130) * scale),
            Math.round((headerY + 180) * scale),
            Math.round(400 * scale),
            Math.round(80 * scale),
            `fontFamily=Arial;fontSize=${Math.round(26 * scale)};fontStyle=3;align=center;verticalAlign=middle;strokeColor=none;fillColor=none;fontColor=#000;html=1;whiteSpace=wrap;`,
        );

        graph.insertVertex(
            parent,
            null,
            "INSPEKTORAT UTAMA",
            Math.round((mX + 130) * scale),
            Math.round((headerY + 210) * scale),
            Math.round(400 * scale),
            Math.round(80 * scale),
            `fontSize=${Math.round(22 * scale)};fontStyle=1;align=center;verticalAlign=middle;strokeColor=none;fillColor=none;fontColor=#000;html=1;whiteSpace=wrap;`,
        );

        // Header tabel dengan border
        const cellH = Math.round(20 * scale);
        const labelW = Math.round(160 * scale);
        const valueW = widthSection - labelW;

        // Siapkan konten "Disahkan oleh" beserta NIP & Jabatan
        const disahkanLines = [];
        // Jabatan di baris pertama
        if (data.disahkan_oleh_jabatan)
            disahkanLines.push(data.disahkan_oleh_jabatan);

        // Sisipkan satu baris kosong (spasi) bila ada nama / NIP
        if (
            data.disahkan_oleh_jabatan &&
            (data.disahkan_oleh || data.disahkan_oleh_nip)
        ) {
            disahkanLines.push("");
            disahkanLines.push("");
            disahkanLines.push("");
            disahkanLines.push("");
            disahkanLines.push("");
        }

        // Nama & NIP berada di bawah
        if (data.disahkan_oleh) disahkanLines.push(data.disahkan_oleh);
        if (data.disahkan_oleh_nip)
            disahkanLines.push(`NIP ${data.disahkan_oleh_nip}`);
        const disahkanValue = disahkanLines.length
            ? disahkanLines.join("\n")
            : "-";

        // Fungsi untuk format tanggal Indonesia
        function formatTanggalIndonesia(tanggal) {
            if (!tanggal) return "-";

            const bulanIndonesia = [
                "",
                "Januari",
                "Februari",
                "Maret",
                "April",
                "Mei",
                "Juni",
                "Juli",
                "Agustus",
                "September",
                "Oktober",
                "November",
                "Desember",
            ];

            const date = new Date(tanggal);
            const hari = date.getDate();
            const bulan = bulanIndonesia[date.getMonth() + 1];
            const tahun = date.getFullYear();

            return `${hari} ${bulan} ${tahun}`;
        }

        const tableData = [
            ["Nomor SOP", data.nomor_sop || "-"],
            [
                "Tanggal Pembuatan",
                formatTanggalIndonesia(data.tanggal_pembuatan),
            ],
            ["Tanggal Revisi", formatTanggalIndonesia(data.tanggal_revisi)],
            ["Tanggal Efektif", formatTanggalIndonesia(data.tanggal_efektif)],
            ["Disahkan oleh", disahkanValue],
            ["Nama SOP", data.nama_sop || "-"],
        ];

        // Buat tabel dengan border dan tinggi yang dapat menyesuaikan
        let currentY = mY;
        tableData.forEach(([label, value], index) => {
            // Special cases for cell height adjustments
            const isDisahkanOleh = label === "Disahkan oleh";
            const isNamaSOP = label === "Nama SOP";
            // Tinggi minimum untuk cell, akan menyesuaikan jika konten lebih panjang
            const minCellH = isDisahkanOleh
                ? cellH * 7.5
                : isNamaSOP
                  ? cellH * 2.5
                  : cellH;

            // Cell label
            const labelCell = graph.insertVertex(
                parent,
                null,
                label,
                rightSectionX,
                currentY,
                labelW,
                minCellH,
                cellStyle + "fontStyle=1;",
            );

            // Cell value dengan auto-sizing
            const valueCell = graph.insertVertex(
                parent,
                null,
                value,
                rightSectionX + labelW,
                currentY,
                valueW,
                minCellH,
                cellStyle + (isDisahkanOleh ? "align=center;" : ""),
            );

            // Update currentY berdasarkan tinggi cell yang sebenarnya
            const actualHeight = Math.max(
                labelCell.getGeometry().height,
                valueCell.getGeometry().height,
                minCellH,
            );
            currentY += actualHeight;
        });

        // === CONTENT SECTIONS ===
        let contentY = Math.round(headerY + headerH + 30);

        // Dasar Hukum dengan tinggi yang dapat menyesuaikan
        const dasarHukumTitle = graph.insertVertex(
            parent,
            null,
            "Dasar Hukum:",
            mX,
            contentY,
            leftWidthSection,
            Math.round(18 * scale), // Tinggi minimum untuk judul
            titleStyle,
        );

        let dasarHukumArr = Array.isArray(data.dasarHukum)
            ? data.dasarHukum.filter((v) => v && v.trim() !== "")
            : [];
        let dasarHukumText = (dasarHukumArr.length > 0 ? dasarHukumArr : ["-"])
            .map((v, i) => `${i + 1}. ${v}`)
            .join("\n");

        const dasarHukumContent = graph.insertVertex(
            parent,
            null,
            dasarHukumText,
            mX,
            contentY + Math.round(18 * scale),
            leftWidthSection,
            Math.round(85 * scale), // Tinggi minimum untuk konten
            sectionStyle,
        );

        // Kualifikasi Pelaksanaan (kanan atas)
        graph.insertVertex(
            parent,
            null,
            "Kualifikasi Pelaksanaan:",
            rightSectionX,
            contentY,
            widthSection,
            Math.round(18 * scale),
            titleStyle,
        );

        let kualifikasiArr = Array.isArray(data.kualifikasi)
            ? data.kualifikasi.filter((v) => v && v.trim() !== "")
            : [];
        let kualifikasiText = (
            kualifikasiArr.length > 0 ? kualifikasiArr : ["-"]
        )
            .map((v, i) => `${i + 1}. ${v}`)
            .join("\n");

        graph.insertVertex(
            parent,
            null,
            kualifikasiText,
            rightSectionX,
            contentY + Math.round(18 * scale),
            widthSection,
            Math.round(85 * scale),
            sectionStyle,
        );

        // Keterkaitan (kiri tengah)
        contentY += Math.round(125 * scale);
        graph.insertVertex(
            parent,
            null,
            "Keterkaitan:",
            mX,
            contentY,
            leftWidthSection,
            Math.round(18 * scale),
            titleStyle,
        );

        let keterkaitanArr = Array.isArray(data.keterkaitan)
            ? data.keterkaitan.filter((v) => v && v.trim() !== "")
            : [];
        let keterkaitanText = (
            keterkaitanArr.length > 0 ? keterkaitanArr : ["-"]
        )
            .map((v, i) => `${i + 1}. ${v}`)
            .join("\n");

        graph.insertVertex(
            parent,
            null,
            keterkaitanText,
            mX,
            contentY + Math.round(18 * scale),
            leftWidthSection,
            Math.round(85 * scale),
            sectionStyle,
        );

        // Peralatan/Perlengkapan (kanan tengah)
        graph.insertVertex(
            parent,
            null,
            "Peralatan/Perlengkapan:",
            rightSectionX,
            contentY,
            widthSection,
            Math.round(18 * scale),
            titleStyle,
        );

        let peralatanArr = Array.isArray(data.peralatan)
            ? data.peralatan.filter((v) => v && v.trim() !== "")
            : [];
        let peralatanText = (peralatanArr.length > 0 ? peralatanArr : ["-"])
            .map((v, i) => `${i + 1}. ${v}`)
            .join("\n");

        graph.insertVertex(
            parent,
            null,
            peralatanText,
            rightSectionX,
            contentY + Math.round(18 * scale),
            widthSection,
            Math.round(85 * scale),
            sectionStyle,
        );

        // Peringatan
        contentY += Math.round(125 * scale);
        graph.insertVertex(
            parent,
            null,
            "Peringatan:",
            mX,
            contentY,
            leftWidthSection,
            Math.round(18 * scale),
            titleStyle,
        );

        let peringatanArr = Array.isArray(data.peringatan)
            ? data.peringatan.filter((v) => v && v.trim() !== "")
            : [];
        let peringatanText = (
            peringatanArr.length > 0 ? peringatanArr : ["-"]
        ).join("\n");

        graph.insertVertex(
            parent,
            null,
            peringatanText,
            mX,
            contentY + Math.round(18 * scale),
            leftWidthSection,
            Math.round(45 * scale),
            sectionStyle,
        );

        // Pencatatan dan Pendataan
        graph.insertVertex(
            parent,
            null,
            "Pencatatan dan Pendataan:",
            rightSectionX,
            contentY,
            widthSection,
            Math.round(18 * scale),
            titleStyle,
        );

        let pencatatanArr = Array.isArray(data.pencatatan)
            ? data.pencatatan.filter((v) => v && v.trim() !== "")
            : [];
        let pencatatanText = (
            pencatatanArr.length > 0 ? pencatatanArr : ["-"]
        ).join("\n");

        graph.insertVertex(
            parent,
            null,
            pencatatanText,
            rightSectionX,
            contentY + Math.round(18 * scale),
            widthSection,
            Math.round(45 * scale),
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
