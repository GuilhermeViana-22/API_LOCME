<?php

namespace App\Http\Middleware;

use App\Exceptions\MissingTokenException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->bearerToken()) {
            throw new MissingTokenException();
        }

        return $next($request);
    }
}