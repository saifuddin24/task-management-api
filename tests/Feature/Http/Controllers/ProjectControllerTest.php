<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ProjectController
 */
class ProjectControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $projects = Project::factory()->count(3)->create();

        $response = $this->get(route('project.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProjectController::class,
            'store',
            \App\Http\Requests\ProjectStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $title = $this->faker->sentence(4);
        $manager_id = $this->faker->numberBetween(-10000, 10000);
        $created_at = $this->faker->dateTime();

        $response = $this->post(route('project.store'), [
            'title' => $title,
            'manager_id' => $manager_id,
            'created_at' => $created_at,
        ]);

        $projects = Project::query()
            ->where('title', $title)
            ->where('manager_id', $manager_id)
            ->where('created_at', $created_at)
            ->get();
        $this->assertCount(1, $projects);
        $project = $projects->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $project = Project::factory()->create();

        $response = $this->get(route('project.show', $project));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ProjectController::class,
            'update',
            \App\Http\Requests\ProjectUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $project = Project::factory()->create();
        $title = $this->faker->sentence(4);
        $manager_id = $this->faker->numberBetween(-10000, 10000);
        $created_at = $this->faker->dateTime();

        $response = $this->put(route('project.update', $project), [
            'title' => $title,
            'manager_id' => $manager_id,
            'created_at' => $created_at,
        ]);

        $project->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($title, $project->title);
        $this->assertEquals($manager_id, $project->manager_id);
        $this->assertEquals($created_at, $project->created_at);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $project = Project::factory()->create();

        $response = $this->delete(route('project.destroy', $project));

        $response->assertNoContent();

        $this->assertModelMissing($project);
    }
}
