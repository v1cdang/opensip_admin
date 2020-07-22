<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberFormat;
use Propaganistas\LaravelPhone\PhoneNumber;
use Propaganistas\LaravelPhone\Exceptions\CountryCodeException;
use Propaganistas\LaravelPhone\Exceptions\NumberParseException;
use Propaganistas\LaravelPhone\Exceptions\NumberFormatException;
use Illuminate\Support\Facades\DB;

class RoutesController extends Controller
{
    public function index(Request $request, $clientPrefix, $phonenumber)
    {
        $requestingIP = $request->ip();

        $phone = new PhoneNumber('+'.$phonenumber);
        $countryPhone = $phone->getCountry();
        //echo $countryPhone;
        $allowed_country = DB::table('customer_allowed_countries')->select('country')->where(['prefix' => $clientPrefix,'country' => $countryPhone])->first();
        if ($allowed_country->isEmpty()) {
            echo "NOT ALLOWED"
        }
    }

}
