<?php
/**
 * Script manual para crear enlace simbólico de storage
 * Usa symlink() directamente sin necesidad de exec()
 * 
 * INSTRUCCIONES:
 * 1. Sube este archivo a public_html/api/
 * 2. Visita: https://digitalcoreapp.com/api/create-storage-link-manual.php
 * 3. ELIMINA este archivo después de usarlo
 */

// Seguridad básica - cambiar este password
define('ADMIN_PASSWORD', 'bookheaven2026');

$password = $_GET['password'] ?? '';
if ($password !== ADMIN_PASSWORD) {
    die('❌ Password incorrecto. Agrega ?password=bookheaven2026 a la URL');
}

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Crear Storage Link - BookHeaven</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: 20px 0; }
        .success { color: #28a745; font-size: 18px; font-weight: bold; }
        .error { color: #dc3545; font-size: 18px; font-weight: bold; }
        .warning { color: #ffc107; }
        .info { color: #17a2b8; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🔗 Crear Enlace Simbólico de Storage</h1>
";

// Rutas
$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';

echo "<div class='box'>";
echo "<h3>📁 Rutas detectadas:</h3>";
echo "<p><strong>Target (origen):</strong> <code>$target</code></p>";
echo "<p><strong>Link (destino):</strong> <code>$link</code></p>";
echo "</div>";

// Verificar que el directorio target existe
if (!is_dir($target)) {
    echo "<div class='box error'>";
    echo "❌ Error: El directorio storage/app/public no existe.<br>";
    echo "Verifica que subiste correctamente la carpeta storage/";
    echo "</div></body></html>";
    exit;
}

// Verificar si el link ya existe
if (file_exists($link)) {
    if (is_link($link)) {
        echo "<div class='box warning'>";
        echo "⚠️ El enlace simbólico ya existe.<br>";
        echo "Apunta a: <code>" . readlink($link) . "</code><br><br>";
        
        $action = $_GET['action'] ?? '';
        if ($action === 'recreate') {
            // Eliminar el link existente
            if (unlink($link)) {
                echo "<p class='info'>🗑️ Enlace anterior eliminado. Creando uno nuevo...</p>";
            } else {
                echo "<p class='error'>❌ No se pudo eliminar el enlace anterior.</p>";
                echo "</div></body></html>";
                exit;
            }
        } else {
            echo "<p>Si quieres recrearlo, <a href='?password=$password&action=recreate'>haz clic aquí</a></p>";
            echo "</div></body></html>";
            exit;
        }
    } else {
        echo "<div class='box error'>";
        echo "❌ Error: Existe un archivo/carpeta llamado 'storage' en public/<br>";
        echo "Debes eliminarlo manualmente antes de crear el enlace simbólico.";
        echo "</div></body></html>";
        exit;
    }
}

// Intentar crear el enlace simbólico
echo "<div class='box'>";
echo "<h3>🔄 Creando enlace simbólico...</h3>";

if (symlink($target, $link)) {
    echo "<p class='success'>✅ ¡Enlace simbólico creado exitosamente!</p>";
    echo "<p><strong>Storage está ahora accesible en:</strong></p>";
    echo "<pre>https://digitalcoreapp.com/storage/</pre>";
    echo "<p class='info'>📸 Las imágenes y archivos subidos serán accesibles vía web.</p>";
} else {
    echo "<p class='error'>❌ No se pudo crear el enlace simbólico.</p>";
    echo "<p>Posibles causas:</p>";
    echo "<ul>";
    echo "<li>Permisos insuficientes</li>";
    echo "<li>La función symlink() está deshabilitada</li>";
    echo "<li>Restricciones del servidor</li>";
    echo "</ul>";
    echo "<p class='warning'><strong>Solución:</strong> Contacta al soporte de Hostinger y pídeles que ejecuten:</p>";
    echo "<pre>cd public_html/api\nphp artisan storage:link</pre>";
    echo "<p>O que creen manualmente el enlace simbólico desde SSH.</p>";
}

echo "</div>";

echo "<div class='box warning'>";
echo "<h3>⚠️ IMPORTANTE</h3>";
echo "<p><strong>Elimina este archivo inmediatamente por seguridad:</strong></p>";
echo "<p><code>create-storage-link-manual.php</code></p>";
echo "</div>";

echo "</body></html>";
