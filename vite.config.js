import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    // server: {
    //     host: true, // agar bisa diakses dari luar
    //     port: 5173,
    //     origin: "https://fe.azkass.my.id",
    //     allowedHosts: ["fe.azkass.my.id"],
    // },
});
