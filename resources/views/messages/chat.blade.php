@extends('templates.default')

@section('content')
    <div class="container">
		<div class="row">
		    <div class="col-md-8 col-md-offset-2">
		        <div class="panel panel-default">
		            <div class="panel-heading">
		            	<div class="green-circle" v-if="(usersonline.find(useronline => useronline.id === {{ $friend->id }}))"></div>      
		            	<div class="red-circle" v-else></div>
		            	<span class="text-size-20" v-if="typing">{{ $friend->name }} is typing...</span>
		            	<span class="text-size-20" v-else>Chat with {{ $friend->name }}</span>
		            	<div class="pull-right"><send-notification
		            		:friend="{{ $friend }}"
		            	></send-notification></div>
		            </div>

	                <chat-messages 
	                	v-on:getmessages="fetchMessages"
	                	v-on:listenchat="listenChat"
	                	:messages="messages"
	                	:friend="{{ $friend }}"
	                	:user="{{ Auth::user() }}"
	                ></chat-messages>
	                
		            <div class="panel-footer">
		                <chat-form
		                    v-on:messagesent="addMessage"
		                    v-on:getmessages="fetchMessages"
		                    v-on:typing="onTyping"
		                    :user="{{ Auth::user() }}"
		                    :friend="{{ $friend }}"
		                ></chat-form>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
@stop
