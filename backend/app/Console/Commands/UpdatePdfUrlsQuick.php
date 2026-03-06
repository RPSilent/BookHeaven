<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdatePdfUrlsQuick extends Command
{
    protected $signature = 'content:update-pdfs-quick';
    protected $description = 'Actualiza las URLs de PDFs a Cloudinary usando SQL directo';

    public function handle()
    {
        $this->info('📝 Actualizando URLs de PDFs a Cloudinary con SQL...');
        
        // Actualizar libros
        $this->info('📚 Actualizando libros...');
        DB::statement("UPDATE libros
 SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776197/bookheaven/libros/pdfs/bookheaven/libros/pdfs/1.pdf' WHERE titulo = 'Cien años de soledad'");
        DB::statement("UPDATE libros SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776203/bookheaven/libros/pdfs/bookheaven/libros/pdfs/don_quijote.pdf' WHERE titulo = 'Don Quijote de la Mancha'");
        DB::statement("UPDATE libros SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776197/bookheaven/libros/pdfs/bookheaven/libros/pdfs/1.pdf' WHERE titulo = '1984'");
        DB::statement("UPDATE libros SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776224/bookheaven/libros/pdfs/bookheaven/libros/pdfs/senor_anillos.pdf' WHERE titulo = 'El Señor de los Anillos'");
        DB::statement("UPDATE libros SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776213/bookheaven/libros/pdfs/bookheaven/libros/pdfs/harry_potter.pdf' WHERE titulo = 'Harry Potter y la Piedra Filosofal'");
        DB::statement("UPDATE libros SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776222/bookheaven/libros/pdfs/bookheaven/libros/pdfs/principito.pdf' WHERE titulo = 'El Principito'");
        DB::statement("UPDATE libros SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776200/bookheaven/libros/pdfs/bookheaven/libros/pdfs/crimen_castigo.pdf' WHERE titulo = 'Crimen y castigo'");
        DB::statement("UPDATE libros SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776215/bookheaven/libros/pdfs/bookheaven/libros/pdfs/metamorfosis.pdf' WHERE titulo = 'La metamorfosis'");
        DB::statement("UPDATE libros SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776217/bookheaven/libros/pdfs/bookheaven/libros/pdfs/orgullo_prejuicio.pdf' WHERE titulo = 'Orgullo y prejuicio'");
        $this->info('✅ Libros actualizados');
        
        // Actualizar mangas
        $this->info('📗 Actualizando mangas...');
        DB::statement("UPDATE mangas SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776271/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/Naruto.pdf' WHERE titulo = 'Naruto'");
        DB::statement("UPDATE mangas SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776277/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/one.pdf' WHERE titulo = 'One Piece'");
        DB::statement("UPDATE mangas SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776268/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/death.pdf' WHERE titulo = 'Death Note'");
        DB::statement("UPDATE mangas SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776264/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/blue_lock.pdf' WHERE titulo = 'Blue Lock'");
        DB::statement("UPDATE mangas SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776245/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/demon_slayer.pdf' WHERE titulo = 'Demon Slayer'");
        DB::statement("UPDATE mangas SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776247/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/attack.pdf' WHERE titulo = 'Attack on Titan'");
        $this->info('✅ Mangas actualizados');
        
        // Actualizar comics
        $this->info('📘 Actualizando comics...');
        DB::statement("UPDATE comics SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776423/bookheaven/comics/pdfs/bookheaven/comics/pdfs/watchmen.pdf' WHERE titulo = 'Watchmen'");
        DB::statement("UPDATE comics SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776396/bookheaven/comics/pdfs/bookheaven/comics/pdfs/batman_dark_knight.pdf' WHERE titulo = 'Batman: The Killing Joke'");
        DB::statement("UPDATE comics SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776403/bookheaven/comics/pdfs/bookheaven/comics/pdfs/maus.pdf' WHERE titulo = 'Maus'");
        DB::statement("UPDATE comics SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776419/bookheaven/comics/pdfs/bookheaven/comics/pdfs/v_vendetta.pdf' WHERE titulo = 'V de Vendetta'");
        DB::statement("UPDATE comics SET pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776400/bookheaven/comics/pdfs/bookheaven/comics/pdfs/spiderman_kraven.pdf' WHERE titulo = 'the amazing spiderman'");
        $this->info('✅ Comics actualizados');
        
        $this->info('');
        $this->info('✅ ¡Todas las URLs de PDFs actualizadas correctamente!');
        
        return 0;
    }
}
