<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamProject\TeamProjectStoreRequest;
use App\Http\Requests\TeamProject\TeamProjectUpdateRequest;
use App\Http\Resources\TeamProjectCollection;
use App\Http\Resources\TeamProjectResource;
use App\Models\TeamProject;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TeamProjectController extends Controller
{
    public function index(Request $request): Response
    {
        $teamProjects = TeamProject::all();

        return new TeamProjectCollection($teamProjects);
    }

    public function store(TeamProjectStoreRequest $request): TeamProjectResource
    {
        $teamProject = TeamProject::create($request->validated());

        return new TeamProjectResource($teamProject);
    }

    public function show(Request $request, TeamProject $teamProject): Response
    {
        return new TeamProjectResource($teamProject);
    }

    public function update(TeamProjectUpdateRequest $request, TeamProject $teamProject): Response
    {
        $teamProject->update($request->validated());

        return new TeamProjectResource($teamProject);
    }

    public function destroy(Request $request, TeamProject $teamProject): Response
    {
        $teamProject->delete();

        return response()->noContent();
    }
}
