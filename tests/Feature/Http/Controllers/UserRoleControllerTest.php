<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\UserRoleController
 */
class UserRoleControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $userRoles = UserRole::factory()->count(3)->create();

        $response = $this->get(route('user-role.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Auth\UserRoleController::class,
            'store',
            \App\Http\Requests\UserRrole\UserRoleStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $role_id = $this->faker->numberBetween(-1000, 1000);
        $user_id = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('user-role.store'), [
            'role_id' => $role_id,
            'user_id' => $user_id,
        ]);

        $userRoles = UserRole::query()
            ->where('role_id', $role_id)
            ->where('user_id', $user_id)
            ->get();
        $this->assertCount(1, $userRoles);
        $userRole = $userRoles->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $userRole = UserRole::factory()->create();

        $response = $this->delete(route('user-role.destroy', $userRole));

        $response->assertNoContent();

        $this->assertModelMissing($userRole);
    }
}
