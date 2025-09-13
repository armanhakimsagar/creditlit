<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSectionPermission
{
    public function handle($request, Closure $next, $permission)
    {
        $user = Auth::guard('web')->user();

        if (is_null($user) || !$user->can($permission)) {
            abort(403, 'Sorry !! You are Unauthorized to view this page !');
        }

        return $next($request);
    }
}
