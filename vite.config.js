import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            output: 'public/build', // Đường dẫn đến thư mục output cho tệp manifest
            refresh: true,
        }),
    ],
});
