import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/weight-loss.js', 'resources/js/conditions.js',
                'resources/js/services.js', 'resources/js/branch-map.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
