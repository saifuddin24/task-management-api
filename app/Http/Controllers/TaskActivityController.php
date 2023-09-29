<?php

namespace App\Http\Controllers;


use App\Http\Requests\TaskActivity\TaskActivityFinishRequest;
use App\Http\Requests\TaskActivity\TaskActivityIndexRequest;
use App\Http\Requests\TaskActivity\TaskActivityShowRequest;
use App\Http\Requests\TaskActivity\TaskShowRequest;
use App\Http\Requests\TaskActivity\TaskActivityStoreRequest;
use App\Http\Requests\TaskActivity\TaskActivityUpdateRequest;
use App\Http\Resources\TaskActivityCollection;
use App\Http\Resources\TaskActivityResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\TaskActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskActivityController extends Controller
{

    protected array $authorized = [
        'task-activity.read' => [ 'index','show' ],
        'task-activity.create' => [ 'store' ],
        'task-activity.update' => [ 'update' ],
        'task-activity.delete' => [ 'destroy' ],
    ];

    public function index(TaskActivityIndexRequest $request, Task $task): TaskActivityCollection
    {
        $taskActivities = $task
                            ->task_activities()
                            ->whereNotNull('finished_at' )
                            ->orderBy('created_at','desc')
                            ->orderBy('progress_percentage','desc')
                            ->with( [
                                ...$request->relations([]),
                            ])
                            ->paginate();

        $unfinished_activity = $task
            ->task_activities()
            ->whereNull('finished_at' )
            ->orderBy('created_at','desc')->first();


        $task->setAttribute(
            'progress_percentage',
            $task->task_activities()
                ->max('progress_percentage')
        );

        $task->load('project');

        return (new TaskActivityCollection($taskActivities))->additional([
            'task' => new TaskResource( $task ),
            'last_unfinished_activity' =>
                $unfinished_activity
                    ? TaskActivityResource::make($unfinished_activity)
                    : null
        ]);
    }

    public function store(TaskActivityStoreRequest $request, Task $task): TaskActivityResource|Response
    {

        if( $task->task_activities()->whereNull('finished_at')->exists() ) {
            return response([
                'message' => 'You have an unfinished task, finish that and try again.',
            ],400);
        }

        $taskActivity = $task->task_activities()->create($request->validated( ));

        return new TaskActivityResource($taskActivity);
    }

    public function show(TaskActivityShowRequest $request, Task $task, TaskActivity $activity ) : TaskActivityResource
    {

        if( $request->user()->tokenCan('user.read') ) {
            $task->load([
                'manager','employee'
            ]);
        }

        return (new TaskActivityResource($activity))
            ->additional([
                'task' => $request->user()->tokenCan('task-activity.read')
                    ?  TaskResource::make( $task )
                    : null
            ]);
    }

    public function finish(TaskActivityFinishRequest $request, Task $task,  TaskActivity $activity): TaskActivityResource
    {
        $task->task_activities()
            ->find( $activity->id )
            ->update( $request->validated() );

        return new TaskActivityResource($activity);
    }

    public function destroy(Request $request, Task $task,  TaskActivity $activity): Response
    {
        $activity->delete();

        return response()->noContent();
    }
}
