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

        //action=add&extdisplay=&action=add&extdisplay=&tech=pjsip&hardware=generic&extension=23333&name=Vic+Test&outboundcid=&emergency_cid=&devinfo_secret=616510df3d7dd1159cdfee8bfa3f5ffa&userman_directory=1&userman_assign=add&userman_password=d4a9579dd3bda79d6013423efd053311&userman_group%5B%5D=1&vm=disabled&vmx_option_0_number=&vmx_option_0_system_default=checked&fmfm_ddial=disabled&fmfm_pre_ring=7&fmfm_strategy=ringallv2-prim&fmfm_grptime=20&fmfm_grplist=&fmfm_annmsg_id=&fmfm_ringing=Ring&fmfm_grppre=&fmfm_dring=&fmfm_rvolume=&fmfm_needsconf=disabled&fmfm_changecid=default&gotofmfm=Follow_Me&Announcementsfmfm=popover&Call_Flow_Controlfmfm=popover&Call_Recordingfmfm=popover&Conferencesfmfm=popover&Custom_Applicationsfmfm=popover&Directoryfmfm=popover&Extensionsfmfm=from-did-direct%2C5001%2C1&Fax_Recipientfmfm=ext-fax%2C11%2C1&Feature_Code_Adminfmfm=ext-featurecodes%2C*30%2C1&Follow_Mefmfm=ext-local%2C%2Cdest&IVRfmfm=popover&Inbound_Routesfmfm=from-trunk%2C13034810082%2C1&Paging_and_Intercomfmfm=popover&Queuesfmfm=popover&Ring_Groupsfmfm=ext-group%2C6000999%2C1&Sipstationfmfm=sipstation-welcome%2C%24%7BEXTEN%7D%2C1&Terminate_Callfmfm=app-blackhole%2Changup%2C1&Time_Conditionsfmfm=popover&Trunksfmfm=ext-trunk%2C1%2C1&Voicemailfmfm=ext-local%2Cvmb100001%2C1&Voicemail_Blastingfmfm=popover&fmfm_goto=gotofmfm&newdid_name=&newdid=&newdidcid=&devinfo_dtmfmode=rfc4733&devinfo_context=from-internal&devinfo_defaultuser=&devinfo_trustrpid=yes&devinfo_send_connected_line=yes&devinfo_user_eq_phone=no&devinfo_sendrpid=yes&devinfo_qualifyfreq=60&devinfo_transport=&devinfo_avpf=no&devinfo_icesupport=no&devinfo_rtcp_mux=no&devinfo_namedcallgroup=&devinfo_namedpickupgroup=&devinfo_disallow=&devinfo_allow=&devinfo_dial=&devinfo_mailbox=&devinfo_vmexten=&devinfo_accountcode=&devinfo_max_contacts=1&devinfo_media_use_received_transport=no&devinfo_rtp_symmetric=yes&devinfo_rewrite_contact=yes&devinfo_force_rport=yes&devinfo_mwi_subscription=auto&devinfo_aggregate_mwi=yes&devinfo_bundle=no&devinfo_max_audio_streams=1&devinfo_max_video_streams=1&devinfo_media_encryption=no&devinfo_timers=yes&devinfo_direct_media=yes&devinfo_media_encryption_optimistic=no&devinfo_device_state_busy_at=0&devinfo_match=&devinfo_maximum_expiration=7200&devinfo_minimum_expiration=60&devinfo_outbound_proxy=&cid_masquerade=&sipname=&ringtimer=0&rvolume=&cfringtimer=0&concurrency_limit=3&callwaiting=enabled&call_screen=0&intercom=enabled&qnostate=usestate&recording_in_external=recording_in_external%3Ddontcare&recording_out_external=recording_out_external%3Ddontcare&recording_in_internal=recording_in_internal%3Ddontcare&recording_out_internal=recording_out_internal%3Ddontcare&recording_ondemand=recording_ondemand%3Ddisabled&recording_priority=10&in_default_directory=0&dtls_enable=no&dtls_auto_generate_cert=0&dtls_certificate=1&dtls_verify=fingerprint&dtls_setup=actpass&dtls_rekey=0&goto0=&Announcements0=popover&Call_Flow_Control0=popover&Call_Recording0=popover&Conferences0=popover&Custom_Applications0=popover&Directory0=popover&Extensions0=from-did-direct%2C5001%2C1&Fax_Recipient0=ext-fax%2C11%2C1&Feature_Code_Admin0=ext-featurecodes%2C*30%2C1&IVR0=popover&Inbound_Routes0=from-trunk%2C13034810082%2C1&Paging_and_Intercom0=popover&Queues0=popover&Ring_Groups0=ext-group%2C6000999%2C1&Sipstation0=sipstation-welcome%2C%24%7BEXTEN%7D%2C1&Terminate_Call0=app-blackhole%2Changup%2C1&Time_Conditions0=popover&Trunks0=ext-trunk%2C1%2C1&Voicemail0=ext-local%2Cvmb100001%2C1&Voicemail_Blasting0=popover&noanswer_dest=goto0&noanswer_cid=&goto1=&Announcements1=popover&Call_Flow_Control1=popover&Call_Recording1=popover&Conferences1=popover&Custom_Applications1=popover&Directory1=popover&Extensions1=from-did-direct%2C5001%2C1&Fax_Recipient1=ext-fax%2C11%2C1&Feature_Code_Admin1=ext-featurecodes%2C*30%2C1&IVR1=popover&Inbound_Routes1=from-trunk%2C13034810082%2C1&Paging_and_Intercom1=popover&Queues1=popover&Ring_Groups1=ext-group%2C6000999%2C1&Sipstation1=sipstation-welcome%2C%24%7BEXTEN%7D%2C1&Terminate_Call1=app-blackhole%2Changup%2C1&Time_Conditions1=popover&Trunks1=ext-trunk%2C1%2C1&Voicemail1=ext-local%2Cvmb100001%2C1&Voicemail_Blasting1=popover&busy_dest=goto1&busy_cid=&goto2=&Announcements2=popover&Call_Flow_Control2=popover&Call_Recording2=popover&Conferences2=popover&Custom_Applications2=popover&Directory2=popover&Extensions2=from-did-direct%2C5001%2C1&Fax_Recipient2=ext-fax%2C11%2C1&Feature_Code_Admin2=ext-featurecodes%2C*30%2C1&IVR2=popover&Inbound_Routes2=from-trunk%2C13034810082%2C1&Paging_and_Intercom2=popover&Queues2=popover&Ring_Groups2=ext-group%2C6000999%2C1&Sipstation2=sipstation-welcome%2C%24%7BEXTEN%7D%2C1&Terminate_Call2=app-blackhole%2Changup%2C1&Time_Conditions2=popover&Trunks2=ext-trunk%2C1%2C1&Voicemail2=ext-local%2Cvmb100001%2C1&Voicemail_Blasting2=popover&chanunavail_dest=goto2&chanunavail_cid=&pinless=disabled&cxpanel_add_extension=1&cxpanel_auto_answer=0&intercom_override=intercom_override%3Dreject&devinfo_secret_origional=&devinfo_sipdriver=chan_pjsip

        $csv .= "add,$account,,\"$account\",default,0,,,6567347766,,,,,,,,default,$account,pjsip,PJSIP/$account,fixed,$account,\"$account\",,,dontcare,dontcare,dontcare,dontcare,disabled,10,disabled,enabled,$account,3,,yes,,no,no,\"$account <$account>\",from-internal,,0,yes,,rfc4733,yes,no,$account@device,,1,1,1,7200,no,no,yes,60,auto,,,,60,yes,no,yes,$secret,yes,pai,chan_pjsip,yes,,yes,yes,,,,,,,,,,,,,ENABLED,ringallv2-prim,20,,$account,,\"ext-local,$account,dest\",,,,,Ring,7,default,,yes,default,,,yes,$account,adjolin@gmail.com,,attach=no|saycid=no|envelope=no|delete=no,no,no,,,,1,,,";
        Storage::put('extensions.csv',$csv);
        $connection = ssh2_connect('104.237.1.167', 22);
        if (ssh2_auth_password($connection, 'us4deliver', '0*6x7V6T?Z5hqQh')) {
            ssh2_scp_send($connection, '/var/www/html/opensip_admin/storage/app/extensions.csv', '/home/us4deliver/extensions.csv', 0644);
        }

    }
}
