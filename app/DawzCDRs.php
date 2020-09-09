<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dawzcdrs extends Model
{
    public $fillable = ['dialed_number',
    'callerid',
    'duration',
    'calldate',
    'rate',
    'total_cost'];
}
