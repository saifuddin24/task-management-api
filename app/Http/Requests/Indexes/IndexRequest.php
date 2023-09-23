<?php

namespace App\Http\Requests\Indexes;
use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    protected $relations = [];

    protected function getRelations():array{
        return [];
    }

    public function relations():array{
        $relations = [ ];

        $relation_requests = explode(',', $this->get('with' ));

        foreach ($this->getRelations() as $relation => $permission ) {

            $relation_callback = '';

            if( is_array($permission) ) {
                $relation_callback = $permission[1];
                $permission = $permission[0];
            }

            if( in_array($relation, $relation_requests) && $this->user()->tokenCan( $permission ) ) {
                if( $relation_callback ) {
                    $relations[$relation] = $relation_callback;
                }else {
                    $relations[] = $relation;
                }
            }

        }

        return $relations;
    }

}
