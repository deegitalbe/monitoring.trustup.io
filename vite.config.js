import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import * as fs from 'fs';

export default defineConfig({
    plugins: [
        laravel([
            'resources/css/app.css',
            'resources/js/app.js',
        ]),
    ],

    // server: {
    //     https: {
    //         key: fs.readFileSync('/Users/romainvause/.config/valet/Certificates/my.trustup.pro.test.key'),
    //         cert: fs.readFileSync('/Users/romainvause/.config/valet/Certificates/my.trustup.pro.test.crt'),
    //     },
    //     host: '0.0.0.0',
    //     hmr: {
    //         host: 'monitoring.trustup.io.teset',
    //     }
    // },
});
