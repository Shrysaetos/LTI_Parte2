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

Route::post('login', 'LoginControllerAPI@login'); 
Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');

//Volumes
Route::post('createVolume', 'VolumeController@createVolume');
Route::get('volumes', 'VolumeController@getVolumes');

//Instances
Route::get('instances', 'InstanceController@getInstances');

//Flavors
Route::get('flavors', 'FlavorController@getFlavors');
