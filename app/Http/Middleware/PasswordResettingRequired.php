<?php

namespace App\Http\Middleware;

use App\Exceptions\PasswordResettingRequiredException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PasswordResettingRequired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user() instanceof User) {
            if( $request->user()->required_password_resetting == 1 ) {

                throw new PasswordResettingRequiredException();
            }
        }

        return $next($request);
    }



}
