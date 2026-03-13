<?php
/**
 * Script de ayuda para configurar Laravel en Hostinger
 * 
 * INSTRUCCIONES:
 * 1. Sube este archivo a public_html/api/public/
 * 2. Visita: https://tudominio.com/api/hostinger-setup.php
 * 3. Ejecuta los comandos necesarios
 * 4. ELIMINA este archivo después de usarlo (seguridad)
 * 
 * IMPORTANTE: Este script debe eliminarse después del despliegue
 */

// Mostrar errores para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Seguridad básica - cambiar este password
define('ADMIN_PASSWORD', 'cambiar_este_password_123');

// Verificar password
$password = $_GET['password'] ?? '';
if ($password !== ADMIN_PASSWORD) {
    die('❌ Password incorrecto. Edita este archivo y cambia ADMIN_PASSWORD.');
}

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Hostinger Setup - BookHeaven</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        .command { background: #f4f4f4; padding: 15px; margin: 10px 0; border-left: 4px solid #4CAF50; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        button { background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; margin: 5px; }
        button:hover { background: #45a049; }
        h1 { color: #333; }
        .info { background: #e3f2fd; padding: 10px; border-left: 4px solid #2196F3; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>🚀 Hostinger Setup - BookHeaven</h1>
    <div class='warning'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de completar la configuración</div>
";

// Verificar que estamos en el directorio correcto
// Este script debe estar en public/, así que artisan está un nivel arriba
$artisan = __DIR__ . '/../artisan';
if (!file_exists($artisan)) {
    die("<div style='color:red; padding:20px;'>❌ Error: El archivo artisan no existe. Ruta buscada: " . realpath(__DIR__ . '/..') . "/artisan</div></body></html>");
}

$action = $_GET['action'] ?? '';

function runArtisanCommand($command, $description) {
    echo "<div class='command'>";
    echo "<strong>$description</strong><br>";
    echo "Comando: <code>php artisan $command</code><br><br>";
    
    try {
        // Cargar Laravel desde un nivel arriba (public/../)
        require __DIR__.'/../vendor/autoload.php';
        $app = require_once __DIR__.'/../bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        
        ob_start();
        $status = $kernel->call($command);
        $output = ob_get_clean();
        
        if ($status === 0) {
            echo "<span class='success'>✅ Éxito</span><br>";
        } else {
            echo "<span class='error'>❌ Error</span><br>";
        }
        
        if ($output) {
            echo "<pre>$output</pre>";
        }
        
    } catch (Exception $e) {
        echo "<span class='error'>❌ Excepción capturada:</span><br>";
        echo "<pre>";
        echo "Mensaje: " . $e->getMessage() . "\n";
        echo "Archivo: " . $e->getFile() . "\n";
        echo "Línea: " . $e->getLine() . "\n";
        echo "Trace:\n" . $e->getTraceAsString();
        echo "</pre>";
    }
    
    echo "</div>";
    return isset($status) && $status === 0;
}

// Mostrar información del sistema
if ($action === '' || $action === 'info') {
    echo "<h2>📊 Información del Sistema</h2>";
    echo "<div class='info'>";
    echo "<strong>PHP Version:</strong> " . phpversion() . "<br>";
    echo "<strong>Laravel Path:</strong> " . realpath(__DIR__ . '/..') . "<br>";
    echo "<strong>Script Path:</strong> " . __DIR__ . "<br>";
    echo "<strong>Archivo .env existe:</strong> " . (file_exists(__DIR__ . '/../.env') ? '✅ Sí' : '❌ No') . "<br>";
    
    if (file_exists(__DIR__ . '/../.env')) {
        $envContent = file_get_contents(__DIR__ . '/../.env');
        echo "<strong>APP_KEY configurada:</strong> " . (strpos($envContent, 'APP_KEY=base64:') !== false ? '✅ Sí' : '⚠️ No') . "<br>";
        echo "<strong>APP_ENV:</strong> " . (strpos($envContent, 'APP_ENV=production') !== false ? '✅ production' : '⚠️ No es production') . "<br>";
        
        // Verificar credenciales de BD
        preg_match('/DB_DATABASE=(.*)/', $envContent, $dbName);
        preg_match('/DB_USERNAME=(.*)/', $envContent, $dbUser);
        echo "<strong>DB_DATABASE:</strong> " . (isset($dbName[1]) ? trim($dbName[1]) : 'No configurado') . "<br>";
        echo "<strong>DB_USERNAME:</strong> " . (isset($dbUser[1]) ? trim($dbUser[1]) : 'No configurado') . "<br>";
    }
    
    echo "<strong>Vendor existe:</strong> " . (is_dir(__DIR__ . '/../vendor') ? '✅ Sí' : '❌ No') . "<br>";
    echo "<strong>Bootstrap existe:</strong> " . (is_dir(__DIR__ . '/../bootstrap') ? '✅ Sí' : '❌ No') . "<br>";
    echo "<strong>Storage writable:</strong> " . (is_writable(__DIR__ . '/../storage') ? '✅ Sí' : '❌ No') . "<br>";
    echo "<strong>Bootstrap/cache writable:</strong> " . (is_writable(__DIR__ . '/../bootstrap/cache') ? '✅ Sí' : '❌ No') . "<br>";
    
    // Extensiones PHP requeridas
    echo "<br><strong>Extensiones PHP:</strong><br>";
    $extensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json'];
    foreach ($extensions as $ext) {
        echo "- $ext: " . (extension_loaded($ext) ? '✅' : '❌') . "<br>";
    }
    echo "</div>";
}

// Menú de acciones
echo "<h2>🛠️ Acciones Disponibles</h2>";

if ($action === 'migrate') {
    runArtisanCommand('migrate --force', '🗄️ Ejecutar Migraciones');
} elseif ($action === 'seed') {
    runArtisanCommand('db:seed --force', '🌱 Ejecutar Seeders');
} elseif ($action === 'optimize') {
    echo "<h3>⚡ Optimizando Laravel...</h3>";
    runArtisanCommand('config:cache', '📦 Cachear configuración');
    runArtisanCommand('route:cache', '🛣️ Cachear rutas');
    runArtisanCommand('view:cache', '👁️ Cachear vistas');
} elseif ($action === 'clear') {
    echo "<h3>🧹 Limpiando cachés...</h3>";
    runArtisanCommand('config:clear', '📦 Limpiar configuración');
    runArtisanCommand('route:clear', '🛣️ Limpiar rutas');
    runArtisanCommand('view:clear', '👁️ Limpiar vistas');
    runArtisanCommand('cache:clear', '💾 Limpiar caché general');
}

// Botones
echo "<div style='margin-top: 30px;'>";
echo "<a href='?password=$password&action=info'><button>📊 Info del Sistema</button></a>";
echo "<a href='?password=$password&action=migrate'><button>🗄️ Migrar BD</button></a>";
echo "<a href='?password=$password&action=seed'><button>🌱 Seeders</button></a>";
echo "<a href='?password=$password&action=optimize'><button>⚡ Optimizar</button></a>";
echo "<a href='?password=$password&action=clear'><button>🧹 Limpiar Cache</button></a>";
echo "</div>";

echo "
<div style='margin-top: 50px; padding: 20px; background: #fff3cd; border-left: 4px solid #f0ad4e;'>
    <h3>⚠️ Recordatorio de Seguridad</h3>
    <p><strong>Este archivo debe eliminarse después de completar la configuración.</strong></p>
    <p>Elimina <code>hostinger-setup.php</code> de <code>public_html/api/public/</code> usando el File Manager de cPanel.</p>
</div>

</body>
</html>
";
