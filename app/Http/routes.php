<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('sms/{from}/{to}/{message}', 'SMSController@sms');

Route::post('sun', 'SMSController@sun');

Route::post('broadcast', 'SMSController@broadcast');

Route::group(['prefix' => 'txtcmdr'], function()
{
    Route::get('test', function ()    {
        return "Test";
    });

    Route::post('group/{group}/{mobile}/{handle?}', 'TextCommanderController@joinGroup');
});