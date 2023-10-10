<?php

namespace App\Http\Controllers;


use App\Http\Requests\TaskActivity\TaskActivityFinishRequest;
use App\Http\Requests\TaskActivity\TaskActivityIndexOrShowRequest;
use App\Http\Requests\TaskActivity\TaskActivityShowRequest;
use App\Http\Requests\TaskActivity\TaskShowRequest;
use App\Http\Requests\TaskActivity\TaskActivityStoreRequest;
use App\Http\Requests\TaskActivity\TaskActivityUpdateRequest;
use App\Http\Resources\TaskActivityCollection;
use App\Http\Resources\TaskActivityResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\TaskAssign;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class TaskActivityController extends Controller
{

    protected array $authorized = [
        'task-activity.read' => [ 'index','show' ],
        'task-activity.create' => [ 'store' ],
        'task-activity.update' => [ 'update' ],
        'task-activity.delete' => [ 'destroy' ],
    ];

    public function index(TaskActivityIndexOrShowRequest $request, Task $task): TaskActivityCollection|Collection
    {
        $taskActivities = $task
                            ->task_activities()
                            ->whereNotNull('finished_at' )
                            ->orderBy('created_at','desc')
                            ->orderBy('progress_percentage','desc')
                            ->with( [
                                ...$request->relations([]),
                            ]);

        $taskActivities->with('assign.employee');

        $taskActivities->when($request->get('employee_id'), function ( $taskActivities, $employee_id){

            $taskActivities->where('task_user.user_id', $employee_id );
        });



        $taskActivities = $taskActivities->get();

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

    protected function user_assigned(Task $task, Request $request){
        $task_assigned = $task->assigns()->where('user_id', $request->user()->id );
        return $task_assigned;
    }

    public function store(TaskActivityStoreRequest $request, Task $task): TaskActivityResource|Response|array
    {

        $task_assigned = $this->user_assigned($task, $request)->first();

        if( !$task_assigned ) {
            return response([
                'message' => 'You are not assigned to the task.',
            ],403);
        }

        if( $task->task_activities()->whereNull('finished_at')->exists() ) {
            return response([
                'message' => 'You have an unfinished task, finish that and try again.',
            ],400);
        }

        $taskActivity = $task_assigned->activities()->create(
            array_merge(
                [
                    'created_at' => now()
                ],
                $request->validated( )
            )
        );

        return (new TaskActivityResource($taskActivity))->additional([
            'task' => $task
        ]);
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

    public function finish(TaskActivityFinishRequest $request, Task $task,  TaskActivity $activity): TaskActivityResource|Response
    {
        $task_assigned = $this->user_assigned($task, $request)->first();

        if( !$task_assigned instanceof TaskAssign) {
            return response([
                'message' => 'You are not assigned to the task.',
            ],403);
        }

        $activity = $task_assigned->activities()->find( $activity->id );

        $activity?->update($request->validated());

        return new TaskActivityResource($activity);
    }

    public function destroy(Request $request, Task $task,  TaskActivity $activity): Response
    {
        $activity->delete();

        return response()->noContent();
    }
}
