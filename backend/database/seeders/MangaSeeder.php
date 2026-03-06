<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Manga;

class MangaSeeder extends Seeder
{
    public function run(): void
    {
        $mangas = [
            // PREMIUM MANGAS (5 items)
            [
                'titulo' => 'One Piece',
                'descripcion' => 'Monkey D. Luffy viaja por los mares para encontrar el One Piece y convertirse en el Rey de los Piratas.',
                'autor' => 'Eiichiro Oda',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776256/bookheaven/mangas/imagenes/bookheaven/mangas/imagenes/one.png',
                'pdf' => null,
                'genero' => 'Shonen',
                'is_premium' => true,
                'tiene_derechos_autor' => true,
                'popularidad' => 100,
            ],
            [
                'titulo' => 'Attack on Titan',
                'descripcion' => 'La humanidad lucha por sobrevivir contra gigantes devoradores de hombres conocidos como Titanes.',
                'autor' => 'Hajime Isayama',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776227/bookheaven/mangas/imagenes/bookheaven/mangas/imagenes/aot.png',
                'pdf' => null,
                'genero' => 'Seinen',
                'is_premium' => true,
                'tiene_derechos_autor' => true,
                'popularidad' => 99,
            ],
            [
                'titulo' => 'Berserk',
                'descripcion' => 'Guts, un mercenario solitario, lucha contra demonios y su propio destino maldito.',
                'autor' => 'Kentaro Miura',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776231/bookheaven/mangas/imagenes/bookheaven/mangas/imagenes/berserk.png',
                'pdf' => null,
                'genero' => 'Fantasía Oscura',
                'is_premium' => true,
                'tiene_derechos_autor' => true,
                'popularidad' => 98,
            ],
            [
                'titulo' => 'Naruto',
                'descripcion' => 'Un joven ninja busca reconocimiento y sueña con convertirse en el Hokage de su aldea.',
                'autor' => 'Masashi Kishimoto',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776251/bookheaven/mangas/imagenes/bookheaven/mangas/imagenes/naruto.png',
                'pdf' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776331/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/naruto.pdf',
                'genero' => 'Shonen',
                'is_premium' => true,
                'tiene_derechos_autor' => true,
                'popularidad' => 97,
            ],
            [
                'titulo' => 'Blue Lock',
                'descripcion' => 'Un grupo de jóvenes futbolistas compite en un programa de entrenamiento intensivo para convertirse en el mejor delantero del mundo.',
                'autor' => 'Muneyuki Kaneshiro',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776234/bookheaven/mangas/imagenes/bookheaven/mangas/imagenes/blue_lock.png',
                'pdf' => null,
                'genero' => 'Deportes',
                'is_premium' => true,
                'tiene_derechos_autor' => true,
                'popularidad' => 96,
            ],

            // STANDARD MANGAS
            [
                'titulo' => 'Death Note',
                'descripcion' => 'Un estudiante encuentra un cuaderno sobrenatural que le permite matar a cualquiera escribiendo su nombre.',
                'autor' => 'Tsugumi Ohba',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776245/bookheaven/mangas/imagenes/bookheaven/mangas/imagenes/death.png',
                'pdf' => null,
                'genero' => 'Thriller',
                'is_premium' => false,
                'tiene_derechos_autor' => false,
                'popularidad' => 95,
            ],
            [
                'titulo' => 'Date A Live',
                'descripcion' => 'Un chico debe hacer que misteriosas chicas con poderes llamadas Espíritus se enamoren de él para sellar sus habilidades y evitar desastres. Combina citas románticas con batallas sobrenaturales.',
                'autor' => 'Kohei Horikoshi',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776241/bookheaven/mangas/imagenes/bookheaven/mangas/imagenes/date.png',
                'pdf' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776298/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/date_a_live.pdf',
                'genero' => 'Superhéroes',
                'is_premium' => false,
                'tiene_derechos_autor' => false,
                'popularidad' => 94,
            ],
            [
                'titulo' => 'Demon Slayer',
                'descripcion' => 'Un joven se convierte en cazador de demonios para vengar a su familia y curar a su hermana.',
                'autor' => 'Koyoharu Gotouge',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776249/bookheaven/mangas/imagenes/bookheaven/mangas/imagenes/demon.png',
                'pdf' => null,
                'genero' => 'Acción',
                'is_premium' => false,
                'tiene_derechos_autor' => false,
                'popularidad' => 93,
            ],
            [
                'titulo' => 'Vinland Saga',
                'descripcion' => 'Un joven guerrero busca venganza y justicia en la era de los vikingos.',
                'autor' => 'Makoto Yukimura',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776261/bookheaven/mangas/imagenes/bookheaven/mangas/imagenes/vinland.png',
                'pdf' => null,
                'genero' => 'Horror',
                'is_premium' => false,
                'tiene_derechos_autor' => false,
                'popularidad' => 92,
            ],
            [
                'titulo' => 'Dragon Ball',
                'descripcion' => 'Las aventuras de Goku mientras busca las Esferas del Dragón y lucha contra enemigos poderosos.',
                'autor' => 'Akira Toriyama',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776238/bookheaven/mangas/imagenes/bookheaven/mangas/imagenes/Dargo.png',
                'pdf' => null,
                'genero' => 'Aventura',
                'is_premium' => false,
                'tiene_derechos_autor' => false,
                'popularidad' => 91,
            ],
        ];

        foreach ($mangas as $manga) {
            Manga::firstOrCreate(
                ['titulo' => $manga['titulo']],
                $manga
            );
        }
    }
}
