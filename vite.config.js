import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

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
                "resources/js/printSOP.js",
            ],
            refresh: true,
        }),
    ],
});
