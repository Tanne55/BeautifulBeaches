import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/guest.css',
                'resources/css/auth.css',
                'resources/css/breadcrumb.css',
                'resources/css/calendar.css',
                'resources/js/explore.js',
                'resources/js/guest.js',
                'resources/js/auth.js',
                'resources/js/tour.js',
                'resources/js/booking-calendar.js',
                'resources/js/tourdetail.js',
                'resources/js/detail.js',
                'resources/js/contact.js',
                'resources/js/queries.js',
                'resources/js/welcome.js',
                'resources/js/about.js',
                'resources/js/gallery.js',
            ],
            refresh: true,
        }),
    ],
});
