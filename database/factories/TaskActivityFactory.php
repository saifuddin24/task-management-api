<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Task;
use App\Models\TaskActivity;

class TaskActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TaskActivity::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'progress_percentage' => $this->faker->numberBetween(-8, 8),
            'task_id' => Task::factory(),
            'created_at' => $this->faker->dateTime(),
            'finished_at' => $this->faker->dateTime(),
        ];
    }
}
