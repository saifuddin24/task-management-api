<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TaskController
 */
class TaskControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $tasks = Task::factory()->count(3)->create();

        $response = $this->get(route('task.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskController::class,
            'store',
            \App\Http\Requests\Task\TaskStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $title = $this->faker->sentence(4);
        $deadline = $this->faker->dateTime();
        $employee_id = $this->faker->numberBetween(-10000, 10000);
        $created_at = $this->faker->dateTime();

        $response = $this->post(route('task.store'), [
            'title' => $title,
            'deadline' => $deadline,
            'employee_id' => $employee_id,
            'created_at' => $created_at,
        ]);

        $tasks = Task::query()
            ->where('title', $title)
            ->where('deadline', $deadline)
            ->where('employee_id', $employee_id)
            ->where('created_at', $created_at)
            ->get();
        $this->assertCount(1, $tasks);
        $task = $tasks->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $task = Task::factory()->create();

        $response = $this->get(route('task.show', $task));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TaskController::class,
            'update',
            \App\Http\Requests\Task\TaskUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $task = Task::factory()->create();
        $title = $this->faker->sentence(4);
        $deadline = $this->faker->dateTime();
        $employee_id = $this->faker->numberBetween(-10000, 10000);
        $created_at = $this->faker->dateTime();

        $response = $this->put(route('task.update', $task), [
            'title' => $title,
            'deadline' => $deadline,
            'employee_id' => $employee_id,
            'created_at' => $created_at,
        ]);

        $task->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($title, $task->title);
        $this->assertEquals($deadline, $task->deadline);
        $this->assertEquals($employee_id, $task->employee_id);
        $this->assertEquals($created_at, $task->created_at);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $task = Task::factory()->create();

        $response = $this->delete(route('task.destroy', $task));

        $response->assertNoContent();

        $this->assertModelMissing($task);
    }
}
