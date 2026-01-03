import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        VitePWA({
            registerType: 'autoUpdate',
            outDir: 'public',
            buildBase: '/',
            scope: '/',
            workbox: {
                globPatterns: ['**/*.{js,css,html,svg,png,ico,txt,woff2}'],
                cleanupOutdatedCaches: true,
                directoryIndex: 'index.php',
                navigateFallback: null,
            },
            manifest: {
                name: 'FastingMate',
                short_name: 'FastingMate',
                description: 'Lunasi Hutang Puasa dengan Tenang',
                theme_color: '#0ca678',
                background_color: '#ffffff',
                display: 'standalone',
                orientation: 'portrait',
                icons: [
                    {
                        src: '/pwa-192x192.png',
                        sizes: '192x192',
                        type: 'image/png',
                    },
                    {
                        src: '/pwa-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                    },
                    {
                        src: '/pwa-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'any maskable'
                    }
                ],
            },
        }),
    ],
});
