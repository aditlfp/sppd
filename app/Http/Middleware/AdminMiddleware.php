<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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
            session()->flash('error', 'You are not authorized to access this page');
            return redirect()->route('login');

        }
        else{
            if(auth()->user()->role_id !== 2) {
                abort(403);
                session()->flash('error', 'You are not authorized to access this page');
                return redirect()->back();
            }
            return $next($request);
        }
    }
}