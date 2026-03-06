<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ContentController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\DebugController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\ReadingAnalyticsController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\DebugDashboardController;

// Rutas públicas
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/check-email', [AuthController::class, 'checkEmail']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

// Debug endpoint (dev only)
Route::middleware('optional.auth')->get('/debug/auth', [DebugController::class, 'checkAuth']);

// Obtener planes de precios (público)
Route::get('/payments/plans', [PaymentController::class, 'getPricingPlans']);

// 🆕 ENDPOINT UNIFICADO - Obtener contenido de todas las categorías
Route::get('/content/unified', [ContentController::class, 'getUnified']);

// 🆕 ENDPOINT PREMIUM - Contenido premium (requiere admin)
Route::middleware('auth:sanctum')->get('/content/premium', [ContentController::class, 'getPremiumContent']);

// Reseñas (Públicas/Lectura)
Route::get('/reviews', [ReviewController::class, 'index']);

// Obtener contenido - Autenticación opcional via middleware 'optional.auth'
Route::middleware('optional.auth')->get('/content/libros', [ContentController::class, 'getLibros']);
Route::middleware('optional.auth')->get('/content/libros/{libro}', [ContentController::class, 'getLibro'])->missing(function () {
    return response()->json(['message' => 'Libro no encontrado'], 404);
});

Route::middleware('optional.auth')->get('/content/mangas', [ContentController::class, 'getMangas']);
Route::middleware('optional.auth')->get('/content/mangas/{manga}', [ContentController::class, 'getManga'])->missing(function () {
    return response()->json(['message' => 'Manga no encontrado'], 404);
});

Route::middleware('optional.auth')->get('/content/comics', [ContentController::class, 'getComics']);
Route::middleware('optional.auth')->get('/content/comics/{comic}', [ContentController::class, 'getComic'])->missing(function () {
    return response()->json(['message' => 'Cómic no encontrado'], 404);
});

Route::middleware('optional.auth')->get('/content/audiolibros', [ContentController::class, 'getAudiobooks']);
Route::middleware('optional.auth')->get('/content/audiolibros/{audiobook}', [ContentController::class, 'getAudiobook'])->missing(function () {
    return response()->json(['message' => 'Audiolibro no encontrado'], 404);
});

// Servir archivos (PDFs y audios) - Sin autenticación requerida (pero con acceso a datos de usuario si está autenticado)
Route::middleware('optional.auth')->get('/content/serve-pdf/{type}/{id}', [ContentController::class, 'servePDF'])
    ->name('api.content.serve-pdf');
Route::middleware('optional.auth')->get('/content/serve-audio/{id}', [ContentController::class, 'serveAudio'])
    ->name('api.content.serve-audio');

// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {
    
    // Autenticación
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::put('/auth/demographics', [AuthController::class, 'updateDemographics']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
    Route::post('/auth/profile-photo', [AuthController::class, 'uploadProfilePhoto']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword']);
    Route::get('/user/stats', [AuthController::class, 'getUserStats']);

    // Contenido - crear y editar
    Route::post('/content/libros', [ContentController::class, 'createLibro']);
    Route::put('/content/libros/{libro}', [ContentController::class, 'updateLibro']);
    Route::delete('/content/libros/{libro}', [ContentController::class, 'deleteLibro']);

    Route::post('/content/mangas', [ContentController::class, 'createManga']);
    Route::put('/content/mangas/{manga}', [ContentController::class, 'updateManga']);
    Route::delete('/content/mangas/{manga}', [ContentController::class, 'deleteManga']);

    Route::post('/content/comics', [ContentController::class, 'createComic']);
    Route::put('/content/comics/{comic}', [ContentController::class, 'updateComic']);
    Route::delete('/content/comics/{comic}', [ContentController::class, 'deleteComic']);

    Route::post('/content/audiolibros', [ContentController::class, 'createAudiobook']);
    Route::put('/content/audiolibros/{audiobook}', [ContentController::class, 'updateAudiobook']);
    Route::delete('/content/audiolibros/{audiobook}', [ContentController::class, 'deleteAudiobook']);

    // Pagos
    Route::post('/payments/initiate-premium', [PaymentController::class, 'initiatePremiumPayment']);
    Route::post('/payments/complete/{payment}', [PaymentController::class, 'completePayment'])->name('api.payment.complete');
    Route::get('/payments/history', [PaymentController::class, 'getUserPayments']);
    Route::post('/payments/cancel/{payment}', [PaymentController::class, 'cancelPayment']);

    // Reseñas (Autenticadas)
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);

    // Favoritos (Mi Lista)
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle']);
    Route::delete('/favorites/{type}/{id}', [FavoriteController::class, 'destroy']);

    // Admin - Dashboard y estadísticas
    Route::middleware('role:admin')->group(function () {
        // 🆕 Dashboard Optimizado - Todos los datos en una respuesta
        Route::get('/admin/dashboard', [AdminController::class, 'getDashboard']);
        
        // 📊 Dashboard stats con estadísticas demográficas
        Route::get('/admin/dashboard-stats', [DashboardController::class, 'stats']);
        
        // 📚 Análisis de lectura por datos demográficos
        Route::get('/admin/reading-analytics', [ReadingAnalyticsController::class, 'compare']);
        Route::get('/admin/reading-analytics/by-gender', [ReadingAnalyticsController::class, 'byGender']);
        Route::get('/admin/reading-analytics/by-age', [ReadingAnalyticsController::class, 'byAge']);
        Route::get('/admin/reading-analytics/by-country', [ReadingAnalyticsController::class, 'byCountry']);
        Route::get('/admin/reading-analytics/by-user-type', [ReadingAnalyticsController::class, 'byUserType']);
        Route::get('/admin/reading-analytics/monthly-trends', [ReadingAnalyticsController::class, 'monthlyTrends']);
        Route::get('/admin/reading-analytics/popular-content', [ReadingAnalyticsController::class, 'popularContent']);
        
        // Dashboard Stats Legacy (mantener para compatibilidad)
        Route::get('/admin/stats', [AdminController::class, 'getDashboardStats']);
        
        // Gestión de Usuarios - CRUD Completo
        Route::get('/admin/users', [UserController::class, 'index']);
        Route::get('/admin/users/{user}', [UserController::class, 'show']);
        Route::post('/admin/users', [UserController::class, 'store']);
        Route::put('/admin/users/{user}', [UserController::class, 'update']);
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy']);
        Route::get('/admin/roles', [UserController::class, 'getRoles']);

        Route::get('/admin/payments', [AdminController::class, 'getPaymentHistory']);
        Route::get('/admin/activity-logs', [AdminController::class, 'getActivityLog']);

        Route::put('/admin/roles/{role}/permissions', [AdminController::class, 'manageRolePermissions']);

        Route::get('/admin/content/stats', [ContentController::class, 'getContentStats']);
        
        // 🆕 Estadísticas de contenido PREMIUM
        Route::get('/admin/content/premium-stats', [AdminController::class, 'getPremiumContentStats']);

        // Reportes
        Route::get('/admin/export/pdf', [AdminController::class, 'exportPDF']);
        Route::get('/admin/export/excel', [AdminController::class, 'exportExcel']);
    });
});

// Rutas públicas para obtener foto de perfil (con sanitización)
Route::get('/user/profile-photo/{user}', function (User $user) {
    if (!$user->profile_photo_path) {
        abort(404, 'Foto no encontrada');
    }
    
    $path = storage_path('app/' . $user->profile_photo_path);
    if (!file_exists($path)) {
        abort(404, 'Archivo no encontrado');
    }
    
    return response()->file($path);
})->name('api.user.profile-photo');

// 🧪 DEBUG - SOLO DESARROLLO - Generar datos de prueba en el dashboard
if (config('app.env') !== 'production') {
    Route::prefix('debug/dashboard')->group(function () {
        Route::get('/seed', [DebugDashboardController::class, 'seedData'])
            ->name('debug.dashboard.seed')
            ->withoutMiddleware('throttle');
        
        Route::get ('/clear', [DebugDashboardController::class, 'clearData'])
            ->name('debug.dashboard.clear')
            ->withoutMiddleware('throttle');
        
        Route::get('/stats', [DebugDashboardController::class, 'getStats'])
            ->name('debug.dashboard.stats')
            ->withoutMiddleware('throttle');
    });
}

// Ruta temporal de limpieza de base de datos (REMOVER DESPUES DE USAR)
Route::get('/admin/cleanup-database', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('db:cleanup');
        $output = \Illuminate\Support\Facades\Artisan::output();
        return response()->json([
            'success' => true,
            'message' => 'Limpieza ejecutada correctamente',
            'output' => $output
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
})->name('admin.cleanup.database');

// Ruta temporal para reseed con URLs de Cloudinary (REMOVER DESPUES DE USAR)
Route::get('/admin/reseed-content', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('content:reseed');
        $output = \Illuminate\Support\Facades\Artisan::output();
        return response()->json([
            'success' => true,
            'message' => 'Reseed ejecutado correctamente',
            'output' => $output
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
})->name('admin.reseed.content');

// Ruta temporal para actualizar URLs de PDFs (REMOVER DESPUES DE USAR)
Route::get('/admin/update-pdf-urls', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('content:update-pdfs');
        $output = \Illuminate\Support\Facades\Artisan::output();
        return response()->json([
            'success' => true,
            'message' => 'URLs de PDFs actualizadas correctamente',
            'output' => $output
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
})->name('admin.update.pdfs');

// Ruta temporal para actualizar URLs de PDFs rápido con SQL (REMOVER DESPUES DE USAR)
Route::get('/admin/update-pdf-urls-quick', function () {
    try {
        $librosUpdated = 0;
        $mangasUpdated = 0;
        $comicsUpdated = 0;
        
        // Actualizar libros con Eloquent
        $libros = [
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
        
        foreach ($libros as $titulo => $pdfUrl) {
            $libro = \App\Models\Libro::where('titulo', $titulo)->first();
            if ($libro) {
                $libro->pdf = $pdfUrl;
                $libro->save();
                $librosUpdated++;
            }
        }
        
        // Actualizar mangas con Eloquent
        $mangas = [
            'Naruto' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776271/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/Naruto.pdf',
            'One Piece' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776277/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/one.pdf',
            'Death Note' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776268/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/death.pdf',
            'Blue Lock' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776264/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/blue_lock.pdf',
            'Demon Slayer' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776245/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/demon_slayer.pdf',
            'Attack on Titan' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776247/bookheaven/mangas/pdfs/bookheaven/mangas/pdfs/attack.pdf',
        ];
        
        foreach ($mangas as $titulo => $pdfUrl) {
            $manga = \App\Models\Manga::where('titulo', $titulo)->first();
            if ($manga) {
                $manga->pdf = $pdfUrl;
                $manga->save();
                $mangasUpdated++;
            }
        }
        
        // Actualizar comics con Eloquent
        $comics = [
            'Watchmen' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776423/bookheaven/comics/pdfs/bookheaven/comics/pdfs/watchmen.pdf',
            'Batman: The Killing Joke' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776396/bookheaven/comics/pdfs/bookheaven/comics/pdfs/batman_dark_knight.pdf',
            'Maus' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776403/bookheaven/comics/pdfs/bookheaven/comics/pdfs/maus.pdf',
            'V de Vendetta' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776419/bookheaven/comics/pdfs/bookheaven/comics/pdfs/v_vendetta.pdf',
            'the amazing spiderman' => 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776400/bookheaven/comics/pdfs/bookheaven/comics/pdfs/spiderman_kraven.pdf',
        ];
        
        foreach ($comics as $titulo => $pdfUrl) {
            $comic = \App\Models\Comic::where('titulo', $titulo)->first();
            if ($comic) {
                $comic->pdf = $pdfUrl;
                $comic->save();
                $comicsUpdated++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'URLs de PDFs actualizadas correctamente',
            'libros_updated' => $librosUpdated,
            'mangas_updated' => $mangasUpdated,
            'comics_updated' => $comicsUpdated
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'line' => $e->getLine()
        ], 500);
    }
})->name('admin.update.pdfs.quick');
// Ruta de prueba simple con Eloquent
Route::get('/admin/test-pdf-update', function () {
    try {
        $libro = \App\Models\Libro::where('titulo', 'El Principito')->first();
        if ($libro) {
         $libro->pdf = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776222/bookheaven/libros/pdfs/bookheaven/libros/pdfs/principito.pdf';
            $libro->save();
            return response()->json([
                'success' => true,
                'message' => 'PDF actualizado',
                'libro' => $libro
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Libro no encontrado'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// Ruta temporal para corregir imagen de Sandman (REMOVER DESPUES DE USAR)
Route::get('/admin/fix-sandman-image', function () {
    try {
        $sandman = \App\Models\Comic::where('titulo', 'The Sandman')->first();
        if ($sandman) {
            $sandman->imagen = 'https://res.cloudinary.com/dnorihcmw/image/upload/v1772776387/bookheaven/comics/imagenes/sand.png';
            $sandman->save();
            return response()->json([
                'success' => true,
                'message' => 'Imagen de Sandman actualizada correctamente',
                'comic' => $sandman
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Sandman no encontrado'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// Ruta temporal para subir sand.png a Cloudinary (REMOVER DESPUES DE USAR)
Route::get('/admin/upload-sandman-image', function () {
    try {
        $localPath = public_path('storage/media/comics/imagenes/sand.png');
        
        if (!file_exists($localPath)) {
            return response()->json([
                'success' => false,
                'error' => 'Archivo sand.png no encontrado en: ' . $localPath
            ], 404);
        }
        
        // Subir a Cloudinary
        $cloudinary = new \Cloudinary\Cloudinary();
        $result = $cloudinary->uploadApi()->upload($localPath, [
            'folder' => 'bookheaven/comics/imagenes',
            'public_id' => 'sand',
            'resource_type' => 'image'
        ]);
        
        // Actualizar base de datos
        $sandman = \App\Models\Comic::where('titulo', 'The Sandman')->first();
        if ($sandman) {
            $sandman->imagen = $result['secure_url'];
            $sandman->save();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Imagen subida y actualizada correctamente',
            'cloudinary_url' => $result['secure_url'],
            'comic' => $sandman
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});



