<?php

require __DIR__.'/vendor/autoload.php';

use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;

// Cargar variables de entorno desde .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Inicializar Cloudinary
$cloudinary = new Cloudinary($_ENV['CLOUDINARY_URL'] ?? $_ENV['CLOUDINARY_CLOUD_NAME']);

$mediaPath = __DIR__ . '/public/storage/media';
$uploadedFiles = [];

echo "\n🚀 Iniciando subida de archivos a Cloudinary...\n\n";

// Función para subir archivos
function uploadDirectory($cloudinary, $localPath, $cloudinaryFolder) {
    global $uploadedFiles;
    
    if (!is_dir($localPath)) {
        echo "❌ Directorio no encontrado: $localPath\n";
        return;
    }
    
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($localPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    
    foreach ($files as $file) {
        if (!$file->isFile() || $file->getFilename() === '.gitkeep') {
            continue;
        }
        
        $filePath = $file->getPathname();
        $relativePath = str_replace($localPath . DIRECTORY_SEPARATOR, '', $filePath);
        $relativePath = str_replace('\\', '/', $relativePath);
        
        // Nombre público en Cloudinary (sin extensión)
        $publicId = $cloudinaryFolder . '/' . pathinfo($relativePath, PATHINFO_FILENAME);
        
        try {
            echo "📤 Subiendo: $relativePath ... ";
            
            $result = $cloudinary->uploadApi()->upload($filePath, [
                'public_id' => $publicId,
                'folder' => dirname($cloudinaryFolder . '/' . $relativePath),
                'resource_type' => 'auto', // Detecta automáticamente si es imagen, video, o archivo
                'overwrite' => true
            ]);
            
            echo "✅ OK\n";
            echo "   URL: " . $result['secure_url'] . "\n\n";
            
            $uploadedFiles[$relativePath] = $result['secure_url'];
            
        } catch (Exception $e) {
            echo "❌ ERROR\n";
            echo "   " . $e->getMessage() . "\n\n";
        }
    }
}

// Subir libros
echo "📚 === LIBROS ===\n";
uploadDirectory($cloudinary, $mediaPath . '/libros/imagenes', 'bookheaven/libros/imagenes');
uploadDirectory($cloudinary, $mediaPath . '/libros/pdfs', 'bookheaven/libros/pdfs');

// Subir mangas
echo "\n📖 === MANGAS ===\n";
uploadDirectory($cloudinary, $mediaPath . '/mangas/imagenes', 'bookheaven/mangas/imagenes');
uploadDirectory($cloudinary, $mediaPath . '/mangas/pdfs', 'bookheaven/mangas/pdfs');

// Subir comics
echo "\n🦸 === COMICS ===\n";
uploadDirectory($cloudinary, $mediaPath . '/comics/imagenes', 'bookheaven/comics/imagenes');
uploadDirectory($cloudinary, $mediaPath . '/comics/pdfs', 'bookheaven/comics/pdfs');

// Guardar el mapeo en un archivo JSON
$mappingFile = __DIR__ . '/cloudinary_urls.json';
file_put_contents($mappingFile, json_encode($uploadedFiles, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo "\n✅ ¡Subida completada!\n";
echo "📄 URLs guardadas en: cloudinary_urls.json\n";
echo "\n📊 Total de archivos subidos: " . count($uploadedFiles) . "\n\n";
