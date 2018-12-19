<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Resources\InvoiceItems;


class Items extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'price', 'type', 'description',
    ];

    public function order(){
        return $this->belongsTo(Orders::class, 'item_id','id');
    }

    public function invoice_item(){
        return $this->belongsToMany(InvoiceItems::class,'invoices_items','meal_id','id');
    }
}
