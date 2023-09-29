<?php

namespace App\Models\Traits;

use Illuminate\Support\Collection;

trait ValuesAssignable
{
    protected function map_assign_ids(array $ids, $column = ''):Collection{
        return collect( $ids )->map(function ($id) use ($column ){
            return [
                $column => $id,
                'created_at' => now(),
            ];
        });
    }
}
