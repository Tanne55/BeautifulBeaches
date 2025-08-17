import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/calendar.css',
                'resources/js/guest.js',
                'resources/js/auth.js',
                'resources/js/booking-calendar.js',
                'resources/js/tourdetail.js'
            ],
            refresh: true,
        }),
    ],
});
