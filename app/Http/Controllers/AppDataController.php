<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AppDataController extends Controller
{

    public function index(Request $request):Response|string|array
    {

        $user = $request->user();

        if( $user instanceof User ) {
            $user->load(['primary_role']);
        }

        return response([
            'user' => $user ? UserResource::make($user):null,
            'is_logged_in' => (bool) $user,
        ]);
    }

}
