<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Libro;
use App\Models\Manga;
use App\Models\Comic;
use Illuminate\Support\Facades\DB;

class UpdatePdfUrls extends Command
{
    protected $signature = 'content:update-pdfs';
    protected $description = 'Actualiza las URLs de PDFs a Cloudinary';

    public function handle()
    {
        $this->info('📝 Actualizando URLs de PDFs a Cloudinary...');
        
        // URLs de PDFs de Cloudinary para libros
        $librosPdfs = [
            'Cien años de soledad' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776197/bookheaven/libros/pdfs/bookheaven/libros/pdfs/1.pdf',
            'Don Quijote de la Mancha' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776203/bookheaven/libros/pdfs/bookheaven/libros/pdfs/don_quijote.pdf',
            '1984' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776197/bookheaven/libros/pdfs/bookheaven/libros/pdfs/1.pdf',
            'El Señor de los Anillos' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776224/bookheaven/libros/pdfs/bookheaven/libros/pdfs/senor_anillos.pdf',
            'Harry Potter y la Piedra Filosofal' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776213/bookheaven/libros/pdfs/bookheaven/libros/pdfs/harry_potter.pdf',
            'El Principito' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776222/bookheaven/libros/pdfs/bookheaven/libros/pdfs/principito.pdf',
            'Crimen y castigo' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776200/bookheaven/libros/pdfs/bookheaven/libros/pdfs/crimen_castigo.pdf',
            'La metamorfosis' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776215/bookheaven/libros/pdfs/bookheaven/libros/pdfs/metamorfosis.pdf',
            'Orgullo y prejuicio' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776217/bookheaven/libros/pdfs/bookheaven/libros/pdfs/orgullo_prejuicio.pdf',
        ];
        
        // URLs de PDFs de Cloudinary para mangas
        $mangasPdfs = [
            'Naruto' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776271/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/Naruto.pdf',
            'One Piece' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776277/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/one.pdf',
            'Death Note' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776268/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/death.pdf',
            'Blue Lock' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776264/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/blue_lock.pdf',
            'Demon Slayer' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776245/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/demon_slayer.pdf',
            'Attack on Titan' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776247/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/attack.pdf',
            'Dragon Ball' => null, // No hay PDF disponible
            'Berserk' => null, // No hay PDF disponible
            'Date A Live' => null, // No hay PDF disponible
            'Vinland Saga' => null, // No hay PDF disponible
        ];
        
        // URLs de PDFs de Cloudinary para comics
        $comicsPdfs = [
            'Watchmen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776423/bookheaven/comics/pdfs/bookheaven/comics/pdfs/watchmen.pdf',
            'The Sandman' => null, // No hay PDF disponible
            'Batman: The Killing Joke' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776396/bookheaven/comics/pdfs/bookheaven/comics/pdfs/batman_dark_knight.pdf',
            'Maus' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776403/bookheaven/comics/pdfs/bookheaven/comics/pdfs/maus.pdf',
            'V de Vendetta' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776419/bookheaven/comics/pdfs/bookheaven/comics/pdfs/v_vendetta.pdf',
            'the amazing spiderman' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776400/bookheaven/comics/pdfs/bookheaven/comics/pdfs/spiderman_kraven.pdf',
            'X-Men: Days of Future Past' => null, // No hay PDF disponible
            'Civil War' => null, // No hay PDF disponible
            'The Walking Dead' => null, // No hay PDF disponible
            'Deadpool' => null, // No hay PDF disponible
        ];
        
        // Actualizar libros
        $librosUpdated = 0;
        foreach ($librosPdfs as $titulo => $pdfUrl) {
            $updated = Libro::where('titulo', $titulo)->update(['pdf' => $pdfUrl]);
            if ($updated) {
                $librosUpdated++;
                $this->info("   ✅ Libro '{$titulo}' actualizado");
            }
        }
        
        // Actualizar mangas
        $mangasUpdated = 0;
        foreach ($mangasPdfs as $titulo => $pdfUrl) {
            if ($pdfUrl !== null) { // Solo actualizar si hay URL de PDF
                $updated = Manga::where('titulo', $titulo)->update(['pdf' => $pdfUrl]);
                if ($updated) {
                    $mangasUpdated++;
                    $this->info("   ✅ Manga '{$titulo}' actualizado");
                }
            }
        }
        
        // Actualizar comics
        $comicsUpdated = 0;
        foreach ($comicsPdfs as $titulo => $pdfUrl) {
            if ($pdfUrl !== null) { // Solo actualizar si hay URL de PDF
                $updated = Comic::where('titulo', $titulo)->update(['pdf' => $pdfUrl]);
                if ($updated) {
                    $comicsUpdated++;
                    $this->info("   ✅ Comic '{$titulo}' actualizado");
                }
            }
        }
        
        $this->info('');
        $this->info('📊 RESUMEN:');
        $this->info("   - Libros actualizados: {$librosUpdated}");
        $this->info("   - Mangas actualizados: {$mangasUpdated}");
        $this->info("   - Comics actualizados: {$comicsUpdated}");
        $this->info('');
        $this->info('✅ ¡Actualización de PDFs completada!');
        
        return 0;
    }
}
