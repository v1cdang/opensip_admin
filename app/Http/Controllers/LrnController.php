<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LrnController extends Controller
{
    public function index(Request $request, $clientPrefix, $phonenumber)
    {
        $this->lrn_connect();
    }

    public function lrn_connect()
    {
        $switch_host = 'lrn.summitsystemsus.com';
        $switch_port = '55666';
        global $sock, $switch_host, $switch_port;
        if($sock === false || ($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false || socket_connect($sock, $switch_host, $switch_port) === false)
            die('Error connecting to LRN Server. Please try again later.');
        return;
    }
}
