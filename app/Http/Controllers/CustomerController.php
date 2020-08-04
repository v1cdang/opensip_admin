<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


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

        $csv = "action,extension,name,cid_masquerade,sipname,outboundcid,ringtimer,callwaiting,call_screen,pinless,password,emergency_cid,tech,hardware,devinfo_channel,devinfo_secret,devinfo_notransfer,devinfo_dtmfmode,devinfo_canreinvite,devinfo_context,devinfo_immediate,devinfo_signalling,devinfo_echocancel,devinfo_echocancelwhenbrdiged,devinfo_echotraining,devinfo_busydetect,devinfo_busycount,devinfo_callprogress,devinfo_host,devinfo_type,devinfo_nat,devinfo_port,devinfo_qualify,devinfo_callgroup,devinfo_pickupgroup,devinfo_disallow,devinfo_allow,devinfo_dial,devinfo_accountcode,devinfo_mailbox,devinfo_deny,devinfo_permit,devicetype,deviceid,deviceuser,description,dictenabled,dictformat,dictemail,langcode,record_in,record_out,vm,vmpwd,email,pager,attach,saycid,envelope,delete,options,vmcontext,vmx_state,vmx_unavail_enabled,vmx_busy_enabled,vmx_play_instructions,vmx_option_0_sytem_default,vmx_option_0_number,vmx_option_1_system_default,vmx_option_1_number,vmx_option_2_number,account,ddial,pre_ring,strategy,grptime,grplist,annmsg_id,ringing,grppre,dring,needsconf,remotealert_id,toolate_id,postdest,faxenabled,faxemail\n";


        $csv .= "add,$account,$account,$account,,,0,enabled,0,,,,pjsip,,,$secret,,rfc2833,no,from-internal,,,,,,,,,dynamic,friend,yes,5060,yes,,,,,PJSIP/$account,,$account@device,0.0.0.0/0.0.0.0,0.0.0.0/0.0.0.0,fixed,,$account,$account,disabled,ogg,,,Adhoc,Adhoc,enabled,1234,j.doe@foo.bar,j.doe.pager@foo.bar,attach=no,saycid=no,envelope=no,delete=no,,default,,,,,checked,,,,,$account,CHECKED,0,ringallv2,20,$account-552244,2,Ring,TEST,,CHECKED,0,0,\"ext-local,vmu552244,1\",,";
        Storage::put('extensions.csv',$csv);
        $connection = ssh2_connect('104.237.1.167', 22);
        if (ssh2_auth_password($connection, 'us4deliver', '0*6x7V6T?Z5hqQh')) {
            ssh2_scp_send($connection, '/var/www/html/opensip_admin/storage/app/extensions.csv', '/home/us4deliver/extensions.csv', 0644);
        }

    }
}
