<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'state' => $this->state,
            'item' => $this->item == 'undefined' ? "" : $this->item,
            'meal' => $this->meal_id,
            'end' => $this->end,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'responsible_cook_id' => $this->responsible_cook_id
        ];
        //return parent::toArray($request);
    }


}
