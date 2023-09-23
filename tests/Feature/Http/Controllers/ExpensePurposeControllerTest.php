<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\ExpensePurpose;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ExpensePurposeController
 */
class ExpensePurposeControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $expensePurposes = ExpensePurpose::factory()->count(3)->create();

        $response = $this->get(route('expense-purpose.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ExpensePurposeController::class,
            'store',
            \App\Http\Requests\ExpensePurposeStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $title = $this->faker->sentence(4);

        $response = $this->post(route('expense-purpose.store'), [
            'title' => $title,
        ]);

        $expensePurposes = ExpensePurpose::query()
            ->where('title', $title)
            ->get();
        $this->assertCount(1, $expensePurposes);
        $expensePurpose = $expensePurposes->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $expensePurpose = ExpensePurpose::factory()->create();

        $response = $this->get(route('expense-purpose.show', $expensePurpose));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $expensePurpose = ExpensePurpose::factory()->create();

        $response = $this->delete(route('expense-purpose.destroy', $expensePurpose));

        $response->assertNoContent();

        $this->assertModelMissing($expensePurpose);
    }
}
