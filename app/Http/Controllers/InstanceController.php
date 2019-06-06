<?php 
 
namespace App\Http\Controllers;

use Illuminate\Http\Request as IlluRequest;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
//database
use App\Server;

class InstanceController extends Controller
{


    public function getInstances(){
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
        $url = $serverUrl.'/compute/v2.1/servers/detail';

        
        $response = $client->request('GET', $url, [
            'headers' => [
                'x-auth-token' => $tempToken,
            ]
        ]);


        return $response;
    }

    public function getZones(){
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
        $url = $serverUrl.'/compute/v2/os-availability-zone';

        $response = $client->request('GET', $url, [
            'headers' => [
                'x-auth-token' => $tempToken,
            ]
        ]);

        return $response;
    }

    public function createInstance($name, $description, $zone, $image, $size, $flavor, $networkId, $networkName, $keypair, $volume)
    {
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
        $url = $serverUrl.'/compute/v2/servers';

        if ($image != 'NULL') { //criar volume para instancia
            $body = '{ "server" : { "name" : '.$name.', "description" : '.$description.', "key_name" : '.$keypair.', "availability_zone": '.$zone.', "flavorRef" : '.$flavor.', "networks" : [{ "uuid" : '.$networkId.', "tag": '.$networkName.' }], "block_device_mapping_v2": [{ "uuid": '.$image.', "source_type": "image", "destination_type": "volume", "boot_index": 0, "volume_size": '.$size.', "tag": "createdByApp" }] } }';
        }else{ // utilizar volume
            $body = '{
                "server" : {
                    "name" : '.$name.',
                    "description" : '.$description.',
                    "key_name" : '.$keypair.',
                    "availability_zone": '.$zone.',
                    "flavorRef" : '.$flavor.',
                    "networks" : [{
                        "uuid" : '.$networkId.',
                        "tag": '.$networkName.'
                    }],
                    "block_device_mapping_v2": [{
                        "uuid": "'.$volume.'",
                        "source_type": "volume",
                        "destination_type": "volume",
                        "boot_index": 0
                    }]
                }
            }
            ';
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

    public function deleteInstance($instanceID){
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
        $url = $serverUrl.'/compute/v2/servers/'.$instanceID;

        $client->request('DELETE', $url, [
            'headers' => [
                'x-auth-token' => $tempToken,
            ]
        ]);
    }

    public function getUrl($instanceID){
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
        $url = $serverUrl.'/compute/v2/servers/'.$instanceID.'/action';

        $body = '{
                    "os-getVNCConsole": {
                        "type": "novnc"
                    }
                }';

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
