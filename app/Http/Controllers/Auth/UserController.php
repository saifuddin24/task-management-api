<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Indexes\IndexRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function bcrypt;
use function response;

class UserController extends Controller
{
    public function index(IndexRequest $request): UserCollection
    {

        $valid_relations = [
            'primary_role' => [ 'role.read', function($role){
                $role->select('id','name');
            }],
            'roles' => 'role.read',
            'permissions' => 'permission.read',
            'projects' => 'projects.read',
        ];

        $relations = $request->relations( $valid_relations );

        $users = User::with( $relations );

        return new UserCollection($users->paginate());
    }

    public function store(UserStoreRequest $request): UserResource
    {
        $userData = $request->validated();

        $userData['password'] = bcrypt($userData['password']);

        $user = User::create($userData);

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

    public function login(LoginRequest $request){

        if($request->user()) {
            return (new UserResource($request->user()))->additional([
                'already_logged_in' => true,
                'token' => '',
            ]);
        }

        $request->authenticate();

        $user = $request->getAuthenticateUser();

        $primary_role = $user->primary_role( )->first( );
        $another_roles = $user->roles( )->get( );

        $scopes = $primary_role->permissions()->pluck('title')->all();

        $another_roles->each(function ($role) use (&$scopes) {
            $scopes = [ ...$scopes, ...$role->permissions()->pluck('title')->all() ];
        });

        $scopes = array_values([ ...$scopes, ...$user->permissions()->pluck('title')->all()]);

        $token = $user->createToken( $request->ip(), $scopes )->plainTextToken;

        return (new UserResource($user))->additional([
            'already_logged_in' => false,
            'token' => $token
        ]);

    }
}
