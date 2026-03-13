<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Actualizar rutas de libros
DB::table('libros')->update([
    'pdf' => DB::raw("REPLACE(pdf, '/storage/', '')"),
    'imagen' => DB::raw("REPLACE(imagen, '/storage/', '')")
]);

// Actualizar rutas de mangas
DB::table('mangas')->update([
    'pdf' => DB::raw("REPLACE(pdf, '/storage/', '')"),
    'imagen' => DB::raw("REPLACE(imagen, '/storage/', '')")
]);

// Actualizar rutas de comics
DB::table('comics')->update([
    'pdf' => DB::raw("REPLACE(pdf, '/storage/', '')"),
    'imagen' => DB::raw("REPLACE(imagen, '/storage/', '')")
]);

echo "✅ Rutas actualizadas correctamente\n";

// Verificar
$libro = DB::table('libros')->first();
echo "Ejemplo de ruta actualizada: {$libro->pdf}\n";
