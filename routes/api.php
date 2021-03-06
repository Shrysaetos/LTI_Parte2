<?php 

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::resource('server','ServerController');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/getUrl/{instanceID}', 'InstanceController@getUrl');

Route::post('login', 'LoginControllerAPI@login'); 
Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');

//Volumes
	//Create
Route::post('createVolume/{name}/{description}/{size}/{image}', 'VolumeController@createVolume');
	//Delete
Route::delete('deleteVolume/{volumeID}', 'VolumeController@deleteVolume');
	//List
Route::get('/volumes', 'VolumeController@listVolumes');
Route::get('/listImages', 'VolumeController@listImages');

///{server}/{username}/{password}/{project}
//Key Pairs
Route::get('keypairs', 'KeypairController@getKeypairs');
	//create
Route::post('createKeypair/{name}/{type}/{publicKey}', 'KeypairController@createKeypair');
	//delete
Route::delete('deleteKeypair/{keypairName}', 'KeypairController@deleteKeypair');

//Images
Route::get('images', 'ImageController@getImages');
	//create
Route::post('createImage/{name}/{format}/{location}/{disk}/{ram}/{visibility}/{protected}/', 'ImageController@createImage');
	//delete
Route::delete('deleteImage/{imageID}', 'ImageController@deleteImage');

//Instances
Route::get('instances', 'InstanceController@getInstances');
	//create
Route::get('zones', 'InstanceController@getZones');
Route::post('createInstance/{name}/{description}/{zone}/{image}/{size}/{flavor}/{networkId}/{networkName}/{keypair}/{volume}', 'InstanceController@createInstance');
	//delete
Route::delete('deleteInstance/{instanceID}', 'InstanceController@deleteInstance');

//Flavors
Route::get('flavors', 'FlavorController@getFlavors');

//Networks
Route::get('networks', 'NetworkController@getNetworks');
Route::get('subnets', 'NetworkController@getSubnets');
	//delete
Route::delete('deleteNetwork/{networkId}', 'NetworkController@deleteNetwork');
	//create
Route::post('createNetwork/{networkName}/{adminState}/{subnetName}/{address}/{gateway}/{dhcp}', 'NetworkController@createNetwork');

//Security Groups
Route::get('security_groups', 'SecurityGroupController@getSecurityGroups');
	//create
Route::post('createSecurityGroup/{name}/{description}', 'SecurityGroupController@createSecurityGroup');
	//delete
Route::delete('deleteSecurityGroup/{id}','SecurityGroupController@deleteSecurityGroup');

//Floating IPs
Route::get('floatingips', 'FloatingIPController@getFloatingIPs');
	//create
Route::post('createFloatingIp/{network}/{description}', 'FloatingIPController@createFloatingIp');
	//delete
Route::delete('deleteFloatingIP/{id}','FloatingIPController@deleteFloatingIP');

Route::post('createTempToken/{server}/{username}/{password}', 'ServerController@createTempToken');
Route::get('getProjectList/{token}/{server}/{userId}', 'ServerController@getProjectList');
Route::post('getToken/{server}/{username}/{password}/{project}', 'ServerController@getToken');