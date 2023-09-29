<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\RolePermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\RolePermissionController
 */
class RolePermissionControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $rolePermissions = RolePermission::factory()->count(3)->create();

        $response = $this->get(route('role-permission.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Auth\RolePermissionController::class,
            'store',
            \App\Http\Requests\RolePermission\RolePermissionStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $permission_id = $this->faker->numberBetween(-10000, 10000);
        $role_id = $this->faker->numberBetween(-1000, 1000);

        $response = $this->post(route('role-permission.store'), [
            'permission_id' => $permission_id,
            'role_id' => $role_id,
        ]);

        $rolePermissions = RolePermission::query()
            ->where('permission_id', $permission_id)
            ->where('role_id', $role_id)
            ->get();
        $this->assertCount(1, $rolePermissions);
        $rolePermission = $rolePermissions->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $rolePermission = RolePermission::factory()->create();

        $response = $this->delete(route('role-permission.destroy', $rolePermission));

        $response->assertNoContent();

        $this->assertModelMissing($rolePermission);
    }
}
