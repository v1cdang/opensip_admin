<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalleridController extends Controller
{
    public function index($prefix, $phonenumber, $ipaddress)
    {

        $callerid = '';
        if ($prefix=='3300') {
            $available_callerids['CA'] = '13236419245';
            $available_callerids['CO'] = '13034810082';
            $available_callerids['CT'] = '18604125025';
            $available_callerids['FL'] = '19042484331';
            $available_callerids['IL'] = '13128210893';
            $available_callerids['MA'] = '16175318167';
            $available_callerids['NJ'] = '19738491638';
            $available_callerids['NM'] = '15053539918';
            $available_callerids['NV'] = '17252060624';
            $available_callerids['TX'] = '19723672084';
            $available_callerids['UT'] = '18016566113';
            $npanxx = substr($phonenumber, 1, 6);
            $state = DB::table('npanxx')->select('stateISO')->where(['npanxx'=>$npanxx])->first();
            if (array_key_exists($state->stateISO, $available_callerids)) {
                $callerid = $available_callerids[$state->stateISO];
                if ($callerid!='')
                    echo $callerid;
                else
                    echo '13236419245';
            } else {
                echo '13236419245';
            }
        } else {
            $callerids = DB::table('callerids')->select('callerid')->where(['prefix'=>$prefix, 'ip'=>$ipaddress])->inRandomOrder()->get();
            echo $callerids[0]->callerid;
        }
    }
}
