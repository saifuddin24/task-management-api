<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\TaskActivity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TaskActivityController
 */
class TaskActivityControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $taskActivities = TaskActivity::factory()->count(3)->create();

        $response = $this->get(route('task-activity.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskActivityController::class,
            'store',
            \App\Http\Requests\TaskActivityStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $progress_percentage = $this->faker->numberBetween(-8, 8);
        $task_id = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('task-activity.store'), [
            'progress_percentage' => $progress_percentage,
            'task_id' => $task_id,
        ]);

        $taskActivities = TaskActivity::query()
            ->where('progress_percentage', $progress_percentage)
            ->where('task_id', $task_id)
            ->get();
        $this->assertCount(1, $taskActivities);
        $taskActivity = $taskActivities->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $taskActivity = TaskActivity::factory()->create();

        $response = $this->get(route('task-activity.show', $taskActivity));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskActivityController::class,
            'update',
            \App\Http\Requests\TaskActivityUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $taskActivity = TaskActivity::factory()->create();
        $progress_percentage = $this->faker->numberBetween(-8, 8);
        $task_id = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('task-activity.update', $taskActivity), [
            'progress_percentage' => $progress_percentage,
            'task_id' => $task_id,
        ]);

        $taskActivity->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($progress_percentage, $taskActivity->progress_percentage);
        $this->assertEquals($task_id, $taskActivity->task_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $taskActivity = TaskActivity::factory()->create();

        $response = $this->delete(route('task-activity.destroy', $taskActivity));

        $response->assertNoContent();

        $this->assertModelMissing($taskActivity);
    }
}
