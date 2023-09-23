<?php

namespace App\Http\Controllers;

use App\Http\Requests\Indexes\IndexRequest;
use App\Http\Requests\Indexes\ProjectIndexRequest;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function index(ProjectIndexRequest $request): ProjectCollection
    {

        $projects = Project::query()->with( $request->relations() )->paginate();

        TaskResource::withoutWrapping();

        return new ProjectCollection($projects);
    }

    public function store(ProjectStoreRequest $request): ProjectResource
    {


        $project = Project::create($request->validated());

        return new ProjectResource($project);
    }

    public function show(Request $request, Project $project): ProjectResource
    {
        return new ProjectResource($project);
    }

    public function update(ProjectUpdateRequest $request, Project $project): ProjectResource
    {
        $project->update($request->validated());
        return new ProjectResource($project);
    }

    public function destroy(Request $request, Project $project): Response
    {
        $project->delete();

        return response()->noContent();
    }

    public function restore(Project $trashed_project):ProjectResource
    {
//        dd($trashed_project);
        $trashed_project->restore();
        return new ProjectResource($trashed_project);
    }
}
