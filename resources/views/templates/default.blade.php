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
		@include('templates.partials.navigation')
		<div class="container">
		    @include('templates.partials.alerts')
		    @yield('content')
		</div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    
    <script src="{{ url('/js/main.js') }}"></script>
</body>
</html>
