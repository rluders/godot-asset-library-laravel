<?php

declare(strict_types=1);

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

Route::group([
    'namespace' => 'Api\v1',
    'prefix' => 'v1',
], function () {
    Route::get('/asset', 'AssetController@index');
    Route::get('/asset/{asset}', 'AssetController@show');

    Route::get('/configure', 'ConfigureController@index');
});
