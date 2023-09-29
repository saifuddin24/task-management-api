<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\UserPermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\UserPermissionController
 */
class UserPermissionControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $userPermissions = UserPermission::factory()->count(3)->create();

        $response = $this->get(route('user-permission.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Auth\UserPermissionController::class,
            'store',
            \App\Http\Requests\UserPermission\UserPermissionStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $permission_id = $this->faker->numberBetween(-10000, 10000);
        $user_id = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('user-permission.store'), [
            'permission_id' => $permission_id,
            'user_id' => $user_id,
        ]);

        $userPermissions = UserPermission::query()
            ->where('permission_id', $permission_id)
            ->where('user_id', $user_id)
            ->get();
        $this->assertCount(1, $userPermissions);
        $userPermission = $userPermissions->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $userPermission = UserPermission::factory()->create();

        $response = $this->delete(route('user-permission.destroy', $userPermission));

        $response->assertNoContent();

        $this->assertModelMissing($userPermission);
    }
}
