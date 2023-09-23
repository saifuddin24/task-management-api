<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\RevenuePurpose;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\RevenuePurposeController
 */
class RevenuePurposeControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $revenuePurposes = RevenuePurpose::factory()->count(3)->create();

        $response = $this->get(route('revenue-purpose.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RevenuePurposeController::class,
            'store',
            \App\Http\Requests\RevenuePurposeStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $title = $this->faker->sentence(4);

        $response = $this->post(route('revenue-purpose.store'), [
            'title' => $title,
        ]);

        $revenuePurposes = RevenuePurpose::query()
            ->where('title', $title)
            ->get();
        $this->assertCount(1, $revenuePurposes);
        $revenuePurpose = $revenuePurposes->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $revenuePurpose = RevenuePurpose::factory()->create();

        $response = $this->get(route('revenue-purpose.show', $revenuePurpose));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $revenuePurpose = RevenuePurpose::factory()->create();

        $response = $this->delete(route('revenue-purpose.destroy', $revenuePurpose));

        $response->assertNoContent();

        $this->assertModelMissing($revenuePurpose);
    }
}
