<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Laravel\Sanctum\Http\Middleware\CheckAbilities as Abilities;
use Illuminate\Auth\AuthenticationException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;

class CheckAbilities extends Abilities
{

    public function handle($request, $next, ...$abilities)
    {
        if (! $request->user() || ! $request->user()->currentAccessToken()) {
            throw new AuthenticationException;
        }

        if( Role::isSuperAdmin( $request->user()->role_id ) && $request->user()->id ===1 ) {
            return $next($request);
        }

        foreach ($abilities as $ability) {
            if (! $request->user()->tokenCan($ability)) {
                throw new MissingAbilityException($ability);
            }
        }

        return $next($request);
    }
}
