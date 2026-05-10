import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/games/flexbox-froggy.js', 'resources/js/GameEngine.js', 'resources/js/games/grid-garden.js', 'resources/js/games/css-selector.js', 'resources/js/games/html-builder.js', 'resources/js/games/js-quest.js'],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
