<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function bcrypt;
use function response;

class UserController extends Controller
{
    protected array $authorized = [
        'user.read' => [ 'index','show' ],
        'user.create' => [ 'store' ],
        'user.update' => [ 'update' ],
        'user.delete' => [ 'destroy', 'restore' ],
    ];

    public function index(IndexRequest $request): UserCollection|array
    {

        $valid_relations = [
            'primary_role' => 'role.read',
            'roles' => 'role.read',
            'permissions' => 'permission.read',
            'projects' => 'projects.read',
        ];

        $relations = $request->relations( $valid_relations );

        $users = User::with( $relations );

        $users->when($request->get('role-level'), function ($query, $level){
            $query->whereHas('primary_role', function ($primary_role) use ($level){
                $primary_role->level($level);
            });
        });

        return new UserCollection($users->paginate());
    }

    public function store(UserStoreRequest $request): UserResource
    {
        $userData = $request->validated();

        $userData['password'] = bcrypt($userData['password']);

        $user = User::query()->create( $userData );

        return new UserResource($user);
    }

    public function show(Request $request, User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        $user->update($request->validated());

        return new UserResource($user);
    }

    public function destroy(Request $request, User $user): Response
    {
        $user->delete();
        return response()->noContent();
    }


    public function profile(Request $request){
        return new UserResource($request->user());
    }

    public function update_profile(Request $request){
        //return new UserResource($request->user());
    }

    public function logout( Request $request ): Response
    {
        if( $request->user() ) {
            $request->user()->tokens()->delete();
        }
        return response()->noContent();
    }

    public function login( LoginRequest $request )
    {

        if ($request->user()) {
            return response([
                'user' => UserResource::make($request->user()),
                'already_logged_in' => true,
                'token' => '',
                'message' => 'Already logged in by the user',
            ], 400);
        }

        $request->authenticate();

        $user = $request->getAuthenticateUser();

        $user->load(['primary_role']);

        if(($user->primary_role->id ?? '') == 1){
            $scopes = ["*"];
        }else{
            $user->load(['roles']);

            $primary_role = $user->primary_role;
            $another_roles = $user->roles;

            $scopes = $primary_role->permissions()->pluck('title')->all();

            $another_roles->each(function ($role) use (&$scopes) {
                $scopes = [...$scopes, ...$role->permissions()->pluck('title')->all()];
            });

            $scopes = array_values([...$scopes, ...$user->permissions()->pluck('title')->all()]);
        }

        $token = $user->createToken( $request->ip(), $scopes )->plainTextToken;

        return (new UserResource($user))->additional([
            'already_logged_in' => false,
            'token' => $token,
            'message' => 'Login Successful!',
        ]);

    }
}
