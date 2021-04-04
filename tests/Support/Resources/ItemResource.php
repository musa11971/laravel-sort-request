<?php

namespace musa11971\SortRequest\Tests\Support\Resources;

use Illuminate\Http\Resources\Json\JsonResource as Resource;
use musa11971\SortRequest\Tests\Support\Models\Item;

class ItemResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request = null)
    {
        /** @var Item $item */
        $item = $this->resource;

        return [
            'id'            => (int) $item->id,
            'displayName'   => $item->displayName,
            'gameName'      => $item->gameName,
            'stackSize'     => (int) $item->stackSize
        ];
    }
}
