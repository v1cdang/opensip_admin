<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditsController extends Controller
{
    private function getAllPrefixes()
    {
        $prefixes = DB::table('cc_card')->select('prefix')
                    ->orderBy('prefix','asc')
                    ->get();
        return $prefixes;
    }
    public function index()
    {
        $prefixes = $this->getAllPrefixes();

        return view('creditHistory', ['prefixes' => $prefixes]);
    }

    public function viewPrefixCreditHistory($prefix)
    {
        DB::enableQueryLog();
        $prefixes = $this->getAllPrefixes();
        $credit_history = DB::table('account_credit_history')->where('prefix',$prefix)->get();
//      $query = DB::getQueryLog();


        return view('prefixCreditHistory', ['credit_history' => $credit_history, 'prefixes' => $prefixes]);
    }

}
