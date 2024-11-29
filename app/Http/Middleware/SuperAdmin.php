<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if($user && $user->isSuperAdmin())
        {
            return $next($request);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
