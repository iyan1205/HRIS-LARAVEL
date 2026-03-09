<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckSingleSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {

            $user = Auth::user();

            // jika session berbeda berarti login di device lain
            if ($user->session_id && $user->session_id !== Session::getId()) {

                Auth::logout();

                Session::flush();

                return redirect('/login')
                    ->with('error','Akun Anda login di perangkat lain.');
            }
        }

        return $next($request);
    }
}