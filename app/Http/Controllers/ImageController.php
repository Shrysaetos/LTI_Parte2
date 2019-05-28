<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as IlluRequest;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class ImageController extends Controller
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

    public function getImages(){
    	$client = new \GuzzleHttp\Client();
    	$url = 'http://46.101.65.213/image/v2/images';
    	$token = $this->getToken();

    	
    	$response = $client->request('GET', $url, [
    		'headers' => [
    			'x-auth-token' => $token,
    		]
		]);


		return $response;
    }

    public function createImage($name, $format, $location, $disk, $ram, $visibility, $protected){
        $client = new \GuzzleHttp\Client();
        $url = '46.101.65.213/image/v2/images';

        $locationFinal = str_replace("]","/",$location);
        
        if ($ram == 'null' && $disk == 'null') {
            $body = '{ "disk_format": '.$format.', "name": '.$name.', "visibility": '.$visibility.', "protected ": '.$protected.', "location": '.$locationFinal.' }';
        }else{
            //se nao forem os dois nulos o body vai ser completo
            $body = '{ "disk_format": '.$format.', "name": '.$name.', "min_disk": '.$disk.', "min_ram": '.$ram.', "visibility": '.$visibility.', "protected ": '.$protected.', "location": '.$locationFinal.' }';

            //o body muda caso apenas um dois dois seja null
            if ($disk == 'null') {
            $body = '{ "disk_format": '.$format.', "name": '.$name.', "min_ram": '.$ram.', "visibility": '.$visibility.', "protected ": '.$protected.', "location": '.$locationFinal.' }';
            }
            if ($ram == 'null') {
                $body = '{ "disk_format": '.$format.', "name": '.$name.', "min_disk": '.$disk.', "visibility": '.$visibility.', "protected ": '.$protected.', "location": '.$locationFinal.' }';
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


    public function deleteImage($imageID){
        $client = new \GuzzleHttp\Client();
        $url = '46.101.65.213/image/v2/images/'.$imageID;
        $token = $this->getToken();

        $client->request('DELETE', $url, [
            'headers' => [
                'x-auth-token' => $token,
            ]
        ]);
    }
}
