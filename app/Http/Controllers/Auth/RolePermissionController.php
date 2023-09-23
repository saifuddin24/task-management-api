<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolePermissionStoreRequest;
use App\Http\Resources\RolePermissionCollection;
use App\Http\Resources\RolePermissionResource;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function response;

class RolePermissionController extends Controller
{
    public function index(Request $request): Response
    {
        $rolePermissions = RolePermission::all();

        return new RolePermissionCollection($rolePermissions);
    }

    public function store(RolePermissionStoreRequest $request): Response
    {
        $rolePermission = RolePermission::create($request->validated());

        return new RolePermissionResource($rolePermission);
    }

    public function destroy(Request $request, RolePermission $rolePermission): Response
    {
        $rolePermission->delete();

        return response()->noContent();
    }
}
