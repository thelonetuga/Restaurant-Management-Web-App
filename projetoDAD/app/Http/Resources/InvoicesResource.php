<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoicesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'state' => $this->state,
            'nif' => $this->nif,
            'date' => $this->date,
            'meals' => $this->meals,
            'waiter_name' => $this->meals->user()->value('name'),
            'order_state' => $this->meals->order()->value('state'),
            'item' => $this->items,
        ];
    }
}

/*
return [
    'invoice_id' => $this->state,
    //'item_id' => $this->items->name,
    'table_number' => $this->meals->table_number,
    'item_name' => $this->items()->value('name'),

   //'item' => \App\Items::where('id', $this->item_id)->get()
];
 */
