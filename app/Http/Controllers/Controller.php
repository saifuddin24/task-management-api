<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected array $authorized = [];

    public function __construct()
    {
        foreach ($this->authorized as $middleware => $methods){
            $this->middleware('abilities:'.$middleware )->only( $methods );
        }
    }

}
