<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as IlluRequest;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class NetworkController extends Controller
{

    public function getToken(){
        $client = new \GuzzleHttp\Client();
        $url = 'http://46.101.65.213/identity/v3/auth/tokens';
        $body = '{ "auth": { "identity": { "methods": [ "password" ], "password": { "user": { "name": "D-D", "domain": { "name": "Default" }, "password": "D-D" } } }, "scope": { "project": { "id": "58293217310f47b69785e31aaaad5987" } } } }';
        $response = $client->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $body
        ]);
        $token = $response->getHeader('X-Subject-Token')[0];
        return $token;
    }

    public function getNetworks(){
    	$client = new \GuzzleHttp\Client();
    	$url = 'http://46.101.65.213:9696/v2.0/networks';
    	$token = $this->getToken();

    	
    	$response = $client->request('GET', $url, [
    		'headers' => [
    			'x-auth-token' => $token,
    		]
		]);


		return $response;
    }

    public function getSubnets(){
        $client = new \GuzzleHttp\Client();
        $url = 'http://46.101.65.213:9696/v2.0/subnets';
        $token = $this->getToken();

        
        $response = $client->request('GET', $url, [
            'headers' => [
                'x-auth-token' => $token,
            ]
        ]);


        return $response;
    }

    public function deleteNetwork($networkId){
        $client = new \GuzzleHttp\Client();
        $url = '46.101.65.213:9696/v2.0/networks/'.$networkId;
        $token = $this->getToken();

        $client->request('DELETE', $url, [
            'headers' => [
                'x-auth-token' => $token,
            ]
        ]);
    }

    public function createNetwork($networkName, $adminState, $subnetName, $address, $gateway, $dhcp){

        $client = new \GuzzleHttp\Client();
        $url = 'http://46.101.65.213:9696/v2.0/networks';

        $body = '{
                    "network": {
                        "name": "'.$networkName.'",
                        "admin_state_up": '.$adminState.',
                        "mtu": 1400 
                    }
                }';
        
        $response = $client->request('POST', $url, [
            'headers' => [
                'x-auth-token' => $this->getToken(),
                'Content-Type' => 'application/json',
            ],
            'body' => $body
        ]);

        //

        $json = $response->getBody(); 
        $json = json_decode($json, true);
        $jsonTemp = $json['network'];
        $jsonTemp = $jsonTemp['id'];

        if ($subnetName != 'null') {
            $response = $this->createSubNet($jsonTemp, $subnetName, $address, $gateway, $dhcp);
            return $response;
        }else{
            return $response;
        }

    }

    public function createSubNet($networkId, $subnetName, $address, $gateway, $dhcp){
        $client = new \GuzzleHttp\Client();
        $url = 'http://46.101.65.213:9696/v2.0/subnets';

        $addressFinal = str_replace("]","/",$address);

        if($gateway == 'null'){
            $body = '{
                    "subnet": {
                        "network_id": "'.$networkId.'",
                        "ip_version": 4,
                        "cidr": "'.$addressFinal.'",
                        "name": "'.$subnetName.'",
                        "enable_dhcp": '.$dhcp.'
                    }
                }';
        }else{
            $body = '{
                    "subnet": {
                        "network_id": "'.$networkId.'",
                        "ip_version": 4,
                        "cidr": "'.$addressFinal.'",
                        "name": "'.$subnetName.'",
                        "enable_dhcp": '.$dhcp.',
                        "gateway_ip": "'.$gateway.'"
                    }
                }';
        }
        
        $response = $client->request('POST', $url, [
            'headers' => [
                'x-auth-token' => $this->getToken(),
                'Content-Type' => 'application/json',
            ],
            'body' => $body
        ]);

        return $response;



        
    }
}
