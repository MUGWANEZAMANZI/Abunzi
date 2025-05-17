<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
public function handle(Request $request, Closure $next)
{
    $locale = Session::get('locale', config('app.locale')); // Use default locale from config
    App::setLocale($locale);

    return $next($request);
}
}
