<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalleridController extends Controller
{
    public function index($prefix, $ipaddress)
    {
        $callerids = DB::table('callerids')->select('callerid')->where(['prefix'=>$prefix, 'ip'=>$ipaddress])->inRandomOrder()->get();
        echo $callerids[0]->callerid;
    }
}
