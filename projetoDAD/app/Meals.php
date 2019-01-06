<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meals extends Model
{

    public function invoice(){
        return $this->belongsTo(Invoices::class,'id','meal_id');
    }

    protected $fillable = [
        'table_number', 'state', 'total_price_preview', 'start', 'end', 'total_price_preview', 'responsible_waiter_id'
    ];

    public function orders(){
        return $this->hasMany(Orders::class, 'meal_id', 'id')->orderBy('start');
    }

    public function order(){
        return $this->hasMany(Orders::class, 'meal_id','id');
    }

    public function item(){
        return $this->hasMany(Items::class, 'item_id', 'id');
    }

    public function user(){
        return $this->hasOne(User::class,'id','responsible_waiter_id');
    }


}
