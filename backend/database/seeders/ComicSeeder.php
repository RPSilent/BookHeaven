<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comic;

class ComicSeeder extends Seeder
{
    public function run(): void
    {
        $comics = [
            // PREMIUM COMICS (5 items)
            [
                'titulo' => 'Watchmen',
                'descripcion' => 'Un grupo de superhéroes retirados investiga el asesinato de uno de los suyos en una Guerra Fría alternativa.',
                'autor' => 'Alan Moore',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776387/bookheaven/comics/imagenes/bookheaven/comics/imagenes/watch.png',
                'pdf' => null,
                'genero' => 'Superhéroes',
                'is_premium' => true,
                'tiene_derechos_autor' => true,
                'popularidad' => 99,
            ],
            [
                'titulo' => 'The Sandman',
                'descripcion' => 'Morpheus, el Rey de los Sueños, escapa de su cautiverio y reconstruye su reino caído.',
                'autor' => 'Neil Gaiman',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776387/bookheaven/comics/imagenes/bookheaven/comics/imagenes/watch.png',
                'pdf' => null,
                'genero' => 'Fantasía Oscura',
                'is_premium' => true,
                'tiene_derechos_autor' => true,
                'popularidad' => 98,
            ],
            [
                'titulo' => 'Batman: The Killing Joke',
                'descripcion' => 'El Joker intenta demostrar que un mal día puede volver loco a cualquiera, torturando al Comisionado Gordon.',
                'autor' => 'Alan Moore',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776352/bookheaven/comics/imagenes/bookheaven/comics/imagenes/bat.png',
                'pdf' => null,
                'genero' => 'Crimen',
                'is_premium' => true,
                'tiene_derechos_autor' => true,
                'popularidad' => 97,
            ],
            [
                'titulo' => 'Maus',
                'descripcion' => 'La historia de un superviviente del Holocausto contada a través de ratones y gatos antropomórficos.',
                'autor' => 'Art Spiegelman',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776368/bookheaven/comics/imagenes/bookheaven/comics/imagenes/maus.png',
                'pdf' => null,
                'genero' => 'Biografía',
                'is_premium' => true,
                'tiene_derechos_autor' => true,
                'popularidad' => 96,
            ],
            [
                'titulo' => 'V de Vendetta',
                'descripcion' => 'Un anarquista enmascarado lucha contra un gobierno fascista en una Inglaterra distópica.',
                'autor' => 'Alan Moore',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776384/bookheaven/comics/imagenes/bookheaven/comics/imagenes/v.png',
                'pdf' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776453/bookheaven/comics/pdfs/bookheaven/comics/pdfs/v_vendetta.pdf',
                'genero' => 'Distopía',
                'is_premium' => true,
                'tiene_derechos_autor' => true,
                'popularidad' => 95,
            ],

            // STANDARD COMICS
            [
                'titulo' => 'the amazing spiderman',
                'descripcion' => 'Peter Parker recuerda su amor perdido, Gwen Stacy, en el día de San Valentín.',
                'autor' => 'Jeph Loeb',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776382/bookheaven/comics/imagenes/bookheaven/comics/imagenes/spid.png',
                'pdf' => null,
                'genero' => 'Romance',
                'is_premium' => false,
                'tiene_derechos_autor' => false,
                'popularidad' => 94,
            ],
            [
                'titulo' => 'X-Men: Days of Future Past',
                'descripcion' => 'Kitty Pryde viaja al pasado para evitar un futuro donde los mutantes son cazados por Centinelas.',
                'autor' => 'Chris Claremont',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776393/bookheaven/comics/imagenes/bookheaven/comics/imagenes/x.png',
                'pdf' => null,
                'genero' => 'Ciencia Ficción',
                'is_premium' => false,
                'tiene_derechos_autor' => false,
                'popularidad' => 93,
            ],
            [
                'titulo' => 'Civil War',
                'descripcion' => 'Los superhéroes se dividen en dos bandos tras una ley que obliga a revelar sus identidades.',
                'autor' => 'Mark Millar',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776356/bookheaven/comics/imagenes/bookheaven/comics/imagenes/civil.png',
                'pdf' => null,
                'genero' => 'Acción',
                'is_premium' => false,
                'tiene_derechos_autor' => false,
                'popularidad' => 92,
            ],
            [
                'titulo' => 'The Walking Dead',
                'descripcion' => 'Un grupo de supervivientes lucha por mantenerse con vida en un mundo infestado de zombis.',
                'autor' => 'Robert Kirkman',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776359/bookheaven/comics/imagenes/bookheaven/comics/imagenes/dead.png',
                'pdf' => null,
                'genero' => 'Horror',
                'is_premium' => false,
                'tiene_derechos_autor' => false,
                'popularidad' => 91,
            ],
            [
                'titulo' => 'Deadpool',
                'descripcion' => 'Un mercenario bocazas con habilidades regenerativas lucha contra enemigos y se enfrenta a situaciones cómicas y violentas.',
                'autor' => 'Brian K. Vaughan',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776363/bookheaven/comics/imagenes/bookheaven/comics/imagenes/deadpool.png',
                'pdf' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776405/bookheaven/comics/pdfs/bookheaven/comics/pdfs/deadpool.pdf',
                'genero' => 'Space Opera',
                'is_premium' => false,
                'tiene_derechos_autor' => false,
                'popularidad' => 90,
            ],
        ];

        foreach ($comics as $comic) {
            Comic::create($comic);
        }
    }
}
