<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function getCurrentClientSummary()
    {
        $dateNow = date("Y-m-d");

        $okCalls = DB::select("SELECT clientPrefix as PREFIX, count(distinct callid) as TOTAL, carrierid, sip_code, sip_reason, DATE_SUB(NOW(),INTERVAL 2 HOUR) as `date_start` FROM `acc` where time BETWEEN DATE_SUB(NOW(),INTERVAL 2 HOUR) AND NOW() and to_tag!='' and clientPrefix!='' and carrierid!='' group by clientPrefix, sip_code, sip_reason, carrierid");
   // dd($okCalls);
        return view('reports',['okCalls' => $okCalls]);
    }
}
