<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->adminAccess()->exists()) {
            
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Student access required.'
                ], 403);
            }

            abort(403, 'Student access required.');
        }
        return $next($request);
    }
}
