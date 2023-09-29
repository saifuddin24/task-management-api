<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TeamController
 */
class TeamControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $teams = Team::factory()->count(3)->create();

        $response = $this->get(route('team.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TeamController::class,
            'store',
            \App\Http\Requests\Team\TeamStoreRequest::class
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

        $response = $this->post(route('team.store'), [
            'title' => $title,
            'manager_id' => $manager_id,
            'created_at' => $created_at,
        ]);

        $teams = Team::query()
            ->where('title', $title)
            ->where('manager_id', $manager_id)
            ->where('created_at', $created_at)
            ->get();
        $this->assertCount(1, $teams);
        $team = $teams->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $team = Team::factory()->create();

        $response = $this->get(route('team.show', $team));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TeamController::class,
            'update',
            \App\Http\Requests\Team\TeamUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $team = Team::factory()->create();
        $title = $this->faker->sentence(4);
        $manager_id = $this->faker->numberBetween(-10000, 10000);
        $created_at = $this->faker->dateTime();

        $response = $this->put(route('team.update', $team), [
            'title' => $title,
            'manager_id' => $manager_id,
            'created_at' => $created_at,
        ]);

        $team->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($title, $team->title);
        $this->assertEquals($manager_id, $team->manager_id);
        $this->assertEquals($created_at, $team->created_at);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $team = Team::factory()->create();

        $response = $this->delete(route('team.destroy', $team));

        $response->assertNoContent();

        $this->assertModelMissing($team);
    }
}
