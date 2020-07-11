<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountsController extends Controller
{
    public function index()
    {
        $prefixes = DB::table('cc_card')->select('prefix')->get();

        return view('addCredit', ['prefixes' => $prefixes]);
    }
    public function addCreditForm($prefix)
    {
        $prefixes = DB::table('cc_card')->select('prefix')->get();
        return view('addCreditForm', ['selectedPrefix' => $prefix, 'prefixes' => $prefixes]);
    }
    public function updateCredit(Request $request)
    {
        DB::enableQueryLog();
        $selectedPrefix = $request->input('selectedPrefix');
        $credit_amount =  (int) $request->input('credit_amount');

        $previous_balance = DB::table('cc_card')->select('credit')->where('prefix', $selectedPrefix)->first();

        $current_balance = $previous_balance->credit + $credit_amount;
        DB::table('cc_card')->where('prefix', $selectedPrefix)
                           ->increment('credit',$credit_amount);
        $credit_history = DB::table('account_credit_history')->insert(
            [
                'prefix' => $selectedPrefix,
                'previous_balance' => $previous_balance->credit,
                'credit_amount' => $credit_amount,
                'current_balance' => $current_balance
            ]
        );
        if($credit_history) {
            return redirect()->back()->withSuccess('Account Credited');
        } else {
            // the query failed
        }

    }
}
