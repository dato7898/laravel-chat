<?php

use Illuminate\Support\Facades\Route;

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

/**
 * Home
 */

Route::get('/', [
    'uses' => '\App\Http\Controllers\HomeController@index',
    'as' => 'home'
]);

/**
 * Search
 */

 Route::get('/search', [
    'uses' => '\App\Http\Controllers\SearchController@getResults',
    'as' => 'search.results'
 ]);

 /**
 * User profile
 */

 /**
  * The general rule of laravel routing is to place specific routes 
  * before wildcard routes that are related.
  * https://stackoverflow.com/questions/49851507/new-laravel-routes-not-working
  */
Route::get('/profile/edit', [
    'uses' => '\App\Http\Controllers\ProfileController@getEdit',
    'as' => 'profile.edit',
    'middleware' => ['auth']
 ]);

 Route::post('/profile/edit', [
     'uses' => '\App\Http\Controllers\ProfileController@postEdit',
     'middleware' => ['auth']
 ]);

Route::get('/profile/{username}', [
    'uses' => '\App\Http\Controllers\ProfileController@getProfile',
    'as' => 'profile.index'
 ]);

 /**
  * Friends
  */

Route::get('/friends', [
    'uses' => '\App\Http\Controllers\FriendController@getIndex',
    'as' => 'friend.index',
    'middleware' => ['auth']
 ]);

Route::get('/friends/add/{username}', [
	'uses' => '\App\Http\Controllers\FriendController@getAdd',
	'as' => 'friend.add',
	'middleware' => ['auth']
]);

Route::get('/friends/accept/{username}', [
	'uses' => '\App\Http\Controllers\FriendController@getAccept',
	'as' => 'friend.accept',
	'middleware' => ['auth']
]);

Route::post('/friends/delete/{username}', [
	'uses' => '\App\Http\Controllers\FriendController@postDelete',
	'as' => 'friend.delete',
	'middleware' => ['auth']
]);

/**
* Statuses
*/

Route::post('/status', [
    'uses' => '\App\Http\Controllers\StatusController@postStatus',
    'as' => 'status.post',
    'middleware' => ['auth']
 ]);

Route::post('/status/{statusId}/reply', [
	'uses' => '\App\Http\Controllers\StatusController@postReply',
	'as' => 'status.reply',
	'middleware' => ['auth']
]);

Route::get('/status/{statusId}/like', [
	'uses' => '\App\Http\Controllers\StatusController@getLike',
	'as' => 'status.like',
	'middleware' => ['auth']
]);

Auth::routes();

Route::get('/chat/{username}', 'ChatsController@index')->name('chat');
Route::get('messages/{user}', 'ChatsController@fetchMessages');
Route::post('messages/{user}', 'ChatsController@sendMessage');

Route::get('/notification', 'NotificationController@notification');

// Notifications
Route::post('notifications', 'NotificationController@store');
Route::get('notifications', 'NotificationController@index');
Route::patch('notifications/{id}/read', 'NotificationController@markAsRead');
Route::post('notifications/mark-all-read', 'NotificationController@markAllRead');
Route::post('notifications/{id}/dismiss', 'NotificationController@dismiss');

// Push Subscriptions
Route::post('subscriptions', 'PushSubscriptionController@update');
Route::post('subscriptions/delete', 'PushSubscriptionController@destroy');

// Manifest file (optional if VAPID is used)
Route::get('manifest.json', function () {
    return [
        'name' => config('app.name'),
        'gcm_sender_id' => config('webpush.gcm.sender_id')
    ];
});
