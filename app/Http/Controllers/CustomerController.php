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

    public function setCustomerRatesForm()
    {
        $prefixes = DB::table('cc_card')->select('prefix')->get();

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
        return view('addExtension', ['newpassword' => $password]);
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

    public function addExtension(Request $request)
    {
        $account = $request->input('extension');
        $callerid = $request->input('name')." <".$request->input('outboundcid').">";
        $secret = $request->input('secret');

        $dataUser = [
            [
                'extension'=>$account,
                'password'=>'',
                'name'=>$request->input('name'),
                'voicemail'=>'novm',
                'ringtimer'=>'0',
                'noanswer'=>'',
                'recording'=>'',
                'outboundcid'=>$callerid,
                'sipname'=>'',
                'noanswer_cid'=>'',
                'busy_cid'=>'',
                'chanunavail_cid'=>'',
                'noanswer_dest'=>'',
                'busy_dest'=>'',
                'chanunavail_dest'=>'',
                'mohclass'=>'default'
            ]
        ];

        $data = [
            ['id' => $account, 'keyword' => 'account', 'data' => $account, 'flags' => '43'],
            ['id' => $account, 'keyword' => 'accountcode','data' => '', 'flags' => '21'],
            ['id' => $account, 'keyword' => 'aggregate_mwi','data' => 'yes', 'flags' => '28'],
            ['id' => $account, 'keyword' => 'allow','data' => '', 'flags' => '18'],
            ['id' => $account, 'keyword' => 'avpf','data' => 'no', 'flags' => '12'],
            ['id' => $account, 'keyword' => 'bundle','data' => 'no', 'flags' => '29'],
            ['id' => $account, 'keyword' => 'callerid','data' => $callerid, 'flags' => '44'],
            ['id' => $account, 'keyword' => 'context','data' => 'from-internal', 'flags' => '4'],
            ['id' => $account, 'keyword' => 'defaultuser','data' => '', 'flags' => '5'],
            ['id' => $account, 'keyword' => 'device_state_busy_at','data' => '0', 'flags' => '36'],
            ['id' => $account, 'keyword' => 'dial','data' => 'PJSIP/' . $account, 'flags' => '19'],
            ['id' => $account, 'keyword' => 'direct_media','data' => 'yes', 'flags' => '34'],
            ['id' => $account, 'keyword' => 'disallow','data' => '', 'flags' => '17'],
            ['id' => $account, 'keyword' => 'dtmfmode','data' => 'rfc4733', 'flags' => '3'],
            ['id' => $account, 'keyword' => 'force_rport','data' => 'yes', 'flags' => '26'],
            ['id' => $account, 'keyword' => 'icesupport','data' => 'no', 'flags' => '13'],
            ['id' => $account, 'keyword' => 'mailbox','data' => $account.'@device', 'flags' => '20'],
            ['id' => $account, 'keyword' => 'match','data' => '', 'flags' => '37'],
            ['id' => $account, 'keyword' => 'max_audio_streams','data' => '1', 'flags' => '30'],
            ['id' => $account, 'keyword' => 'max_contacts','data' => '1', 'flags' => '22'],
            ['id' => $account, 'keyword' => 'max_video_streams','data' => '1', 'flags' => '31'],
            ['id' => $account, 'keyword' => 'maximum_expiration','data' => '7200', 'flags' => '38'],
            ['id' => $account, 'keyword' => 'media_encryption','data' => 'no', 'flags' => '32'],
            ['id' => $account, 'keyword' => 'media_encryption_optimistic','data' => 'no', 'flags' => '35'],
            ['id' => $account, 'keyword' => 'media_use_received_transport','data' => 'yes', 'flags' => '23'],
            ['id' => $account, 'keyword' => 'minimum_expiration','data' => '60', 'flags' => '39'],
            ['id' => $account, 'keyword' => 'mwi_subscription','data' => 'auto', 'flags' => '27'],
            ['id' => $account, 'keyword' => 'namedcallgroup','data' => '', 'flags' => '15'],
            ['id' => $account, 'keyword' => 'namedpickupgroup','data' => '', 'flags' => '16'],
            ['id' => $account, 'keyword' => 'outbound_proxy','data' => '', 'flags' => '40'],
            ['id' => $account, 'keyword' => 'qualifyfreq','data' => '60', 'flags' => '10'],
            ['id' => $account, 'keyword' => 'rewrite_contact','data' => 'yes', 'flags' => '25'],
            ['id' => $account, 'keyword' => 'rtcp_mux','data' => 'no', 'flags' => '14'],
            ['id' => $account, 'keyword' => 'rtp_symmetric','data' =>'yes', 'flags' => '24'],
            ['id' => $account, 'keyword' => 'secret','data' => $secret, 'flags' => '2'],
            ['id' => $account, 'keyword' => 'secret_origional','data' => $secret, 'flags' => '41'],
            ['id' => $account, 'keyword' => 'send_connected_line','data' => 'yes', 'flags' => '7'],
            ['id' => $account, 'keyword' => 'sendrpid','data' => 'pai', 'flags' => '9'],
            ['id' => $account, 'keyword' => 'sipdriver','data' => 'chan_pjsip', 'flags' => '42'],
            ['id' => $account, 'keyword' => 'timers','data' => 'yes', 'flags' => '33'],
            ['id' => $account, 'keyword' => 'transport','data' => '', 'flags' => '11'],
            ['id' => $account, 'keyword' => 'trustrpid','data' => 'yes', 'flags' => '6'],
            ['id' => $account, 'keyword' => 'user_eq_phone','data' => 'yes', 'flags' => '8']
        ];
        DB::connection('mysql2')->table('users')->insert($dataUser);
        DB::connection('mysql2')->table('sip')->insert($data);


    }
}
