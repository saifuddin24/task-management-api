<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\ProjectIndexRequest;
use App\Http\Requests\Project\ProjectShowRequest;
use App\Http\Requests\Project\ProjectStoreRequest;
use App\Http\Requests\Project\ProjectUpdateRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JetBrains\PhpStorm\Pure;

class ProjectController extends Controller
{
    protected array $authorized = [
        'project.read' => [ 'index','show' ],
        'project.create' => [ 'store' ],
        'project.update' => [ 'update' ],
        'project.delete' => [ 'destroy', 'restore' ],
    ];

    public function index(ProjectIndexRequest $request): ProjectCollection
    {

        $projects = Project::query()->with( $request->relations([]) );
        $projects->orderBy('id','DESC');

        $projects = $projects->paginate();
        TaskResource::withoutWrapping();

        return new ProjectCollection($projects);
    }

    public function store(ProjectStoreRequest $request): ProjectResource|array
    {

        $project = Project::query()->create($request->validated());

        return new ProjectResource($project);
    }

    #[Pure] public function show(ProjectShowRequest $request, Project $project): ProjectResource
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
        $trashed_project->restore();
        return new ProjectResource($trashed_project);
    }
}
