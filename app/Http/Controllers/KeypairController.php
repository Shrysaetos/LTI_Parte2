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

    public function getToken(){
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
        $url = $serverUrl.'/identity/v3/auth/tokens';
        $body = '{ "auth": { "identity": { "methods": [ "password" ], "password": { "user": { "name": "'.$username.'", "domain": { "name": "Default" }, "password": "'.$password.'" } } }, "scope": { "project": { "id": "'.$project.'" } } } }';
        $response = $client->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $body
        ]);
        $token = $response->getHeader('X-Subject-Token')[0];
        return $token;
    }

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
    	$token = $this->getToken();

    	
    	$response = $client->request('GET', $url, [
    		'headers' => [
    			'x-auth-token' => $token,
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
                'x-auth-token' => $this->getToken(),
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
        $token = $this->getToken();

        $client->request('DELETE', $url, [
            'headers' => [
                'x-auth-token' => $token,
            ]
        ]);
    }
}