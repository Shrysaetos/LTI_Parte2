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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/getUrl/{instanceID}', 'InstanceController@getUrl');

Route::post('login', 'LoginControllerAPI@login'); 
Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');

//Volumes
	//Create
Route::post('createVolume', 'VolumeController@createVolume');
Route::post('createVolume/{name}/{description}/{size}/{image}', 'VolumeController@createVolume');
	//Delete
Route::delete('deleteVolume/{volumeID}', 'VolumeController@deleteVolume');

	//List
Route::get('/volumes', 'VolumeController@listVolumes');
Route::get('/listImages', 'VolumeController@listImages');

Route::post('createVolume', 'VolumeController@createVolume');

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
Route::post('createInstance/{name}/{description}/{zone}/{image}/{size}/{flavor}/{networkId}/{networkName}/{keypair}/{volume}/{boot}', 'InstanceController@createInstance');
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

//Floating IPs
Route::get('floatingips', 'FloatingIPController@getFloatingIPs');