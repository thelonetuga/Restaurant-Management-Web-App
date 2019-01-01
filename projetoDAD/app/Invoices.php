<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invoice;
use App\Http\Resources\InvoiceItems;

class Invoices extends Model
{

    public function meals()
    {
        return $this->hasOne(Meals::class, 'id', 'meal_id');
    }

    public function item()
    {
        return $this->hasMany(Items::class, 'id', 'meal_id');
    }

    public function items()
    {
        return $this->belongsToMany(Items::class, 'invoice_items', 'invoice_id', 'item_id')->withPivot('quantity', 'unit_price', 'sub_total_price');
    }

    public function item_invoice()
    {
        return $this->belongsToMany(InvoiceItems::class, 'invoice_items', 'invoice_id', 'id');

    }

    protected $fillable = [
        'id', 'state', 'meal_id', 'nif', 'name', 'date', 'total_price', 'created_at', 'updated_at'
    ];
}


