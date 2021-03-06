<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'AssetController@index')->name('asset.index');
// Redirect for compatibility with the old asset library homepage URL
Route::permanentRedirect('/asset', url('/'));
Route::get('/asset/submit', 'AssetController@create')->name('asset.create')->middleware('can:submit-asset');
Route::post('/asset', 'AssetController@store')->name('asset.store')->middleware('can:submit-asset');

Route::get('/asset/{asset}', 'AssetController@show')->name('asset.show')->middleware('can:view-asset,asset');
Route::get('/asset/{asset}/edit', 'AssetController@edit')->name('asset.edit')->middleware('can:edit-asset,asset');
Route::put('/asset/{asset}', 'AssetController@update')->name('asset.update')->middleware('can:edit-asset,asset');
Route::put('/asset/{asset}/archive', 'AssetController@archive')->name('asset.archive')->middleware('can:edit-asset,asset');
Route::put('/asset/{asset}/unarchive', 'AssetController@unarchive')->name('asset.unarchive')->middleware('can:edit-asset,asset');
Route::put('/asset/{asset}/publish', 'AssetController@publish')->name('asset.publish')->middleware('can:admin');
Route::put('/asset/{asset}/unpublish', 'AssetController@unpublish')->name('asset.unpublish')->middleware('can:admin');

Route::post('/asset/{asset}/reviews', 'AssetController@storeReview')->name('asset.reviews.store')->middleware('can:submit-review,asset');
Route::post('/asset/reviews/{asset_review}', 'AssetController@storeReviewReply')->name('asset.reviews.replies.store')->middleware('can:submit-review-reply,asset_review');
Route::put('/asset/reviews/{asset_review}', 'AssetController@updateReview')->name('asset.reviews.update')->middleware('can:edit-review,asset_review');
Route::delete('/asset/reviews/{asset_review}', 'AssetController@destroyReview')->name('asset.reviews.destroy')->middleware('can:edit-review,asset_review');

Route::get('/user/{user}', 'UserController@show')->name('user.show');
Route::get('/user/{user}/reviews', 'UserController@indexReviews')->name('user.reviews.index');

Route::get('/profile/edit', 'ProfileController@edit')->name('profile.edit')->middleware('auth');
Route::patch('/profile', 'ProfileController@update')->name('profile.update')->middleware('auth');

Route::get('/admin', 'AdminController@index')->name('admin.index')->middleware('can:admin');
Route::put('/admin/users/{user}/block', 'AdminController@block')->name('admin.block')->middleware('can:admin');
Route::put('/admin/users/{user}/unblock', 'AdminController@unblock')->name('admin.unblock')->middleware('can:admin');

// Register authentication-related routes (including email verification routes)
Auth::routes(['verify' => true]);

// OAuth2 authentication routes
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->name('login.oauth2');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('login.oauth2.callback');
