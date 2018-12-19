<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItems extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }


}


/*
return [
            'invoice_id' => $this->state,
            //'item_id' => $this->items->name,
            'table_number' => $this->meals->table_number,
            'item_name' => $this->items()->value('name'),
            'sub_total_price' => InvoiceItems::where('invoice_id', $this->id)->with('items')->get(),
           //'item' => \App\Items::where('id', $this->item_id)->get()
        ];
 */
