import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],

    server: {
        // Menjamin Vite mendengarkan di semua antarmuka jaringan
        host: '192.168.1.88',
        // Opsional: Tentukan port jika Anda membutuhkannya (defaultnya 5173)
        clientPort: 5173,
    },
});
