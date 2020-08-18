<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function getCurrentClientSummary()
    {
        $dateNow = date("Y-m-d");

        $okCalls = DB::select("SELECT clientPrefix as PREFIX, count(*) as TOTAL, sum(duration) as DURATION, SUM(customerCallCost) as `customerCallCost`, AVG(customerCost) as `avgcustomerCost`, SUM(vendorCallCost) as `vendorCallCost`, AVG(carrierCost) as `avgcarrierCost`, carrierid FROM `acc` where time BETWEEN '? 00:00:00' AND NOW() and to_tag!='' and clientPrefix!='' and carrierid!='' and sip_code='200' group by clientPrefix, carrierid", [$dateNow]);
   // dd($okCalls);
        return view('reports',['okCalls' => $okCalls]);
    }
}
