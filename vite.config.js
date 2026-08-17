import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/map.js'],
            refresh: true,
        }),
    ],
    optimizeDeps: {
        include: ['leaflet'],
    },
    server: {
        host: '127.0.0.1',
        port: 5173,
        strictPort: true,
    },
});
