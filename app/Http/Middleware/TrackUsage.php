<?php

namespace App\Http\Middleware;

use App\Models\Tracking;
use Closure;

class TrackUsage
{
    public function handle($request, Closure $next)
    {

        Tracking::create([
            'ip_address' => substr($_SERVER['REMOTE_ADDR'], 0, 254),
            'agent' => substr($_SERVER['HTTP_USER_AGENT'], 0, 254),
            'url' => substr($_SERVER['REQUEST_URI'], 0, 254)

        ]);
        return $next($request);
    }
}
