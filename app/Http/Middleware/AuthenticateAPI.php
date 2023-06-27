<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAPI
{
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::guard('sanctum')->guest()){
            return response()->json(['message' => 'Unauthorized'],401);
        }

        return $next($request);
    }
}
