<?php

use Illuminate\Support\Facades\Broadcast;
use App\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat', function ($user) {
  return Auth::check();
});

Broadcast::channel('chatty', function ($user) {
  if (Auth::check()) {
  	return $user;
  }
});

Broadcast::channel('chat.{receiverid}', function ($user, $receiverid) {
  if (!Auth::check()) {
      return false;
  }
  
  $friend = User::where('id', $receiverid)->first();
  
  if (!$friend) {
      return false;
  }

  if (!Auth::user()->isFriendsWith($friend) && Auth::user()->id !== $friend->id) {
      return false;
  }
  
  return true;
});
