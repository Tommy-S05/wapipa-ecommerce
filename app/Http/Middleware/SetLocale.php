<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $availableLocales = array_keys(config('app.available_locales'));

        // Verificar si el primer segmento de la URL es un idioma válido
        if (
            in_array($request->segment(1), $availableLocales) &&
            $request->segment(1) !== App::currentLocale()
        ) {
            // Guardar el idioma en la sesión si es diferente al actual
            Session::put('locale', $request->segment(1));
        }

        // Establecer el idioma actual desde la sesión o usar el idioma predeterminado
        App::setLocale(Session::get('locale', App::currentLocale()));

        // Establecer el idioma en las URLs generadas por Laravel
        URL::defaults(['locale' => App::currentLocale() ?? config('app.locale')]);

        return $next($request);
    }
}
