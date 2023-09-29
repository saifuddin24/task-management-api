<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\PermissionStoreRequest;
use App\Http\Resources\PermissionCollection;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function response;

class PermissionController extends Controller
{
    protected array $authorized = [
        'permission.read' => [ 'index','show' ],
        'permission.create' => [ 'store' ],
        'permission.update' => [ 'update' ],
        'permission.delete' => [ 'destroy', 'restore' ],
    ];

    public function index(Request $request): PermissionCollection
    {
        $permissions = Permission::all();

        return new PermissionCollection($permissions);
    }

    public function store(PermissionStoreRequest $request): PermissionResource
    {
        $permission = Permission::create($request->validated());

        return new PermissionResource($permission);
    }

    public function show(Request $request, Permission $permission): PermissionResource
    {
        return new PermissionResource($permission);
    }

    public function destroy(Request $request, Permission $permission): Response
    {
        $permission->delete();

        return response()->noContent();
    }
}
