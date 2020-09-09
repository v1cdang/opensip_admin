<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DawzCDRs extends Model
{
    public $fillable = ['dialed_number',
    'callerid',
    'duration',
    'calldate',
    'rate',
    'total_cost'];
}
