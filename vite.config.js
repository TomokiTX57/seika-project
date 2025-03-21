import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    server: {
        host: "0.0.0.0", // ← Docker コンテナが外部からアクセスされるために必要
        port: 5173,
        strictPort: true, // ポートを固定
        hmr: {
            host: "localhost", // または ホストOSのIPアドレス（後述）
            protocol: "ws",
        },
    },
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
});
