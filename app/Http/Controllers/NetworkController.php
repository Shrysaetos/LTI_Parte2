<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as IlluRequest;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
//database
use App\Server;

class NetworkController extends Controller
{
     
    public function getNetworks(){
        //************************
        //      DATABASE
        //************************
        $servers = Server::all();
        $server = $servers[count($servers)-1];
        $serverUrl = $server['server'];
        $username = $server['username'];
        $password = $server['password'];
        $tempToken = $server['tempToken'];
        $project = $server['project'];
        //************************


    	$client = new \GuzzleHttp\Client();
    	$url = $serverUrl.':9696/v2.0/networks';

    	
    	$response = $client->request('GET', $url, [
    		'headers' => [
    			'x-auth-token' => $tempToken,
    		]
		]);


		return $response;
    }

    public function getSubnets(){
        //************************
        //      DATABASE
        //************************
        $servers = Server::all();
        $server = $servers[count($servers)-1];
        $serverUrl = $server['server'];
        $username = $server['username'];
        $password = $server['password'];
        $tempToken = $server['tempToken'];
        $project = $server['project'];
        //************************


        $client = new \GuzzleHttp\Client();
        $url = $serverUrl.':9696/v2.0/subnets';

        
        $response = $client->request('GET', $url, [
            'headers' => [
                'x-auth-token' => $tempToken,
            ]
        ]);


        return $response;
    }

    public function deleteNetwork($networkId){
        //************************
        //      DATABASE
        //************************
        $servers = Server::all();
        $server = $servers[count($servers)-1];
        $serverUrl = $server['server'];
        $username = $server['username'];
        $password = $server['password'];
        $tempToken = $server['tempToken'];
        $project = $server['project'];
        //************************


        $client = new \GuzzleHttp\Client();
        $url = $serverUrl.':9696/v2.0/networks/'.$networkId;

        $client->request('DELETE', $url, [
            'headers' => [
                'x-auth-token' => $tempToken,
            ]
        ]);
    }

    public function createNetwork($networkName, $adminState, $subnetName, $address, $gateway, $dhcp){
        //************************
        //      DATABASE
        //************************
        $servers = Server::all();
        $server = $servers[count($servers)-1];
        $serverUrl = $server['server'];
        $username = $server['username'];
        $password = $server['password'];
        $tempToken = $server['tempToken'];
        $project = $server['project'];
        //************************


        $client = new \GuzzleHttp\Client();
        $url = $serverUrl.':9696/v2.0/networks';

        $body = '{
                    "network": {
                        "name": "'.$networkName.'",
                        "admin_state_up": '.$adminState.',
                        "mtu": 1400 
                    }
                }';
        
        $response = $client->request('POST', $url, [
            'headers' => [
                'x-auth-token' => $tempToken,
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
        //************************
        //      DATABASE
        //************************
        $servers = Server::all();
        $server = $servers[count($servers)-1];
        $serverUrl = $server['server'];
        $username = $server['username'];
        $password = $server['password'];
        $tempToken = $server['tempToken'];
        $project = $server['project'];
        //************************


        $client = new \GuzzleHttp\Client();
        $url = $serverUrl.':9696/v2.0/subnets';

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
                'x-auth-token' => $tempToken,
                'Content-Type' => 'application/json',
            ],
            'body' => $body
        ]);

        return $response;



        
    }
}
