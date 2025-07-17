import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import jsPDF from "jspdf";
import "svg2pdf.js";

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/graph.js",
                "resources/js/editCover.js",
                "resources/js/editBody.js",
                "resources/js/pdfExportSOP.js",
            ],
            refresh: true,
        }),
    ],
});
