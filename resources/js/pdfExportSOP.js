import { jsPDF } from "jspdf";
import "svg2pdf.js";

window.setupPdfDownload = () => {
    const downloadBtn = document.getElementById("downloadPdfBtn");
    if (!downloadBtn) {
        console.error("Tombol download PDF tidak ditemukan.");
        return;
    }

    downloadBtn.addEventListener("click", async () => {
        try {
            const doc = new jsPDF({
                orientation: "landscape",
                unit: "pt",
                format: "legal",
            });

            const margin = 20;
            const pageW = doc.internal.pageSize.getWidth();
            const pageH = doc.internal.pageSize.getHeight();
            const usableW = pageW - margin * 2;

            let cursorY = margin;
            let svgFound = false;

            for (const sel of ["#coverContainer", "#graphContainer"]) {
                const svgEl = document.querySelector(`${sel} svg`);
                if (!svgEl) {
                    console.warn(`SVG tidak ditemukan pada selector ${sel}.`);
                    continue;
                }

                svgFound = true;

                // Paksa font default agar PDF tidak error
                svgEl.setAttribute("style", "font-family: Helvetica;");

                // Serialize & parse ulang untuk clean SVG
                const serializer = new XMLSerializer();
                const svgString = serializer.serializeToString(svgEl);
                const cleanSvg = new DOMParser().parseFromString(
                    svgString,
                    "image/svg+xml",
                ).documentElement;

                // Tunggu frame berikut agar rendering SVG tidak tergesa-gesa
                await new Promise((resolve) => requestAnimationFrame(resolve));

                const bbox = svgEl.getBBox();
                const scale = usableW / bbox.width;
                const scaledHeight = bbox.height * scale;

                // Jika tidak cukup ruang, buat halaman baru
                if (cursorY + scaledHeight > pageH - margin) {
                    doc.addPage();
                    cursorY = margin;
                }

                // Render SVG ke dokumen
                await doc.svg(cleanSvg, {
                    x: margin,
                    y: cursorY,
                    width: usableW,
                    height: scaledHeight,
                });

                cursorY += scaledHeight + margin;
            }

            if (!svgFound) {
                alert("Tidak ada diagram SVG yang ditemukan untuk diekspor.");
                return;
            }

            doc.save("diagram_sop.pdf");
        } catch (err) {
            console.error("Gagal mengekspor PDF:", err);
            alert("Terjadi kesalahan saat mengunduh PDF. Silakan cek console.");
        }
    });
};
