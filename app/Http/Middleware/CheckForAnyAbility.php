<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility as OnlyAbility;

use Illuminate\Auth\AuthenticationException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;

class CheckForAnyAbility extends OnlyAbility
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
            if ($request->user()->tokenCan($ability)) {
                return $next($request);
            }
        }

        throw new MissingAbilityException($abilities);
    }
}
