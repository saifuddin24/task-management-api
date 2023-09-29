<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserPermission\UserPermissionStoreRequest;
use App\Http\Resources\UserPermissionCollection;
use App\Http\Resources\UserPermissionResource;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function response;

class UserPermissionController extends Controller
{
    public function index(Request $request): Response
    {
        $userPermissions = UserPermission::all();

        return new UserPermissionCollection($userPermissions);
    }

    public function store(UserPermissionStoreRequest $request): Response
    {
        $userPermission = UserPermission::create($request->validated());

        return new UserPermissionResource($userPermission);
    }

    public function destroy(Request $request, UserPermission $userPermission): Response
    {
        $userPermission->delete();

        return response()->noContent();
    }
}
