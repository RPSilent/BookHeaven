import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import compression from "vite-plugin-compression";

// https://vite.dev/config/
export default defineConfig(({ mode }) => ({
  plugins: [
    react({
      jsxImportSource: "react",
      // Mejora hot reload en desarrollo
      fastRefresh: true,
      // Silenciar avisos de deoptimización para archivos grandes (+500KB)
      babel: {
        compact: true,
      },
    }),
    // OPTIMIZACIÓN: Compresión solo en desarrollo (Vercel ya comprime en producción)
    ...(mode !== "production"
      ? [
          compression({
            verbose: false,
            disable: false,
            threshold: 5120,
            algorithm: "gzip",
            ext: ".gz",
          }),
          compression({
            verbose: false,
            disable: false,
            threshold: 5120,
            algorithm: "brotli",
            ext: ".br",
          }),
        ]
      : []),
  ],
  server: {
    host: true, // Permite acceso desde otros dispositivos en la misma red (como el celular)
    // Optimizaciones para desarrollo local
    middlewareMode: false,
    hmr: {
      protocol: "ws",
      host: "localhost",
      port: 5173,
    },
    proxy: {
      "/api": {
        target: "http://localhost:8000",
        changeOrigin: true,
        secure: false,
        rewrite: (path) => path,
      },
      "/storage": {
        target: "http://localhost:8000",
        changeOrigin: true,
        secure: false,
        rewrite: (path) => path,
      },
    },
  },
  build: {
    // OPTIMIZACIÓN: Estrategia de construcción mejorada
    target: "es2020",
    minify: "terser",
    terserOptions: {
      compress: {
        drop_console: true,
        drop_debugger: true,
        passes: 2, // Múltiples pasadas de compresión
      },
      mangle: true,
      format: {
        comments: false,
      },
    },
    cssCodeSplit: true,
    reportCompressedSize: false,
    chunkSizeWarningLimit: 500, // Reducido para advertencias de chunks grandes
    rollupOptions: {
      output: {
        // OPTIMIZACIÓN: Estrategia simplificada de división de chunks
        manualChunks(id) {
          // Agrupar node_modules en vendor chunks
          if (id.includes("node_modules")) {
            if (id.includes("react") || id.includes("react-dom")) {
              return "vendor";
            }
            if (id.includes("react-router")) {
              return "router";
            }
            if (id.includes("recharts") || id.includes("axios")) {
              return "ui";
            }
            return "vendor";
          }
        },
        // Nombrado determinístico para mejor caching (hash basado en contenido)
        entryFileNames: "js/[name]-[hash].js",
        chunkFileNames: "js/[name]-[hash].js",
        assetFileNames: (assetInfo) => {
          const info = assetInfo.name.split(".");
          const ext = info[info.length - 1];
          if (/png|jpe?g|gif|svg|webp/.test(ext)) {
            return "images/[name]-[hash][extname]";
          } else if (/woff|woff2|eot|ttf|otf/.test(ext)) {
            return "fonts/[name]-[hash][extname]";
          } else if (ext === "css") {
            return "css/[name]-[hash][extname]";
          }
          return "assets/[name]-[hash][extname]";
        },
      },
    },
    // No generar sourcemaps en producción (reducir tamaño)
    sourcemap: false,
  },
  optimizeDeps: {
    // OPTIMIZACIÓN: Pre-bundle de dependencias pesadas
    include: ["react", "react-dom", "react-router-dom", "axios", "recharts"],
    exclude: [
      "react-dom/server",
      // Excluir módulos grandes que se cargan bajo demanda
    ],
    // Aplicar esbuild optimizations
    esbuildOptions: {
      target: "es2020",
      keepNames: false, // Reducir tamaño removiendo nombres de funciones innecesarios
    },
  },
  // OPTIMIZACIÓN: Configuración de caché
  cacheDir: ".vite",
}));
