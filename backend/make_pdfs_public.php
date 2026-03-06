<?php

require __DIR__ . '/vendor/autoload.php';

try {
    // Configurar Cloudinary
    putenv('CLOUDINARY_URL=cloudinary://829314572865755:k1rBNK0HcXEhFL-l9qMYTDVr38U@dnorihcmw');
    
    $cloudinary = new \Cloudinary\Cloudinary();
    
    $pdfs = [
        'bookheaven/libros/pdfs/bookheaven/libros/pdfs/principito',
        'bookheaven/libros/pdfs/bookheaven/libros/pdfs/1',
        'bookheaven/libros/pdfs/bookheaven/libros/pdfs/don_quijote',
        'bookheaven/libros/pdfs/bookheaven/libros/pdfs/senor_anillos',
        'bookheaven/libros/pdfs/bookheaven/libros/pdfs/harry_potter',
    ];
    
    echo "🔓 Haciendo PDFs públicos en Cloudinary...\n\n";
    
    foreach ($pdfs as $publicId) {
        try {
            echo "Procesando: $publicId\n";
            
            // Actualizar a tipo 'upload' público
            $result = $cloudinary->uploadApi()->explicit($publicId, [
                'type' => 'upload',
                'resource_type' => 'raw',
                'access_mode' => 'public'
            ]);
            
            echo "✅ Actualizado: {$result['secure_url']}\n\n";
        } catch (Exception $e) {
            echo "❌ Error con $publicId: {$e->getMessage()}\n\n";
        }
    }
    
    echo "✅ ¡Proceso completado!\n";
    
} catch (Exception $e) {
    echo "❌ Error general: {$e->getMessage()}\n";
    echo "Stack trace: {$e->getTraceAsString()}\n";
}
