<?php

require __DIR__ . '/vendor/autoload.php';

try {
    // Configurar Cloudinary con variables de entorno
    putenv('CLOUDINARY_URL=cloudinary://829314572865755:k1rBNK0HcXEhFL-l9qMYTDVr38U@dnorihcmw');
    
    // Inicializar Cloudinary
    $cloudinary = new \Cloudinary\Cloudinary();
    
    $localPath = __DIR__ . '/public/storage/media/comics/imagenes/sand.png';
    
    if (!file_exists($localPath)) {
        die("❌ Error: Archivo no encontrado en: $localPath\n");
    }
    
    echo "📤 Subiendo sand.png a Cloudinary...\n";
    echo "Archivo: $localPath\n";
    echo "Tamaño: " . round(filesize($localPath) / 1024 / 1024, 2) . " MB\n\n";
    
    // Subir a Cloudinary
    $result = $cloudinary->uploadApi()->upload($localPath, [
        'folder' => 'bookheaven/comics/imagenes',
        'public_id' => 'sand',
        'resource_type' => 'image',
        'overwrite' => true
    ]);
    
    echo "✅ ¡Imagen subida exitosamente!\n\n";
    echo "URL de Cloudinary:\n";
    echo $result['secure_url'] . "\n\n";
    
    echo "Ahora actualiza la base de datos en Railway con esta URL:\n";
    echo "UPDATE comics SET imagen = '{$result['secure_url']}' WHERE titulo = 'The Sandman';\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
