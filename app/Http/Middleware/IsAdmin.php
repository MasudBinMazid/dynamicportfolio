<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->to('/admin')->with('error', 'Please log in.');
        }
        if (!Auth::user()->is_admin) {
            Auth::logout();
            return redirect()->to('/admin')->with('error', 'Not authorized.');
        }
        return $next($request);
    }
}
