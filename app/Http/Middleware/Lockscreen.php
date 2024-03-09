<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Lockscreen
{
    public function handle(Request $request, Closure $next): Response
    {
        $lockscreen = session()->get('lockscreen');
        if ($lockscreen) {
            return redirect()->route('user.lockscreen');
        }
        return $next($request);
    }
}