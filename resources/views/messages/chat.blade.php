<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Chatty</title>
      <!-- GCM Manifest (optional if VAPID is used) -->
      @if (config('webpush.gcm.sender_id'))
      <link rel="manifest" href="/manifest.json">
      @endif
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <!-- Scripts -->
      <script src="{{ asset('js/app.js') }}" defer></script>
      <script>
         window.Laravel = {!! json_encode([
             'user' => Auth::user(),
             'csrfToken' => csrf_token(),
             'vapidPublicKey' => config('webpush.vapid.public_key'),
             'pusher' => [
                 'key' => config('broadcasting.connections.pusher.key'),
                 'cluster' => config('broadcasting.connections.pusher.options.cluster'),
             ],
         ]) !!};
      </script>
      <!-- Styles -->
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
      <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
      <link rel="stylesheet" href="{{ url('/css/main.css') }}">
      <style>
         .chat {
         list-style: none;
         margin: 0;
         padding: 0;
         }
         .chat li {
         margin-bottom: 10px;
         padding-bottom: 5px;
         border-bottom: 1px dotted #B3A9A9;
         }
         .chat li .chat-body p {
         margin: 0;
         color: #777777;
         }
         .panel-body {
         overflow-y: scroll;
         height: 350px;
         }
         ::-webkit-scrollbar-track {
         -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
         background-color: #F5F5F5;
         }
         ::-webkit-scrollbar {
         width: 12px;
         background-color: #F5F5F5;
         }
         ::-webkit-scrollbar-thumb {
         -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
         background-color: #555;
         }
      </style>
   </head>
   <body>
      <div id="app">
         <nav class="navbar navbar-default{{ $isMobile ? ' mobile-navbar' : '' }}" role="navigation">
            <div class="container">
               <div class="navbar-header">
                  <button 
                     type="button" 
                     class="navbar-toggle collapsed" 
                     data-toggle="collapse"
                     data-target="#navbar-collapse" 
                     aria-expanded="false"
                     >
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="{{ route('home') }}">Chatty</a>
               </div>
               <div class="collapse navbar-collapse" id="navbar-collapse">
                  @if (Auth::check())
                  <ul class="nav navbar-nav">
                     <li>
                        <a href="#">
                           <toggle-notifications></toggle-notifications>
                        </a>
                     </li>
                     <li><a href="{{ route('home') }}">Timeline</a></li>
                     <li><a href="{{ route('friend.index') }}">Friends</a></li>
                  </ul>
                  <form class="navbar-form navbar-left" role="search" action="{{ route('search.results') }}">
                     <div class="form-group">
                        <input type="text" name="query" class="form-control" placeholder="Find people">
                     </div>
                     <button type="submit" class="btn btn-default">Search</button>
                  </form>
                  @endif
                  <ul class="nav navbar-nav navbar-right">
                     @if (Auth::check())
                     <li><a href="{{ route('profile.index', ['username' => Auth::user()->name]) }}">
                        {{ Auth::user()->getNameOrUsername() }}
                        </a>
                     </li>
                     <li><a href="{{ route('profile.edit') }}">Update profile</a></li>
                     <li><a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                        </a>
                     </li>
                     <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                     </form>
                     @else
                     <li><a href="{{ route('register') }}">Sign up</a></li>
                     <li><a href="{{ route('login') }}">Sign in</a></li>
                     @endif
                  </ul>
               </div>
            </div>
         </nav>
         <div class="container{{ $isMobile ? ' full-width full-height' : '' }}">
            <div class="container{{ $isMobile ? ' full-width full-height' : '' }}">
               <div class="row{{ $isMobile ? ' full-height no-margin' : '' }}">
                  <div class="col-md-8 col-md-offset-2{{ $isMobile ? ' full-height no-padding' : '' }}">
                     <div class="panel panel-default{{ $isMobile ? ' full-height' : '' }}">
                        <div class="panel-heading">
                           <div class="green-circle" v-if="(usersonline.find(useronline => useronline.id === {{ $friend->id }}))"></div>
                           <div class="red-circle" v-else></div>
                           <span class="{{ $isMobile ? 'text-size-14' : 'text-size-20' }}" v-if="typing">{{ $friend->name }} is typing...</span>
                           <span class="{{ $isMobile ? 'text-size-14' : 'text-size-20' }}" v-else>Chat with {{ $friend->name }}</span>
                           <div class="pull-right">
                              <send-notification
                                 :friend="{{ $friend }}"
                                 ></send-notification>
                           </div>
                        </div>
                        <chat-messages 
                           v-on:getmessages="fetchMessages"
                           v-on:listenchat="listenChat"
                           :messages="messages"
                           :friend="{{ $friend }}"
                           :user="{{ Auth::user() }}"
                           :mobile="{{ $isMobile ? '1' : '0' }}"
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
         </div>
      </div>
      <script src="https://code.jquery.com/jquery-3.4.0.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
      <script src="{{ url('/js/main.js') }}"></script>
   </body>
</html>
