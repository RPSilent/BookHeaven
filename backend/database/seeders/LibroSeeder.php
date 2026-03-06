<?php

namespace Database\Seeders;

use App\Models\Libro;
use Illuminate\Database\Seeder;

class LibroSeeder extends Seeder
{
    public function run(): void
    {
        $libros = [
            // PREMIUM BOOKS (5 items)
            [
                'titulo' => 'Cien años de soledad',
                'descripcion' => 'La historia de la familia Buendía a lo largo de varias generaciones en el pueblo ficticio de Macondo.',
                'autor' => 'Gabriel García Márquez',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776171/bookheaven/libros/imagenes/bookheaven/libros/imagenes/cien.png',
                'pdf' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776197/bookheaven/libros/pdfs/bookheaven/libros/pdfs/1.pdf',
                'genero' => 'Realismo Mágico',
                'is_premium' => true,
                'tiene_derechos_autor' => true,
                'popularidad' => 98,
            ],
            [
                'titulo' => 'Don Quijote de la Mancha',
                'descripcion' => 'Un hidalgo enloquece leyendo libros de caballería y decide convertirse en caballero andante.',
                'autor' => 'Miguel de Cervantes Saavedra',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776177/bookheaven/libros/imagenes/bookheaven/libros/imagenes/don.png',
                'pdf' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776203/bookheaven/libros/pdfs/bookheaven/libros/pdfs/don_quijote.pdf',
                'genero' => 'Novela',
                'is_premium' => true,
                'tiene_derechos_autor' => true,
                'popularidad' => 95,
            ],
            [
                'titulo' => '1984',
                'descripcion' => 'Una sociedad vigilada por el Gran Hermano donde el pensamiento libre está prohibido.',
                'autor' => 'George Orwell',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776160/bookheaven/libros/imagenes/bookheaven/libros/imagenes/1984.png',
                'pdf' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776197/bookheaven/libros/pdfs/bookheaven/libros/pdfs/1.pdf',
                'genero' => 'Distopía',
                'is_premium' => true,
                'tiene_derechos_autor' => true,
                'popularidad' => 92,
            ],
            [
                'titulo' => 'El Señor de los Anillos',
                'descripcion' => 'Un hobbit emprende una peligrosa misión para destruir un anillo poderoso.',
                'autor' => 'J. R. R. Tolkien',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776166/bookheaven/libros/imagenes/bookheaven/libros/imagenes/anillos.png',
                'pdf' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776224/bookheaven/libros/pdfs/bookheaven/libros/pdfs/senor_anillos.pdf',
                'genero' => 'Fantasía',
                'is_premium' => true,
                'tiene_derechos_autor' => true,
                'popularidad' => 99,
            ],
            [
                'titulo' => 'Harry Potter y la Piedra Filosofal',
                'descripcion' => 'Un niño descubre que es mago y asiste a una escuela de magia.',
                'autor' => 'J. K. Rowling',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776185/bookheaven/libros/imagenes/bookheaven/libros/imagenes/harry.png',
                'pdf' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776213/bookheaven/libros/pdfs/bookheaven/libros/pdfs/harry_potter.pdf',
                'genero' => 'Fantasía',
                'is_premium' => true,
                'tiene_derechos_autor' => true,
                'popularidad' => 97,
            ],
            
            // STANDARD BOOKS
            [
                'titulo' => 'El Principito',
                'descripcion' => 'Un relato poético sobre la amistad, el amor y el sentido de la vida.',
                'autor' => 'Antoine de Saint-Exupéry',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776195/bookheaven/libros/imagenes/bookheaven/libros/imagenes/principito.png',
                'pdf' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776222/bookheaven/libros/pdfs/bookheaven/libros/pdfs/principito.pdf',
                'genero' => 'Fábula',
                'is_premium' => false,
                'tiene_derechos_autor' => false,
                'popularidad' => 90,
            ],
            [
                'titulo' => 'Crimen y castigo',
                'descripcion' => 'La lucha psicológica de un joven que comete un asesinato y enfrenta su culpa.',
                'autor' => 'Fiódor Dostoyevski',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776173/bookheaven/libros/imagenes/bookheaven/libros/imagenes/crimen.png',
                'pdf' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776200/bookheaven/libros/pdfs/bookheaven/libros/pdfs/crimen_castigo.pdf',
                'genero' => 'Novela Psicológica',
                'is_premium' => false,
                'tiene_derechos_autor' => false,
                'popularidad' => 88,
            ],
            [
                'titulo' => 'La metamorfosis',
                'descripcion' => 'Un hombre despierta convertido en un insecto gigante, cambiando su vida y la de su familia.',
                'autor' => 'Franz Kafka',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776187/bookheaven/libros/imagenes/bookheaven/libros/imagenes/meta.png',
                'pdf' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776215/bookheaven/libros/pdfs/bookheaven/libros/pdfs/metamorfosis.pdf',
                'genero' => 'Absurdo',
                'is_premium' => false,
                'tiene_derechos_autor' => false,
                'popularidad' => 85,
            ],
            [
                'titulo' => 'Orgullo y prejuicio',
                'descripcion' => 'Una historia de amor y diferencias sociales en la Inglaterra del siglo XIX.',
                'autor' => 'Jane Austen',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776192/bookheaven/libros/imagenes/bookheaven/libros/imagenes/orgullo.png',
                'pdf' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776217/bookheaven/libros/pdfs/bookheaven/libros/pdfs/orgullo_prejuicio.pdf',
                'genero' => 'Romance',
                'is_premium' => false,
                'tiene_derechos_autor' => false,
                'popularidad' => 89,
            ],
            [
                'titulo' => 'Fahrenheit 451',
                'descripcion' => 'En un futuro donde los libros están prohibidos, los bomberos se encargan de quemarlos.',
                'autor' => 'Ray Bradbury',
                'imagen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776179/bookheaven/libros/imagenes/bookheaven/libros/imagenes/fah.png',
                'pdf' => null,
                'genero' => 'Ciencia Ficción',
                'is_premium' => false,
                'tiene_derechos_autor' => false,
                'popularidad' => 87,
            ],
        ];

        foreach ($libros as $libro) {
            Libro::updateOrCreate(
                ['titulo' => $libro['titulo']],
                $libro
            );
        }
    }
}

