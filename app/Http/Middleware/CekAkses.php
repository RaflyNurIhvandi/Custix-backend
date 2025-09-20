<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CekAkses
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();

        if (!$user || $user->akses !== $role) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses ke fitur ini.'
            ]);
        }

        return $next($request);
    }
}
