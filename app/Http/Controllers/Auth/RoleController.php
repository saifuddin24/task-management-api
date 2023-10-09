<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\RoleStoreRequest;
use App\Http\Requests\RolePermission\RolePermissionAssignRequest;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function response;

class RoleController extends Controller
{
    protected array $authorized = [
        'role.read' => [ 'index','show' ],
        'role.create' => [ 'store' ],
        'role.update' => [ 'update' ],
        'role.delete' => [ 'destroy', 'restore' ],
    ];

    public function index(Request $request): RoleCollection
    {
        $roles = Role::query();

        $roles->when( $request->has('hide_md'), function (Builder $roles){
            $roles->whereNotIn('id', [1]);
        });

        return new RoleCollection($roles->get());
    }

    public function store(RoleStoreRequest $request): RoleResource
    {
        $role = Role::create($request->validated());

        return new RoleResource($role);
    }

    public function show(Request $request, Role $role): RoleResource
    {
        return new RoleResource($role);
    }

    public function destroy(Request $request, Role $role): Response
    {
        $role->delete();

        return response()->noContent();
    }

    public function permissions(RolePermissionAssignRequest $request, Role $role, array $permissions ):array{

        $role->permissions()->sync( $permissions );

        return compact('role', 'permissions' );
    }
}
