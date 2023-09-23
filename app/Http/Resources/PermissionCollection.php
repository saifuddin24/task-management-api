<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PermissionCollection extends ResourceCollection
{
    public static $wrap = 'permissions';
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return  $this->collection->all();
    }
}
