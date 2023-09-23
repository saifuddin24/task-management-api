<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ExpenseController
 */
class ExpenseControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected(): void
    {
        $expenses = Expense::factory()->count(3)->create();

        $response = $this->get(route('expense.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ExpenseController::class,
            'store',
            \App\Http\Requests\ExpenseStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves(): void
    {
        $user_work_registration_id = $this->faker->numberBetween(-1000, 1000);
        $expense_purpose_id = $this->faker->numberBetween(-1000, 1000);
        $created_at = $this->faker->dateTime();
        $amount = $this->faker->numberBetween(-100000, 100000);

        $response = $this->post(route('expense.store'), [
            'user_work_registration_id' => $user_work_registration_id,
            'expense_purpose_id' => $expense_purpose_id,
            'created_at' => $created_at,
            'amount' => $amount,
        ]);

        $expenses = Expense::query()
            ->where('user_work_registration_id', $user_work_registration_id)
            ->where('expense_purpose_id', $expense_purpose_id)
            ->where('created_at', $created_at)
            ->where('amount', $amount)
            ->get();
        $this->assertCount(1, $expenses);
        $expense = $expenses->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected(): void
    {
        $expense = Expense::factory()->create();

        $response = $this->get(route('expense.show', $expense));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with(): void
    {
        $expense = Expense::factory()->create();

        $response = $this->delete(route('expense.destroy', $expense));

        $response->assertNoContent();

        $this->assertSoftDeleted($expense);
    }
}
