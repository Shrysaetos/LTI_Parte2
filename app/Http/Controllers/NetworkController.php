<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as IlluRequest;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class NetworkController extends Controller
{
    public function getNetworks(){
    	$client = new \GuzzleHttp\Client();
    	$url = 'http://46.101.65.213:9696/v2.0/networks';
    	$token = 'gAAAAABc3wUblswZ0JsUcXX9dm5RMd3um_jp8iiNZVkcS0Y1D1WNBPBHoM_wScuOyr-XPV8dGpApglx2i0giXLYEESpUpLNdyy-zksmd9rRNYNxcQFkfg8QUlaVXnZETIbrRvs3zI3zA2uKbpxXCCCOtZOjGeGqrEZHNlROapodVujk3cNZvHjY';

    	
    	$response = $client->request('GET', $url, [
    		'headers' => [
    			'x-auth-token' => $token,
    		]
		]);


		return $response;
    }
}
