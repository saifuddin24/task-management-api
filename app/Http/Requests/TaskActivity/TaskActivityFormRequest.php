<?php

namespace App\Http\Requests\TaskActivity;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;


class TaskActivityFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function max_progress_validation(): \Closure{
        return function($attr, $value, $error){

            $task = $this->route('task');

            if( $task instanceof Task ) {
                $task_max_progress = $task->task_activities()->max('progress_percentage');
                if($value <= $task_max_progress) {
                    $error("Progress must be greater than task overall progress " . $task_max_progress . "%");
                }
            }

        };
    }

    protected function last_finished_at_validation():\Closure{
        return function($attr, $value, $error){
            $task = $this->route('task');

            if( $task instanceof Task ) {
                $last_finished_at = $task->task_activities()->max('finished_at');

                if($value < $last_finished_at) {

                    $display_time = Carbon::make( $last_finished_at )
                        ->format(config('app.default_time_format'));

                    $error( "Created at must be after last finished activity {$display_time}" );

                }

            }
        };
    }
}
