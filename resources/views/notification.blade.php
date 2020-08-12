@extends('templates.default')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="text-center">
            {{-- See resources/assets/js/components/NotificationsDemo.vue --}}
            <notifications-demo></notifications-demo>
        </div>
    </div>
</div>
@endsection
