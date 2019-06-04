<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Server;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servers = Server::all();
        return response()->json(["servers"=>$servers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $server = Server::create($request->all());
        return response()->json(['server'=>$server]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    //unscoped token
    public function createTempToken($server, $username, $password){

        $client = new \GuzzleHttp\Client();
        $serverFinal = str_replace("]","/",$server);
        $url = $serverFinal.'/identity/v3/auth/tokens';

        $body = '{
                    "auth": {
                        "identity": {
                            "methods": [
                                "password"
                            ],
                            "password": {
                                "user": {
                                    "domain": {
                                        "name": "default"
                                    },
                                    "name": "'.$username.'",
                                    "password": "'.$password.'"
                                }
                            }
                        }
                    }
                }';

        $response = $client->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $body
        ]);
        $token = $response->getHeader('X-Subject-Token')[0];

        $userId = $response->getBody();
        $userId = json_decode($userId, true);
        $userId = $userId['token'];
        $userId = $userId['user'];
        $userId = $userId['id'];

        $data = [
            'token' => $token,
            'userId' => $userId
        ];

        return $data;

    }

    public function getProjectList($token, $server, $userId){
        $client = new \GuzzleHttp\Client();
        $serverFinal = str_replace("]","/",$server);
        $url = $serverFinal.'/identity/v3/users/'.$userId.'/projects';

        
        $response = $client->request('GET', $url, [
            'headers' => [
                'x-auth-token' => $token,
            ]
        ]);


        return $response;
    }
}
