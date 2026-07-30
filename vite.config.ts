import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import svgLoader from 'vite-svg-loader';
import path from 'path';

// @ts-ignore
export default defineConfig({
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost',
        },
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        // Создает отдельный чанк для каждой библиотеки в node_modules
                        return id.toString().split('node_modules/')[1].split('/')[0].toString();
                    }
                },
            },
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: (content, filePath) => {
                    // Фильтруем файлы именно в этой папке
                    if (filePath.includes('packages/webx')) {
                        return `@import "@/styles/_inject.scss"; ${content}`;
                    }
                    return content;
                },
                quietDeps: true,
                silenceDeprecations: ['import', 'color-functions', 'global-builtin', 'if-function'],
                //api: 'modern-compiler',
            },
        },
    },
    plugins: [
        laravel({
            input: [
                'packages/webx/main.ts',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),

        svgLoader(),
    ],

    resolve: {
        alias: {
            '@': path.resolve(__dirname, './packages/webx'),
        },
    },
});
