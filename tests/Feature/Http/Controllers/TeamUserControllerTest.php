<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\TeamUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TeamUserController
 */
class TeamUserControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $teamUsers = TeamUser::factory()->count(3)->create();

        $response = $this->get(route('team-user.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TeamUserController::class,
            'store',
            \App\Http\Requests\TeamUserStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $user_id = $this->faker->numberBetween(-10000, 10000);
        $team_id = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('team-user.store'), [
            'user_id' => $user_id,
            'team_id' => $team_id,
        ]);

        $teamUsers = TeamUser::query()
            ->where('user_id', $user_id)
            ->where('team_id', $team_id)
            ->get();
        $this->assertCount(1, $teamUsers);
        $teamUser = $teamUsers->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $teamUser = TeamUser::factory()->create();

        $response = $this->delete(route('team-user.destroy', $teamUser));

        $response->assertNoContent();

        $this->assertModelMissing($teamUser);
    }
}
