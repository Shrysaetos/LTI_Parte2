<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request as IlluRequest;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
//database
use App\Server;

class KeypairController extends Controller
{

    

    public function getKeypairs(){
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
    	$url = $serverUrl.'/compute/v2.1/os-keypairs';

    	
    	$response = $client->request('GET', $url, [
    		'headers' => [
    			'x-auth-token' => $tempToken,
    		]
		]);


		return $response;
    }

    public function createKeypair($name, $type, $publicKey){
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
        $url = $serverUrl.'/compute/v2/os-keypairs';

        if($type == 'SSH key'){ //body ssh
            if($publicKey == 'null'){
                $body = '{ "keypair": { "name": "'.$name.'", "type": "ssh", "user_id": "fake" } }';
            }else{
                $body = '{ "keypair": { "name": "'.$name.'", "type": "ssh", "public_key": '.$publicKey.', "user_id": "fake" } }';
            }
        }
        else{ //type == x509
            if($publicKey == 'null'){
                $body = '{ "keypair": { "name": "'.$name.'", "type": "X509", "user_id": "fake" } }';
            }else{
                $body = '{ "keypair": { "name": "'.$name.'", "type": "X509", "public_key": '.$publicKey.', "user_id": "fake" } }';
            }
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

    public function deleteKeypair($keypairName){
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
        $url = $serverUrl.'/compute/v2/os-keypairs/'.$keypairName;

        $client->request('DELETE', $url, [
            'headers' => [
                'x-auth-token' => $tempToken,
            ]
        ]);
    }
}