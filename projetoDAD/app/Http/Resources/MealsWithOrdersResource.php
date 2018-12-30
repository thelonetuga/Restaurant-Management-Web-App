<?php

namespace App\Http\Resources;

use App\Items;
use App\Meals;
use App\Orders;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;
use mysql_xdevapi\Collection;

class MealsWithOrdersResource extends OrdersResource
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
            'state_invoice' => $this->invoice()->value('state'),
            'state' => $this->state,
            'table_number' => $this->table_number,
            'waiter_name' => $this->user()->value('name'),
            'orders'=>$this->order()->with('item')->get(),
        ];
    }
}
