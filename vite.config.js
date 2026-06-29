import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
    build: {
        outDir: 'public',
        emptyOutDir: false,
        cssCodeSplit: false,
        minify: true,
        rollupOptions: {
            input: path.resolve(__dirname, 'resources/js/app.js'),
            output: {
                entryFileNames: 'js/app.min.js',
                assetFileNames: (info) => {
                    if (info.names?.some((name) => name.endsWith('.css')) || info.name?.endsWith('.css')) {
                        return 'css/app.min.css';
                    }

                    return 'assets/[name][extname]';
                },
            },
        },
    },
});
