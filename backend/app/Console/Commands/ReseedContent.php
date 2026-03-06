<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Libro;
use App\Models\Manga;
use App\Models\Comic;
use Illuminate\Support\Facades\Artisan;

class ReseedContent extends Command
{
    protected $signature = 'content:reseed';
    protected $description = 'Elimina todo el contenido y vuelve a ejecutar los seeders con URLs de Cloudinary';

    public function handle()
    {
        $this->info('🔄 Reseeding content con URLs de Cloudinary...');
        
        // Eliminar todos los registros
        $this->info('🗑️  Eliminando libros existentes...');
        $deletedLibros = Libro::count();
        Libro::truncate();
        $this->info("   ✅ {$deletedLibros} libros eliminados");
        
        $this->info('🗑️  Eliminando mangas existentes...');
        $deletedMangas = Manga::count();
        Manga::truncate();
        $this->info("   ✅ {$deletedMangas} mangas eliminados");
        
        $this->info('🗑️  Eliminando comics existentes...');
        $deletedComics = Comic::count();
        Comic::truncate();
        $this->info("   ✅ {$deletedComics} comics eliminados");
        
        // Ejecutar seeders
        $this->info('');
        $this->info('📚 Ejecutando seeders con URLs de Cloudinary...');
        
        $this->info('   📖 Seeding LibroSeeder...');
        Artisan::call('db:seed', ['--class' => 'LibroSeeder', '--force' => true]);
        $this->info('   ✅ LibroSeeder completado');
        
        $this->info('   📗 Seeding MangaSeeder...');
        Artisan::call('db:seed', ['--class' => 'MangaSeeder', '--force' => true]);
        $this->info('   ✅ MangaSeeder completado');
        
        $this->info('   📘 Seeding ComicSeeder...');
        Artisan::call('db:seed', ['--class' => 'ComicSeeder', '--force' => true]);
        $this->info('   ✅ ComicSeeder completado');
        
        // Verificar resultados
        $this->info('');
        $this->info('📊 RESUMEN FINAL:');
        $this->info('   - Libros: ' . Libro::count());
        $this->info('   - Mangas: ' . Manga::count());
        $this->info('   - Comics: ' . Comic::count());
        
        $this->info('');
        $this->info('✅ ¡Reseed completado con URLs de Cloudinary!');
        
        return 0;
    }
}
