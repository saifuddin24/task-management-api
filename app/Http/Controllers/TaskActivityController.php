<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskActivityStoreRequest;
use App\Http\Requests\TaskActivityUpdateRequest;
use App\Http\Resources\TaskActivityCollection;
use App\Http\Resources\TaskActivityResource;
use App\Models\TaskActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskActivityController extends Controller
{
    public function index(Request $request): Response
    {
        $taskActivities = TaskActivity::all();

        return new TaskActivityCollection($taskActivities);
    }

    public function store(TaskActivityStoreRequest $request): Response
    {
        $taskActivity = TaskActivity::create($request->validated());

        return new TaskActivityResource($taskActivity);
    }

    public function show(Request $request, TaskActivity $taskActivity): Response
    {
        return new TaskActivityResource($taskActivity);
    }

    public function update(TaskActivityUpdateRequest $request, TaskActivity $taskActivity): Response
    {
        $taskActivity->update($request->validated());

        return new TaskActivityResource($taskActivity);
    }

    public function destroy(Request $request, TaskActivity $taskActivity): Response
    {
        $taskActivity->delete();

        return response()->noContent();
    }
}
