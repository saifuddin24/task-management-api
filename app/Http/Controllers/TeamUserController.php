<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamUser\TeamUserStoreRequest;
use App\Http\Resources\TeamUserCollection;
use App\Http\Resources\TeamUserResource;
use App\Models\TeamUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TeamUserController extends Controller
{
    public function index(Request $request): Response
    {
        $teamUsers = TeamUser::all();

        return new TeamUserCollection($teamUsers);
    }

    public function store(TeamUserStoreRequest $request): Response
    {
        $teamUser = TeamUser::create($request->validated());

        return new TeamUserResource($teamUser);
    }

    public function destroy(Request $request, TeamUser $teamUser): Response
    {
        $teamUser->delete();

        return response()->noContent();
    }
}
