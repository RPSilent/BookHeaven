<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use App\Models\Libro;
use App\Models\Manga;
use App\Models\Comic;

class CleanupDatabase extends Command
{
    protected $signature = 'db:cleanup';
    protected $description = 'Elimina duplicados y limita contenido a 10 items por tipo';

    public function handle()
    {
        $this->info('🧹 Limpiando base de datos...');
        $this->newLine();

        // 1. Actualizar Kristofer a Admin
        $this->info('1️⃣ Actualizando usuario Kristofer a Admin...');
        $adminRole = Role::where('name', 'admin')->first();
        $kristofer = User::where('email', 'kristofercanotaborda@gmail.com')->first();
        
        if ($kristofer && $adminRole) {
            $kristofer->role_id = $adminRole->id;
            $kristofer->is_active = true;
            $kristofer->email_verified_at = now();
            $kristofer->save();
            $this->line('   ✅ Kristofer ahora es Admin (role_id: ' . $adminRole->id . ')');
        } else {
            $this->warn('   ⚠️  Usuario Kristofer o rol admin no encontrado');
        }
        $this->newLine();

        // 2. Limpiar libros
        $this->info('2️⃣ Eliminando libros duplicados y limitando a 10...');
        $eliminados = $this->cleanupContent(Libro::class, 'titulo');
        $this->line('   ✅ ' . $eliminados . ' libros eliminados (ahora hay ' . Libro::count() . ')');
        $this->newLine();

        // 3. Limpiar mangas
        $this->info('3️⃣ Eliminando mangas duplicados y limitando a 10...');
        $eliminados = $this->cleanupContent(Manga::class, 'titulo');
        $this->line('   ✅ ' . $eliminados . ' mangas eliminados (ahora hay ' . Manga::count() . ')');
        $this->newLine();

        // 4. Limpiar comics
        $this->info('4️⃣ Eliminando comics duplicados y limitando a 10...');
        $eliminados = $this->cleanupContent(Comic::class, 'titulo');
        $this->line('   ✅ ' . $eliminados . ' comics eliminados (ahora hay ' . Comic::count() . ')');
        $this->newLine();

        // 5. Resumen
        $this->info('📊 RESUMEN FINAL:');
        $this->line('   - Libros únicos: ' . Libro::count());
        $this->line('   - Mangas únicos: ' . Manga::count());
        $this->line('   - Comics únicos: ' . Comic::count());
        $this->line('   - Total usuarios: ' . User::count());
        $this->newLine();

        $this->info('✅ ¡Limpieza completada!');
        return 0;
    }

    private function cleanupContent($modelClass, $uniqueField)
    {
        $items = $modelClass::orderBy('id', 'asc')->get();
        $valoresVistos = [];
        $eliminados = 0;

        // Eliminar duplicados por campo único
        foreach ($items as $item) {
            $valor = $item->$uniqueField;
            if (in_array($valor, $valoresVistos)) {
                $this->line('   🗑️  Eliminado duplicado: ' . $valor . ' (ID: ' . $item->id . ')');
                $item->delete();
                $eliminados++;
            } else {
                $valoresVistos[] = $valor;
            }
        }

        // Si hay más de 10 únicos, eliminar los extras (los más antiguos)
        $count = $modelClass::count();
        if ($count > 10) {
            $extras = $modelClass::orderBy('id', 'asc')->limit($count - 10)->get();
            foreach ($extras as $extra) {
                $this->line('   🗑️  Eliminado extra: ' . $extra->$uniqueField . ' (ID: ' . $extra->id . ')');
                $extra->delete();
                $eliminados++;
            }
        }

        return $eliminados;
    }
}
