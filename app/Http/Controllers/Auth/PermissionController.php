<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionStoreRequest;
use App\Http\Resources\PermissionCollection;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function response;

class PermissionController extends Controller
{
    public function index(Request $request): Response
    {
        $permissions = Permission::all();

        return new PermissionCollection($permissions);
    }

    public function store(PermissionStoreRequest $request): Response
    {
        $permission = Permission::create($request->validated());

        return new PermissionResource($permission);
    }

    public function show(Request $request, Permission $permission): Response
    {
        return new PermissionResource($permission);
    }

    public function destroy(Request $request, Permission $permission): Response
    {
        $permission->delete();

        return response()->noContent();
    }
}
