<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Models\Libro;
use App\Models\Manga;
use App\Models\Comic;

echo "\n🧹 Limpiando base de datos...\n\n";

// 1. Asegurar que Kristofer sea admin
echo "1️⃣ Actualizando usuario Kristofer a Admin...\n";
$adminRole = Role::where('name', 'admin')->first();
$kristofer = User::where('email', 'kristofercanotaborda@gmail.com')->first();

if ($kristofer && $adminRole) {
    $kristofer->role_id = $adminRole->id;
    $kristofer->is_active = true;
    $kristofer->email_verified_at = now();
    $kristofer->save();
    echo "   ✅ Kristofer ahora es Admin (role_id: {$adminRole->id})\n\n";
} else {
    echo "   ⚠️ No se encontró el usuario o el rol admin\n\n";
}

// 2. Eliminar duplicados de libros
echo "2️⃣ Eliminando libros duplicados...\n";
$libros = Libro::all();
$titulosVistos = [];
$eliminados = 0;

foreach ($libros as $libro) {
    if (in_array($libro->titulo, $titulosVistos)) {
        $libro->delete();
        $eliminados++;
        echo "   🗑️  Eliminado: {$libro->titulo} (ID: {$libro->id})\n";
    } else {
        $titulosVistos[] = $libro->titulo;
    }
}
echo "   ✅ {$eliminados} libros duplicados eliminados\n\n";

// 3. Eliminar duplicados de mangas
echo "3️⃣ Eliminando mangas duplicados...\n";
$mangas = Manga::all();
$titulosVistos = [];
$eliminados = 0;

foreach ($mangas as $manga) {
    if (in_array($manga->titulo, $titulosVistos)) {
        $manga->delete();
        $eliminados++;
        echo "   🗑️  Eliminado: {$manga->titulo} (ID: {$manga->id})\n";
    } else {
        $titulosVistos[] = $manga->titulo;
    }
}
echo "   ✅ {$eliminados} mangas duplicados eliminados\n\n";

// 4. Eliminar duplicados de comics
echo "4️⃣ Eliminando comics duplicados...\n";
$comics = Comic::all();
$titulosVistos = [];
$eliminados = 0;

foreach ($comics as $comic) {
    if (in_array($comic->titulo, $titulosVistos)) {
        $comic->delete();
        $eliminados++;
        echo "   🗑️  Eliminado: {$comic->titulo} (ID: {$comic->id})\n";
    } else {
        $titulosVistos[] = $comic->titulo;
    }
}
echo "   ✅ {$eliminados} comics duplicados eliminados\n\n";

// 5. Mostrar resumen
echo "📊 RESUMEN FINAL:\n";
echo "   - Libros únicos: " . Libro::count() . "\n";
echo "   - Mangas únicos: " . Manga::count() . "\n";
echo "   - Comics únicos: " . Comic::count() . "\n";
echo "   - Total usuarios: " . User::count() . "\n";

echo "\n✅ ¡Limpieza completada!\n\n";
