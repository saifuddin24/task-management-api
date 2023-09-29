<?php

namespace App\Exceptions;

use App\Models\Project;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Str;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {



        if( $e instanceof AuthorizationException || $e instanceof MissingAbilityException) {
            return response([
                'message' => 'This action is unauthorized!',
                'unauthorized' => true
            ],
                403
            );
        }


        if( $e instanceof AuthenticationException ) {

            return response([
                'message' => $e->getMessage(),
                'loggedOut' => true
            ],
                401
            );
        }

        if ($e instanceof ModelNotFoundException && $request->wantsJson() )
        {
            $modelName = preg_replace(
                "/.+[\\\\$]/",
                "",$e->getModel(),
                1
            );

            return response(
                [
                    'message' =>
                        $modelName
                            ? 'Requested ' . Str::snake($modelName,' ') . ' not found'
                            : $e->getMessage(),
                    'success' => false
                ],
                404
            );
        }

        return parent::render($request, $e );
    }

}
