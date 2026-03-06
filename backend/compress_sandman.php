<?php

$sourcePath = __DIR__ . '/public/storage/media/comics/imagenes/sand.png';
$outputPath = __DIR__ . '/public/storage/media/comics/imagenes/sand_compressed.png';

if (!file_exists($sourcePath)) {
    die("❌ Error: Archivo no encontrado\n");
}

echo "📦 Comprimiendo sand.png...\n";
echo "Original: " . round(filesize($sourcePath) / 1024 / 1024, 2) . " MB\n";

// Cargar imagen
$image = imagecreatefrompng($sourcePath);
if (!$image) {
    die("❌ Error: No se pudo cargar la imagen\n");
}

// Comprimir y guardar (0-9, donde 9 es máxima compresión)
imagepng($image, $outputPath, 9);
imagedestroy($image);

echo "Comprimida: " . round(filesize($outputPath) / 1024 / 1024, 2) . " MB\n";

if (filesize($outputPath) > 10 * 1024 * 1024) {
    echo "⚠️ Aún es mayor a 10 MB, intentando reducir dimensiones...\n";
    
    // Reducir dimensiones si aún es muy grande
    $image = imagecreatefrompng($outputPath);
    $width = imagesx($image);
    $height = imagesy($image);
    
    // Reducir a 80% del tamaño original
    $newWidth = $width * 0.8;
    $newHeight = $height * 0.8;
    
    $resized = imagecreatetruecolor($newWidth, $newHeight);
    imagealphablending($resized, false);
    imagesavealpha($resized, true);
    
    imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    imagepng($resized, $outputPath, 9);
    
    imagedestroy($image);
    imagedestroy($resized);
    
    echo "Redimensionada: " . round(filesize($outputPath) / 1024 / 1024, 2) . " MB\n";
}

echo "✅ ¡Imagen comprimida exitosamente!\n";
echo "Archivo: $outputPath\n";
