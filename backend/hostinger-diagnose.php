<?php
/**
 * Script de diagnóstico para problemas de conexión a BD
 * Verifica credenciales y limpia caché completamente
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ADMIN_PASSWORD', 'cambiar_este_password_123');
$password = $_GET['password'] ?? '';
if ($password !== ADMIN_PASSWORD) {
    die('❌ Password incorrecto.');
}

echo "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Diagnóstico Hostinger - BookHeaven</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 50px auto; padding: 20px; }
        .success { color: green; background: #e8f5e9; padding: 15px; margin: 10px 0; border-left: 4px solid green; }
        .error { color: red; background: #ffebee; padding: 15px; margin: 10px 0; border-left: 4px solid red; }
        .warning { color: orange; background: #fff3e0; padding: 15px; margin: 10px 0; border-left: 4px solid orange; }
        .info { background: #e3f2fd; padding: 15px; margin: 10px 0; border-left: 4px solid #2196F3; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; font-size: 12px; }
        h1 { color: #333; }
        h2 { color: #555; border-bottom: 2px solid #2196F3; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f5f5; font-weight: bold; }
    </style>
</head>
<body>
    <h1>🔍 Diagnóstico Hostinger - BookHeaven</h1>
    <div class='warning'><strong>⚠️:</strong> Este es un script de diagnóstico. Será eliminado después del uso.</div>
";

// Rutas importantes
$basePath = __DIR__ . '/..';
$envFile = $basePath . '/.env';
$configCache = $basePath . '/bootstrap/cache/config.php';
$routeCache = $basePath . '/bootstrap/cache/routes-v7.php';

echo "<h2>1️⃣ Verificación de Archivos</h2>";
echo "<table>";
echo "<tr><th>Archivo</th><th>Estado</th><th>Ruta</th></tr>";

$files = [
    '.env' => $envFile,
    'artisan' => $basePath . '/artisan',
    'vendor/autoload.php' => $basePath . '/vendor/autoload.php',
    'bootstrap/app.php' => $basePath . '/bootstrap/app.php',
    'config/database.php' => $basePath . '/config/database.php',
    'config/cache.php' => $basePath . '/config/cache.php',
];

foreach ($files as $name => $path) {
    $exists = file_exists($path);
    $status = $exists ? '<span style="color: green;">✅ Existe</span>' : '<span style="color: red;">❌ No existe</span>';
    echo "<tr><td>$name</td><td>$status</td><td>" . realpath($path) . "</td></tr>";
}

echo "</table>";

echo "<h2>2️⃣ Contenido del .env</h2>";
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    echo "<div class='info'>";
    echo "<strong>Primeras 30 líneas del .env:</strong><br>";
    $lines = array_slice(explode("\n", $envContent), 0, 30);
    echo "<pre>" . htmlspecialchars(implode("\n", $lines)) . "</pre>";
    
    // Buscar credenciales
    preg_match('/DB_HOST=(.*)/', $envContent, $host);
    preg_match('/DB_PORT=(.*)/', $envContent, $port);
    preg_match('/DB_DATABASE=(.*)/', $envContent, $db);
    preg_match('/DB_USERNAME=(.*)/', $envContent, $user);
    preg_match('/DB_PASSWORD=(.*)/', $envContent, $pass);
    
    echo "<strong>Credenciales encontradas:</strong><br>";
    echo "DB_HOST: " . (isset($host[1]) ? trim($host[1]) : 'NO ENCONTRADO') . "<br>";
    echo "DB_PORT: " . (isset($port[1]) ? trim($port[1]) : 'NO ENCONTRADO') . "<br>";
    echo "DB_DATABASE: " . (isset($db[1]) ? trim($db[1]) : 'NO ENCONTRADO') . "<br>";
    echo "DB_USERNAME: " . (isset($user[1]) ? trim($user[1]) : 'NO ENCONTRADO') . "<br>";
    echo "DB_PASSWORD: " . (isset($pass[1]) ? '***' . substr(trim($pass[1]), -3) : 'NO ENCONTRADO') . "<br>";
    echo "</div>";
} else {
    echo "<div class='error'>❌ Archivo .env no existe en: $envFile</div>";
}

echo "<h2>3️⃣ Limpieza de Caché</h2>";
echo "<div class='info'>";

// Limpiar archivos de caché
$cacheFiles = [
    'config.php' => $basePath . '/bootstrap/cache/config.php',
    'routes-v7.php' => $basePath . '/bootstrap/cache/routes-v7.php',
    'routes.php' => $basePath . '/bootstrap/cache/routes.php',
];

foreach ($cacheFiles as $name => $path) {
    if (file_exists($path)) {
        if (unlink($path)) {
            echo "<div class='success'>✅ Eliminado: $name</div>";
        } else {
            echo "<div class='error'>❌ No se pudo elimiar: $name</div>";
        }
    }
}

// Limpiar carpeta storage/framework/cache
$cacheDir = $basePath . '/storage/framework/cache';
if (is_dir($cacheDir)) {
    $files = glob($cacheDir . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "<div class='success'>✅ Limpiada carpeta storage/framework/cache</div>";
}

echo "</div>";

echo "<h2>4️⃣ Prueba de Conexión a Base de Datos</h2>";
echo "<div class='info'>";

try {
    // Extraer credenciales del .env
    $envContent = file_get_contents($envFile);
    preg_match('/DB_HOST=(.*)/', $envContent, $host);
    preg_match('/DB_PORT=(.*)/', $envContent, $port);
    preg_match('/DB_DATABASE=(.*)/', $envContent, $db);
    preg_match('/DB_USERNAME=(.*)/', $envContent, $user);
    preg_match('/DB_PASSWORD=(.*)/', $envContent, $pass);
    
    $dbHost = trim($host[1] ?? 'localhost');
    $dbPort = trim($port[1] ?? '3306');
    $dbName = trim($db[1] ?? '');
    $dbUser = trim($user[1] ?? '');
    $dbPass = trim($pass[1] ?? '');
    
    echo "<strong>Intentando conectar con:</strong><br>";
    echo "Host: $dbHost:$dbPort<br>";
    echo "Database: $dbName<br>";
    echo "User: $dbUser<br>";
    
    $mysqli = @mysqli_connect($dbHost, $dbUser, $dbPass, $dbName, (int)$dbPort);
    
    if ($mysqli) {
        echo "<div class='success'>✅ Conexión exitosa a la base de datos</div>";
        
        // Verificar tablas
        $result = mysqli_query($mysqli, "SHOW TABLES");
        $tables = [];
        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }
        
        if (count($tables) > 0) {
            echo "<strong>Tablas existentes: " . count($tables) . "</strong><br>";
            echo "<ul>";
            foreach ($tables as $table) {
                echo "<li>✅ $table</li>";
            }
            echo "</ul>";
        } else {
            echo "<div class='warning'>⚠️ No hay tablas en la base de datos (normal si es la primera vez)</div>";
        }
        
        mysqli_close($mysqli);
    } else {
        echo "<div class='error'>❌ Error de conexión: " . mysqli_connect_error() . "</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Excepción: " . $e->getMessage() . "</div>";
}

echo "</div>";

echo "<h2>5️⃣ Próximos Pasos</h2>";
echo "<div class='success'>";
echo "<p>Después de este diagnóstico:</p>";
echo "<ol>";
echo "<li>Si la conexión fue exitosa ✅, intenta nuevamente ejecutar migraciones con: <a href='?password=$password&action=migrate'><button>🗄️ Intentar Migración</button></a></li>";
echo "<li>Si la conexión falló ❌, revisa las credenciales de la base de datos en cPanel</li>";
echo "</ol>";
echo "</div>";

echo "<div class='warning'><strong>⚠️ Seguridad:</strong> Elimina este archivo después del diagnóstico</div>";
echo "</body></html>";
