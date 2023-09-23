<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\CarTrip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CarTripController
 */
class CarTripControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $carTrips = CarTrip::factory()->count(3)->create();

        $response = $this->get(route('car-trip.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CarTripController::class,
            'store',
            \App\Http\Requests\CarTripStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $user_work_registration_id = $this->faker->numberBetween(-1000, 1000);
        $revenue_purpose_id = $this->faker->numberBetween(-1000, 1000);
        $started_at = $this->faker->dateTime();
        $duration = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('car-trip.store'), [
            'user_work_registration_id' => $user_work_registration_id,
            'revenue_purpose_id' => $revenue_purpose_id,
            'started_at' => $started_at,
            'duration' => $duration,
        ]);

        $carTrips = CarTrip::query()
            ->where('user_work_registration_id', $user_work_registration_id)
            ->where('revenue_purpose_id', $revenue_purpose_id)
            ->where('started_at', $started_at)
            ->where('duration', $duration)
            ->get();
        $this->assertCount(1, $carTrips);
        $carTrip = $carTrips->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $carTrip = CarTrip::factory()->create();

        $response = $this->get(route('car-trip.show', $carTrip));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $carTrip = CarTrip::factory()->create();

        $response = $this->delete(route('car-trip.destroy', $carTrip));

        $response->assertNoContent();

        $this->assertSoftDeleted($carTrip);
    }
}
