<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CdrsController extends Controller
{
    public function compareDawzCDRs(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');


    }
}
