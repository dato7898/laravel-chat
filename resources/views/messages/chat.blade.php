@extends('templates.default')

@section('content')
    <div class="container">
		<div class="row">
		    <div class="col-md-8 col-md-offset-2">
		        <div class="panel panel-default">
		            <div class="panel-heading">Chats</div>

		            <div class="panel-body" id="chat-body">
		                <chat-messages 
		                	v-on:getmessages="fetchMessages"
		                	v-on:listenchat="listenChat"
		                	:messages="messages"
		                	:friend="{{ $friend }}"
		                	:user="{{ Auth::user() }}"
		                ></chat-messages>
		            </div>
		            <div class="panel-footer">
		                <chat-form
		                    v-on:messagesent="addMessage"
		                    :user="{{ Auth::user() }}"
		                    :friend="{{ $friend }}"
		                ></chat-form>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
@stop
