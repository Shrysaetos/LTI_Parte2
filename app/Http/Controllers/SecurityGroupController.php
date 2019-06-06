<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as IlluRequest;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
//database
use App\Server;

class SecurityGroupController extends Controller
{


    public function getSecurityGroups(){
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
    	$url = $serverUrl.':9696/v2.0/security-groups';

    	
    	$response = $client->request('GET', $url, [
    		'headers' => [
    			'x-auth-token' => $tempToken,
    		]
		]);


		return $response;
    }

    public function getSecurityGroupRules($id){
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
        $url = $serverUrl.':9696/v2.0/security-groups/' + $id;

        
        $response = $client->request('GET', $url, [
            'headers' => [
                'x-auth-token' => $tempToken,
            ]
        ]);


        return $response;
    }
}