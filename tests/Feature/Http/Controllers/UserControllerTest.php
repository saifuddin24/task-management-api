<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Auth\UserController
 */
class UserControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $users = User::factory()->count(3)->create();

        $response = $this->get(route('user.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Auth\UserController::class,
            'store',
            \App\Http\Requests\User\UserStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $name = $this->faker->name;
        $password = $this->faker->password;
        $created_at = $this->faker->dateTime();

        $response = $this->post(route('user.store'), [
            'name' => $name,
            'password' => $password,
            'created_at' => $created_at,
        ]);

        $users = User::query()
            ->where('name', $name)
            ->where('password', $password)
            ->where('created_at', $created_at)
            ->get();
        $this->assertCount(1, $users);
        $user = $users->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('user.show', $user));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Auth\UserController::class,
            'update',
            \App\Http\Requests\User\UserUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected(): void
    {
        $user = User::factory()->create();
        $name = $this->faker->name;
        $password = $this->faker->password;
        $created_at = $this->faker->dateTime();

        $response = $this->put(route('user.update', $user), [
            'name' => $name,
            'password' => $password,
            'created_at' => $created_at,
        ]);

        $user->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($name, $user->name);
        $this->assertEquals($password, $user->password);
        $this->assertEquals($created_at, $user->created_at);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $user = User::factory()->create();

        $response = $this->delete(route('user.destroy', $user));

        $response->assertNoContent();

        $this->assertModelMissing($user);
    }
}
