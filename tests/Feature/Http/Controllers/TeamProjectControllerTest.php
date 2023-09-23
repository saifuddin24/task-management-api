<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\TeamProject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TeamProjectController
 */
class TeamProjectControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $teamProjects = TeamProject::factory()->count(3)->create();

        $response = $this->get(route('team-project.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TeamProjectController::class,
            'store',
            \App\Http\Requests\TeamProjectStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $project_id = $this->faker->numberBetween(-10000, 10000);
        $team_id = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('team-project.store'), [
            'project_id' => $project_id,
            'team_id' => $team_id,
        ]);

        $teamProjects = TeamProject::query()
            ->where('project_id', $project_id)
            ->where('team_id', $team_id)
            ->get();
        $this->assertCount(1, $teamProjects);
        $teamProject = $teamProjects->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $teamProject = TeamProject::factory()->create();

        $response = $this->get(route('team-project.show', $teamProject));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TeamProjectController::class,
            'update',
            \App\Http\Requests\TeamProjectUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $teamProject = TeamProject::factory()->create();
        $project_id = $this->faker->numberBetween(-10000, 10000);
        $team_id = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('team-project.update', $teamProject), [
            'project_id' => $project_id,
            'team_id' => $team_id,
        ]);

        $teamProject->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($project_id, $teamProject->project_id);
        $this->assertEquals($team_id, $teamProject->team_id);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $teamProject = TeamProject::factory()->create();

        $response = $this->delete(route('team-project.destroy', $teamProject));

        $response->assertNoContent();

        $this->assertModelMissing($teamProject);
    }
}
