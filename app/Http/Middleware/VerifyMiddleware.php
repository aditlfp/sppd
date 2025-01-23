<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check()){
            abort(403);
            flash('error', 'You are not authorized to access this page');
            return redirect()->route('login');
        }else{
            $this_auth = auth()->user();
            if(!in_array($this_auth->name, ['SULASNI', 'PARNO', 'DIREKTUR', 'DIREKTUR UTAMA', 'admin'])){
                abort(403);
                flash('error', 'You are not authorized to access this page');
                return redirect()->route('login');
            }
            return $next($request);
        }
    }
}
