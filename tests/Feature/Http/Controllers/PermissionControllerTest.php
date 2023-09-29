<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\PermissionController
 */
class PermissionControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $permissions = Permission::factory()->count(3)->create();

        $response = $this->get(route('permission.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Auth\PermissionController::class,
            'store',
            \App\Http\Requests\Permission\PermissionStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $title = $this->faker->sentence(4);
        $guard_name = $this->faker->word;

        $response = $this->post(route('permission.store'), [
            'title' => $title,
            'guard_name' => $guard_name,
        ]);

        $permissions = Permission::query()
            ->where('title', $title)
            ->where('guard_name', $guard_name)
            ->get();
        $this->assertCount(1, $permissions);
        $permission = $permissions->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $permission = Permission::factory()->create();

        $response = $this->get(route('permission.show', $permission));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $permission = Permission::factory()->create();

        $response = $this->delete(route('permission.destroy', $permission));

        $response->assertNoContent();

        $this->assertModelMissing($permission);
    }
}
