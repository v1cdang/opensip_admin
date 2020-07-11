<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditsController extends Controller
{
    public function index()
    {
        $prefixes = DB::table('cc_card')->select('prefix')->get();

        return view('creditHistory', ['prefixes' => $prefixes]);
    }

    public function viewPrefixCreditHistory(Request $request)
    {
        $prefixes = DB::table('cc_card')->select('prefix')->get();
        $selectedPrefix = $request->input('selectedPrefix');
        $credit_history = DB::table('account_credit_history')->get();
        return view('prefixCreditHistory', ['credit_history' => $credit_history, 'prefixes' => $prefixes]);
    }

}
