<?php  

namespace App\Http\Controllers;

use Illuminate\Http\Request as IlluRequest;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
//database
use App\Server;



class VolumeController extends Controller
{    
    

   public function createVolume($name, $description, $size, $image){
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
        $url = $serverUrl.'/volume/v3/'.$project.'/volumes';

        if ($image == 'NO_IMAGE') {
            $body = '{ "volume": { "size":'.$size.', "availability_zone": null, "source_volid": null, "description": '.$description.', "multiattach": false, "snapshot_id": null, "backup_id": null, "name": '.$name.', "imageRef": null, "volume_type": null, "metadata": {}, "consistencygroup_id": null } }';
        }else{

        $body = '{ "volume": { "size":'.$size.', "availability_zone": null, "source_volid": null, "description": '.$description.', "multiattach": false, "snapshot_id": null, "backup_id": null, "name": '.$name.', "imageRef": "'.$image.'", "volume_type": null, "metadata": {}, "consistencygroup_id": null } }';
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


    public function listVolumes(){
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
    	$url = $serverUrl.'/volume/v3/'.$project.'/volumes/detail';

    	
    	$response = $client->request('GET', $url, [
    		'headers' => [
    			'x-auth-token' => $tempToken,
    		]
		]);


		return $response;
    }

    public function listImages(){
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
        $url = $serverUrl.'/image/v2/images';

        $response = $client->request('GET', $url, [
            'headers' => [
                'x-auth-token' => $tempToken,
            ]
        ]);

        return $response;
    }

    public function deleteVolume($volumeID){
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
        $url = $serverUrl.'/volume/v3/'.$project.'/volumes/'.$volumeID;

        $client->request('DELETE', $url, [
            'headers' => [
                'x-auth-token' => $tempToken,
            ]
        ]);
    }




        
    

}




?>