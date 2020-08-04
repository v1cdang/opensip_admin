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

        $csv = "extension,password,name,voicemail,ringtimer,noanswer,recording,outboundcid,sipname,noanswer_cid,busy_cid,chanunavail_cid,noanswer_dest,busy_dest,chanunavail_dest,mohclass,id,tech,dial,devicetype,user,description,emergency_cid,hint_override,recording_in_external,recording_out_external,recording_in_internal,recording_out_internal,recording_ondemand,recording_priority,answermode,intercom,cid_masquerade,concurrency_limit,accountcode,aggregate_mwi,allow,avpf,bundle,callerid,context,defaultuser,device_state_busy_at,direct_media,disallow,dtmfmode,force_rport,icesupport,mailbox,match,max_audio_streams,max_contacts,max_video_streams,maximum_expiration,media_encryption,media_encryption_optimistic,media_use_received_transport,minimum_expiration,mwi_subscription,namedcallgroup,namedpickupgroup,outbound_proxy,qualifyfreq,rewrite_contact,rtcp_mux,rtp_symmetric,secret,send_connected_line,sendrpid,sipdriver,timers,transport,trustrpid,user_eq_phone,canreinvite,deny,encryption,force_avp,host,nat,permit,port,qualify,sessiontimers,type,videosupport,callwaiting_enable,findmefollow_strategy,findmefollow_grptime,findmefollow_grppre,findmefollow_grplist,findmefollow_annmsg_id,findmefollow_postdest,findmefollow_dring,findmefollow_needsconf,findmefollow_remotealert_id,findmefollow_toolate_id,findmefollow_ringing,findmefollow_pre_ring,findmefollow_voicemail,findmefollow_calendar_id,findmefollow_calendar_match,findmefollow_changecid,findmefollow_fixedcid,findmefollow_enabled,voicemail_enable,voicemail_vmpwd,voicemail_email,voicemail_pager,voicemail_options,voicemail_same_exten,disable_star_voicemail,vmx_unavail_enabled,vmx_busy_enabled,vmx_temp_enabled,vmx_play_instructions,vmx_option_0_number,vmx_option_1_number,vmx_option_2_number\n";


        $csv .= "add,$account,,\"$account\",default,0,,,6567347766,,,,,,,,default,$account,pjsip,PJSIP/$account,fixed,$account,\"$account\",,,dontcare,dontcare,dontcare,dontcare,disabled,10,disabled,enabled,$account,3,,yes,,no,no,"$account <$account>",from-internal,,0,yes,,rfc4733,yes,no,$account@device,,1,1,1,7200,no,no,yes,60,auto,,,,60,yes,no,yes,$secret,yes,pai,chan_pjsip,yes,,yes,yes,,,,,,,,,,,,,ENABLED,ringallv2-prim,20,,$account,,\"ext-local,$account,dest\",,,,,Ring,7,default,,yes,default,,,yes,$account,adjolin@gmail.com,,attach=no|saycid=no|envelope=no|delete=no,no,no,,,,1,,,";
        Storage::put('extensions.csv',$csv);
        $connection = ssh2_connect('104.237.1.167', 22);
        if (ssh2_auth_password($connection, 'us4deliver', '0*6x7V6T?Z5hqQh')) {
            ssh2_scp_send($connection, '/var/www/html/opensip_admin/storage/app/extensions.csv', '/home/us4deliver/extensions.csv', 0644);
        }

    }
}
