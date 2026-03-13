<?php
/**
 * Script seguro para migraciones en Hostinger
 * Evita el uso de exec() que está deshabilitada
 * 
 * INSTRUCCIONES:
 * 1. Sube este archivo a public_html/api/public/
 * 2. Visita: https://tudominio.com/api/hostinger-migrate-safe.php?password=XXXX
 * 3. ELIMINA este archivo después de usarlo
 */

// Mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Seguridad
define('ADMIN_PASSWORD', 'cambiar_este_password_123');
$password = $_GET['password'] ?? '';
if ($password !== ADMIN_PASSWORD) {
    die('❌ Password incorrecto.');
}

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Hostinger Migrate Safe - BookHeaven</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 50px auto; padding: 20px; }
        .success { color: green; background: #e8f5e9; padding: 15px; margin: 10px 0; border-left: 4px solid green; }
        .error { color: red; background: #ffebee; padding: 15px; margin: 10px 0; border-left: 4px solid red; }
        .warning { color: orange; background: #fff3e0; padding: 15px; margin: 10px 0; border-left: 4px solid orange; }
        .info { background: #e3f2fd; padding: 15px; margin: 10px 0; border-left: 4px solid #2196F3; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
        h1 { color: #333; }
        button { background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; margin: 5px; border-radius: 4px; }
        button:hover { background: #45a049; }
    </style>
</head>
<body>
    <h1>🚀 Migración Segura - BookHeaven</h1>
    <div class='warning'><strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de completar la migración</div>
";

try {
    // Cargar Laravel
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    
    // Deshabilitar output buffering para ver mensajes en tiempo real
    ob_implicit_flush(true);
    
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    echo "<h2>📊 Estado del Sistema</h2>";
    echo "<div class='info'>";
    echo "<strong>Laravel Path:</strong> " . realpath(__DIR__ . '/..') . "<br>";
    echo "<strong>PHP Version:</strong> " . phpversion() . "<br>";
    echo "<strong>.env existe:</strong> " . (file_exists(__DIR__ . '/../.env') ? '✅' : '❌') . "<br>";
    echo "</div>";
    
    // Paso 1: Crear tabla de migraciones si no existe
    echo "<h2>🗄️ Ejecutando Migraciones</h2>";
    echo "<div class='info'>";
    echo "<strong>Paso 1: Crear tabla de migraciones y base de datos...</strong><br>";
    
    ob_start();
    $status = $kernel->call('migrate', ['--force' => true, '--step' => false]);
    $output = ob_get_clean();
    
    if ($status === 0) {
        echo "<div class='success'>✅ Migraciones completadas exitosamente</div>";
        echo "<pre>$output</pre>";
    } else {
        echo "<div class='error'>❌ Error en migraciones</div>";
        echo "<pre>$output</pre>";
        
        // Intenta nuevamente con modo paso a paso
        echo "<br><strong>Intentando modo paso a paso...</strong><br>";
        ob_start();
        $status = $kernel->call('migrate', ['--force' => true, '--step' => true]);
        $output = ob_get_clean();
        
        if ($status === 0) {
            echo "<div class='success'>✅ Migraciones completadas (modo paso a paso)</div>";
            echo "<pre>$output</pre>";
        } else {
            echo "<div class='error'>❌ Error en migraciones (paso a paso)</div>";
            echo "<pre>$output</pre>";
        }
    }
    
    echo "</div>";
    
    // Paso 2: Seeders (opcional)
    echo "<h2>🌱 Seeders (Opcional)</h2>";
    echo "<div class='info'>";
    echo "<strong>Ejecutando seeders...</strong><br>";
    
    $executeSeeders = $_GET['seed'] ?? 'false';
    
    if ($executeSeeders === 'true') {
        ob_start();
        $seedStatus = $kernel->call('db:seed', ['--force' => true]);
        $output = ob_get_clean();
        
        if ($seedStatus === 0) {
            echo "<div class='success'>✅ Seeders completados</div>";
            echo "<pre>$output</pre>";
        } else {
            echo "<div class='warning'>⚠️ Advertencia en seeders (opcional)</div>";
            echo "<pre>$output</pre>";
        }
    } else {
        echo "<div class='success'>✅ Seeders saltados (opcional)</div>";
        echo "<p><a href='?password=$password&seed=true'><button>Ejecutar Seeders Ahora</button></a></p>";
    }
    
    echo "</div>";
    
    // Paso 3: Verificar tablas creadas
    echo "<h2>✅ Verificación</h2>";
    echo "<div class='info'>";
    
    $db = $app->make(Illuminate\Database\ConnectionResolver::class);
    $connection = $db->connection();
    
    // Obtener lista de tablas
    $tables = $connection->getDoctrineSchemaManager()->listTableNames();
    
    if (!empty($tables)) {
        echo "<strong>Tablas creadas en la base de datos:</strong><br>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>✅ $table</li>";
        }
        echo "</ul>";
        echo "<div class='success'><strong>¡Despliegue exitoso!</strong></div>";
    } else {
        echo "<div class='error'>❌ No se crearon tablas. Verifica los logs.</div>";
    }
    
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<strong>❌ Error capturado:</strong><br>";
    echo "Mensaje: " . $e->getMessage() . "<br>";
    echo "Archivo: " . $e->getFile() . "<br>";
    echo "Línea: " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

echo "
<div style='margin-top: 50px; padding: 20px; background: #fff3e0; border-left: 4px solid #ff9800;'>
    <h3>⚠️ Seguridad</h3>
    <p><strong>ELIMINA este archivo después de completar:</strong></p>
    <p>Ve a File Manager → <code>public_html/api/public/</code> → Elimina <code>hostinger-migrate-safe.php</code></p>
</div>

</body>
</html>
";
