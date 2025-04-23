<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! in_array($request->user()->role ?? '', $roles)) {
            abort(403, 'Ntiwemerewe aha!');
        }

        return $next($request);
    }
}
