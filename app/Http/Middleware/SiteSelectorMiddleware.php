<?php

namespace App\Http\Middleware;

use App\Models\Site;
use App\Services\Cache;
use Closure;
use Illuminate\Http\Request;

class SiteSelectorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
