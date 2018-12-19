<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{

    protected $fillable = [
        'id', 'state', 'item_id','meal_id','responsible_cook_id', 'start'
    ];

    public function meal(){
        return $this->belongsTo(Meals::class);
    }

    public function item(){
        return $this->hasOne(Items::class,'id','item_id');
    }
}
