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
        if (is_null($allowed_country)) {
            echo "NOT ALLOWED";
        } else {
            echo "ALLOWED";
        }
    }

    public function getCountry(Request $request, $phonenumber)
    {
        $phone = new PhoneNumber('+'.$phonenumber);
        $countryPhone = $phone->getCountry();
        $countryCode = DB::table('country_code')->select('code')->where(['alpha2'=>$countryPhone])->first();
        echo $countryPhone."|".$countryCode->code;
    }

}
