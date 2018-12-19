<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice_items extends Model
{
    public $timestamps = false;
    public $primaryKey = 'invoice_id';
    public $incrementing = false;
    //
    protected $fillable = [
        'invoice_id', 'item_id', 'quantity', 'unit_price', 'sub_total_price'
    ];
}
