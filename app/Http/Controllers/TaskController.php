<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\TaskIndexRequest;
use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Http\Requests\TaskActivity\TaskActivityShowRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    protected array $authorized = [
        'task.read' => [ 'index','show' ],
        'task.create' => [ 'store' ],
        'task.update' => [ 'update' ],
        'task.delete' => [ 'destroy', 'restore' ],
    ];

    public function index(TaskIndexRequest $request): TaskCollection
    {
        $tasks = Task::query( )
            ->withMax('task_activities as progress_percentage','progress_percentage')
            ->with(
                [
                    ...$request->relations([]),
                    ...['project']
                ]
            );

        $tasks->when(
            $request->has('project-id'),
            function ($task, $project_id) use ($request){
                $task->where(
                    'project_id',
                    $request->get( 'project-id' )
                );
            }
        );

        return new TaskCollection( $tasks->paginate() );
    }

    public function store(TaskStoreRequest $request): TaskResource|array
    {

        $task = Task::query()->create(
            collect(
                $request->validated( )
            )->except('employee_ids')->all()
        );

        $request->attach_employee( $task );
        return new TaskResource($task);
    }

    public function show(TaskActivityShowRequest $request, Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    public function update(TaskUpdateRequest $request, Task $task): TaskResource|Response
    {
        $task->update($request->validated());
        return new TaskResource($task);
    }

    public function assign(Task $task, Request $request){

        $data = $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'integer|exists:users,id'
        ]);

        $employee_ids = $data['employee_ids'];

        if( $task->id && is_array($employee_ids) ) {

            $task->employees()->whereNotIn('users.id', $employee_ids)->detach();

//            $attaching_ids =
//                $task->employees()
//                    ->whereIn('users.id', $employee_ids)
//                    ->pluck('users.id')
//                    ->all();
//
            $task->employees()->attach( $employee_ids, ['created_at' => now()], false);

            $task->load('employees');
            return new TaskResource($task);
        }

        return response([
            'message' => 'Unknown errors',
            'success' => false
        ],400);
    }

    public function destroy(Request $request, Task $task): Response
    {
        $task->delete();
        return response()->noContent();
    }

    public function restore(Task $trashed_task): TaskResource
    {
        $trashed_task->restore();
        return new TaskResource($trashed_task);
    }
}
