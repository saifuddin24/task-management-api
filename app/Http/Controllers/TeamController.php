<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;
use App\Http\Resources\TeamCollection;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TeamController extends Controller
{
    public function index(Request $request): Response
    {
        $teams = Team::all();

        return new TeamCollection($teams);
    }

    public function store(TeamStoreRequest $request): Response
    {
        $team = Team::create($request->validated());

        return new TeamResource($team);
    }

    public function show(Request $request, Team $team): Response
    {
        return new TeamResource($team);
    }

    public function update(TeamUpdateRequest $request, Team $team): Response
    {
        $team->update($request->validated());

        return new TeamResource($team);
    }

    public function destroy(Request $request, Team $team): Response
    {
        $team->delete();

        return response()->noContent();
    }
}
