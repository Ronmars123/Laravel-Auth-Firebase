<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFirebaseAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('firebase_user')) {
            return redirect('/login');
        }
        return $next($request);
    }
}
