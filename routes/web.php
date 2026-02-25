<?php

use App\Http\Controllers;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//})->name('dashboard');
//
//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});
//
//require __DIR__.'/auth.php';

// Obtener la lista de idiomas válidos desde la configuración
$availableLocales = implode('|', array_keys(config('app.available_locales')));

// Rutas con prefijo de idioma
Route::prefix('{locale}')
//    ->where(['locale' => '[a-zA-Z]{2}']) // El prefijo debe ser de 2 letras (ej. 'en', 'es', 'fr')
    ->where(['locale' => $availableLocales])// Debe estar en available_locales
    ->group(function() {
        // Redirige a la ruta previa o a 'welcome' si no existe una previa
        Route::get('', function() {
            $previousRoute = Route::getRoutes()->match(
                Request::create(URL::previous())
            )->getName();

            return redirect()->route($previousRoute ?? 'home');
        });

        // Ruta para 'home'
        Route::get('home', [Controllers\ProductController::class, 'home'])
            ->name('home');
        Route::get('products/{product:slug}', [Controllers\ProductController::class, 'show'])
            ->name('products.show');

        // Rutas protegidas con autenticación
        Route::middleware('auth')->group(function() {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });

        require __DIR__.'/auth.php';
    });

// Ruta de fallback para redirigir a 'welcome'
Route::fallback(function() {
    return redirect()->route('home');
});
