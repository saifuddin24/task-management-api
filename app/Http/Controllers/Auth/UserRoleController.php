<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRrole\UserRoleStoreRequest;
use App\Http\Resources\UserRoleCollection;
use App\Http\Resources\UserRoleResource;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function response;

class UserRoleController extends Controller
{
    public function index(Request $request): Response
    {
        $userRoles = UserRole::all();

        return new UserRoleCollection($userRoles);
    }

    public function store(UserRoleStoreRequest $request): Response
    {
        $userRole = UserRole::create($request->validated());

        return new UserRoleResource($userRole);
    }

    public function destroy(Request $request, UserRole $userRole): Response
    {
        $userRole->delete();

        return response()->noContent();
    }
}
