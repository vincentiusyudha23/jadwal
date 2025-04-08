<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if(!Auth::check()){
            if($request->segment(1) == 'admin'){
                return redirect()->route('first_page');
            }

            return redirect()->route('firstpage_karyawan');
        }

        if(Auth::check() && !Auth::user()->hasRole($role)){
            return redirect()->back()->with('failed.role', 'Anda tidak punya akses ke halaman '.$role);
        }
        return $next($request);
    }
}
