<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant_tables extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'table_number';

    protected $fillable = [
        'table_number',
    ];
}
