<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class ChatsController extends Controller
{
	public function __construct()
	{
	  $this->middleware('auth');
	}

	/**
	 * Show chats
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($username)
	{
      $friend = User::where('name', $username)->first();
      
      if (!$friend) {
          abort(404);
      }
	
	  if (!Auth::user()->isFriendsWith($friend) && Auth::user()->id !== $friend->id) {
          abort(403);
      }
      
	  return view('messages.chat')
	  	->with('friend', $friend);
	}

	/**
	 * Fetch all messages
	 *
	 * @return Message
	 */
	public function fetchMessages(User $user)
	{
	  $privateCommunication = Message::with('user')
	  	->where(['user_id' => auth()->id(), 'receiver_id' => $user->id])
	  	->orWhere(function($query) use($user) {
	  		$query->where(['user_id' => $user->id, 'receiver_id' => auth()->id()]);
	  	})
	  	->get();
	  	
	  	return $privateCommunication;
	}

	/**
	 * Persist message to database
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function sendMessage(Request $request, User $user)
	{
	  $input = $request->all();
	  $input['receiver_id'] = $user->id;
	
	  $user = Auth::user();

	  $message = $user->messages()->create($input);
		
	  broadcast(new MessageSent($user, $message->load('user')))->toOthers();

	  return response(['status' => 'Message Sent!', 'message' => $message, 'input' => $input]);
	}
}
