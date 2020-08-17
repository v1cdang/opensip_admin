<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class CustomerController extends Controller
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
        if (!Auth::check()) {
            return route('login');
        }
        $prefixes = $this->getAllPrefixes();

        return view('customerAllowedCountriesForm', ['prefixes' => $prefixes]);
    }

    private function getNewPrefix()
    {
        $notfound = false;
        while(!$notfound) {
            $newPrefix = rand(1000,9999);
            $prefix_available = DB::table('cc_card')->select('prefix')->where('prefix',$newPrefix)->first();
            if (!$prefix_available) {
                $notfound=true;
                break;
            }
        }
        return $newPrefix;
    }

    public function addCustomerForm()
    {
        $newPrefix = $this->getNewPrefix();
        return view('addCustomerForm', ['newPrefix' => $newPrefix]);
    }

    public function addCustomer(Request $request)
    {
        $addCustomer = DB::table('cc_card')->insert(
            [
                'username'          => $request->input('prefix'),
                'useralias'         => $request->input('prefix'),
                'uipass'            => $request->input('prefix'),
                'lastname'          => $request->input('last_name'),
                'firstname'         => $request->input('first_name'),
                'prefix'            => $request->input('prefix'),
                'rate_increment'    => $request->input('rate_increment'),
                'rate_minimum'      => $request->input('rate_minimum'),
                'address'           => 'NO ADDRESS',
                'city'              => 'NO CITY',
                'state'             => 'NO STATE',
                'zipcode'           => 'NO ZIP',
                'phone'             => 'NO PHONE',
                'email'             => 'NO EMAIL',
                'fax'               => 'NO FAX',
                'email_notification'=> 'NONE',
                'company_name'      => $request->input('first_name'),
                'company_website'   => 'NONE'
            ]
        );
        if ($addCustomer) {
            return redirect()->back()->withSuccess('Customer Added');
        }
    }

    public function showAllowedCountriesForm($prefix)
    {
        $prefixes = $this->getAllPrefixes();
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

    public function setCustomerRatesForm()
    {
        $prefixes = $this->getAllPrefixes();

        return view('setCustomerRatesForm', ['prefixes' => $prefixes]);
    }

    public function setCustomerRates(Request $request)
    {
        $customer_rate = DB::table('customer_rates')->insert(
            [
                'prefix' => $request->input('prefix'),
                'code' => $request->input('code'),
                'rate' => $request->input('rate')
            ]
        );
        if($customer_rate) {
            return redirect()->back()->withSuccess('Customer rate added');
        } else {
            // the query failed
        }
    }
    public function addExtensionForm()
    {
        $password = $this->randomPassword();
        $prefixes = $this->getAllPrefixes();
        return view('addExtension', ['newpassword' => $password,'prefixes' => $prefixes]);
    }

    private function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function getChildExtension(Request $request, $prefix) {
        $strC = '';
        $childPrefixes = DB::table('sippeers')->select('name')->where(['accountcode'=> $prefix])->get();
        foreach($childPrefixes as $childPrefix) {
            $strC .= $childPrefix->name."|";
        }
        echo rtrim($strC, "|");
    }

    public function getCustomerDID(Request $request, $prefix)
    {
        $strC = '';
        $DIDs = DB::table('o2b_inbound')->select('DID')->where(['account'=> $prefix])->get();
        foreach($DIDs as $DID) {
            $strC .= $DID->DID."|";
        }
        echo rtrim($strC, "|");
    }


    public function addDID()
    {
        $prefixes = $this->getAllPrefixes();
        return view('addDID', ['prefixes' => $prefixes]);
    }
    public function addDIDtoExt(Request $request)
    {
        $parentPrefix = $request->input('customerPrefix');
        $dest = $request->input('destinationExt');
        $did = $request->input('DID');
        $extension = DB::table('extensions')->insert(
            [
                [
                'context' => 'default',
                'exten' => $did,
                'priority' => 1,
                'app' => 'Dial',
                'appdata' => "SIP/$dest,60,tor"
                ],
                [
                    'context' => 'default',
                    'exten' => $did,
                    'priority' => 2,
                    'app' => 'Hangup',
                    'appdata' => ''
                ],
            ]
        );
        if($extension) {
            return redirect()->back()->withSuccess('DID Added');
        } else {
            // the query failed
        }
    }

    public function addExtension(Request $request)
    {
        $account = $request->input('extension');
        $callerid = $request->input('name')." <".$request->input('outboundcid').">";
        $secret = $request->input('secret');
        $selectedPrefix = $request->input('customerPrefix');

        DB::table('ps_aors')->insert(
            [
                'id' => $account,
                'max_contacts' => 2,
                'qualify_frequency' => 30
            ]
        );
        DB::table('ps_auths')->insert(
            [
                'id' => $account,
                'auth_type' => 'userpass',
                'password' => $secret,
                'username' => '$account'
            ]
        );
        DB::table('ps_endpoints')->insert(
            [
                'id' => $account,
                'transport' => 'transport-udp',
                'aors'  => $account,
                'auth' => $account,
                'context' => 'default',
                'disallow' => 'all',
                'allow' => 'ulaw,alaw',
                'direct_media' => 'yes',
                'deny' => '0.0.0.0/0',
                'permit' => '0.0.0.0/0',
                'mailboxes' => $account.'@default'
            ]
        );
        $dialplan = DB::table('extensions')->where(['exten' => "_".$selectedPrefix."X."])->first();
        if (!$dialplan) {
            $extension = DB::table('extensions')->insert(
                [
                    [
                        'context' => 'default',
                        'exten' => "_".$selectedPrefix."X.",
                        'priority' => 1,
                        'app' => 'Dial',
                        'appdata' => 'SIP/${EXTEN}@us4telecoms,60,tor'
                    ],
                    [
                        'context' => 'default',
                        'exten' => "_".$selectedPrefix."X.",
                        'priority' => 2,
                        'app' => 'Hangup',
                        'appdata' => ''
                    ]
                ]
            );
        }
        $sippeers = DB::table('sippeers')->insert(
            [
                'name' => $account,
                'host' => 'dynamic',
                'type' => 'peer',
                'context' => 'default',
                'deny' => '0.0.0.0/0',
                'permit' => '0.0.0.0/0',
                'secret' => $secret,
                'transport' => 'udp',
                'disallow' => 'all',
                'allow' => 'ulaw,alaw',
                'directmedia' => 'nonat',
                'language' => 'en',
                'accountcode' => $selectedPrefix,
                'mailbox' => $account.'@default'
            ]
        );
        if($sippeers) {
            return redirect()->back()->withSuccess('Extension added');
        } else {
            // the query failed
        }

    }
}
