<?php

namespace App\Http\Requests;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class IndexOrShowRequest extends FormRequest
{
    protected $relations = [];

    protected function getRelations():array{
        return [];
    }

    public function relations(array $relations = []):array{
        $relation_requests = explode(',', $this->get('with' ));
        $result = [];



//        return [...$this->getRelations(), ...$relations];

        foreach ([...$this->getRelations(), ...$relations] as $relation => $permission ) {

            $relation_callback = '';

            if( is_array($permission) ) {
                $relation_callback = $permission[1];
                $permission = $permission[0];
            }

            if( in_array($relation, $relation_requests) && $this->user()->tokenCan( $permission ) ) {
                if( $relation_callback ) {
                    $result[$relation] = $relation_callback;
                }else {
                    $result[] = $relation;
                }
            }
        }

        return $result;
    }

}
