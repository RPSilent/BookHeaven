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

// 2. Eliminar duplicados de libros y limitar a 10
echo "2️⃣ Eliminando libros duplicados y limitando a 10...\n";
$libros = Libro::orderBy('id', 'asc')->get();
$titulosVistos = [];
$eliminados = 0;

foreach ($libros as $libro) {
    if (in_array($libro->titulo, $titulosVistos)) {
        $libro->delete();
        $eliminados++;
        echo "   🗑️  Eliminado duplicado: {$libro->titulo} (ID: {$libro->id})\n";
    } else {
        $titulosVistos[] = $libro->titulo;
    }
}

// Si hay más de 10 únicos, eliminar los extras (los más antiguos)
$librosActuales = Libro::count();
if ($librosActuales > 10) {
    $extras = Libro::orderBy('id', 'asc')->limit($librosActuales - 10)->get();
    foreach ($extras as $extra) {
        echo "   🗑️  Eliminado extra: {$extra->titulo} (ID: {$extra->id})\n";
        $extra->delete();
        $eliminados++;
    }
}
echo "   ✅ {$eliminados} libros eliminados (ahora hay " . Libro::count() . ")\n\n";

// 3. Eliminar duplicados de mangas y limitar a 10
echo "3️⃣ Eliminando mangas duplicados y limitando a 10...\n";
$mangas = Manga::orderBy('id', 'asc')->get();
$titulosVistos = [];
$eliminados = 0;

foreach ($mangas as $manga) {
    if (in_array($manga->titulo, $titulosVistos)) {
        $manga->delete();
        $eliminados++;
        echo "   🗑️  Eliminado duplicado: {$manga->titulo} (ID: {$manga->id})\n";
    } else {
        $titulosVistos[] = $manga->titulo;
    }
}

// Si hay más de 10 únicos, eliminar los extras
$mangasActuales = Manga::count();
if ($mangasActuales > 10) {
    $extras = Manga::orderBy('id', 'asc')->limit($mangasActuales - 10)->get();
    foreach ($extras as $extra) {
        echo "   🗑️  Eliminado extra: {$extra->titulo} (ID: {$extra->id})\n";
        $extra->delete();
        $eliminados++;
    }
}
echo "   ✅ {$eliminados} mangas eliminados (ahora hay " . Manga::count() . ")\n\n";

// 4. Eliminar duplicados de comics y limitar a 10
echo "4️⃣ Eliminando comics duplicados y limitando a 10...\n";
$comics = Comic::orderBy('id', 'asc')->get();
$titulosVistos = [];
$eliminados = 0;

foreach ($comics as $comic) {
    if (in_array($comic->titulo, $titulosVistos)) {
        $comic->delete();
        $eliminados++;
        echo "   🗑️  Eliminado duplicado: {$comic->titulo} (ID: {$comic->id})\n";
    } else {
        $titulosVistos[] = $comic->titulo;
    }
}

// Si hay más de 10 únicos, eliminar los extras
$comicsActuales = Comic::count();
if ($comicsActuales > 10) {
    $extras = Comic::orderBy('id', 'asc')->limit($comicsActuales - 10)->get();
    foreach ($extras as $extra) {
        echo "   🗑️  Eliminado extra: {$extra->titulo} (ID: {$extra->id})\n";
        $extra->delete();
        $eliminados++;
    }
}
echo "   ✅ {$eliminados} comics eliminados (ahora hay " . Comic::count() . ")\n\n";

// 5. Mostrar resumen
echo "📊 RESUMEN FINAL:\n";
echo "   - Libros únicos: " . Libro::count() . "\n";
echo "   - Mangas únicos: " . Manga::count() . "\n";
echo "   - Comics únicos: " . Comic::count() . "\n";
echo "   - Total usuarios: " . User::count() . "\n";

echo "\n✅ ¡Limpieza completada!\n\n";
