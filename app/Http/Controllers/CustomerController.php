<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CustomerController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return route('login');
        }
        $prefixes = DB::table('cc_card')->select('prefix')->get();

        return view('customerAllowedCountriesForm', ['prefixes' => $prefixes]);
    }

    public function showAllowedCountriesForm($prefix)
    {
        $prefixes = DB::table('cc_card')->select('prefix')->get();
        $countries = DB::table('country_code')->select('country_name','alpha2')->get();
        return view('customerAllowedCountriesForm', ['selectedPrefix' => $prefix,'prefixes' => $prefixes, 'countries' => $countries]);
    }

    public function setAllowedCountries(Request $request)
    {
        $selectedPrefix = $request->input('selectedPrefix');
        $countries = $request->input('countries');
        foreach ($countries as $country) {
            DB::table('customer_allowed_countries')->insert(
                [
                    'prefix' => $selectedPrefix,
                    'country' => $country
                ]
            );

        }
        return redirect()->back()->withSuccess('$selectedPrefix account updated');
    }
}
