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
    server: {
        host: 'localhost', // or 'laravel_sppd.test' if using Laravel Herd's domain
        port: 5173, // Change the port if needed
        https: false, // Ensure Vite runs with HTTPS if using a `.test` domain
    },
});
