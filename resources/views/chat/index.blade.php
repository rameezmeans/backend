@extends('layouts.app')

@section('pagespecificstyles')

<script src="{{ asset('js/chatify/font.awesome.min.js') }}"></script>
<script src="{{ asset('js/chatify/autosize.js') }}"></script>
{{-- <script src="{{ asset('js/app.js') }}"></script> --}}
<script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script>

{{-- styles --}}
<link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css'/>
<link href="{{ asset('css/chatify/style.css') }}" rel="stylesheet" />
<link href="{{ asset('css/chatify/'.$dark_mode.'.mode.css') }}" rel="stylesheet" />
{{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet" /> --}}
<style>
    :root {
        --messengerColor: {{ $messengerColor }},
    }
/* NProgress background */
#nprogress .bar{
	background: {{ $messengerColor }} !important;
}
#nprogress .peg {
    box-shadow: 0 0 10px {{ $messengerColor }}, 0 0 5px {{ $messengerColor }} !important;
}
#nprogress .spinner-icon {
  border-top-color: {{ $messengerColor }} !important;
  border-left-color: {{ $messengerColor }} !important;
}

.m-header svg{
    color: {{ $messengerColor }};
}

.m-list-active,
.m-list-active:hover,
.m-list-active:focus{
	background: {{ $messengerColor }};
}

.m-list-active b{
	background: #fff !important;
	color: {{ $messengerColor }} !important;
}

.messenger-list-item td b{
    background: {{ $messengerColor }};
}

.messenger-infoView nav a{
    color: {{ $messengerColor }};
}

.messenger-infoView-btns a.default{
	color: {{ $messengerColor }};
}

.mc-sender p{
  background: {{ $messengerColor }};
}

.messenger-sendCard button svg{
    color: {{ $messengerColor }};
}

.messenger-listView-tabs a,
.messenger-listView-tabs a:hover,
.messenger-listView-tabs a:focus{
    color: {{ $messengerColor }};
}

.active-tab{
	border-bottom: 2px solid {{ $messengerColor }};
}

.lastMessageIndicator{
    color: {{ $messengerColor }} !important;
}

.messenger-favorites div.avatar{
    box-shadow: 0px 0px 0px 2px {{ $messengerColor }};
}

.dark-mode-switch{
    color: {{ $messengerColor }};
}
.m-list-active .activeStatus{
    border-color: {{ $messengerColor }} !important;
}

.messenger [type='text']:focus {
    outline: 1px solid {{ $messengerColor }};
    border-color: {{ $messengerColor }} !important;
    border-color: {{ $messengerColor }};
    box-shadow: 0 0 2px {{ $messengerColor }};
}
</style>
<style>

</style>
@endsection

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title"><h3>Customer Chat</h3>
                </div>
                <div class="pull-right">
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                @include('Chatify::layouts.headLinks')
<div class="messenger">
    {{-- ----------------------Users/Groups lists side---------------------- --}}
    <div class="messenger-listView">
        {{-- Header and search bar --}}
        <div class="m-header">
            <nav>
                <a href="#"><i class="fas fa-inbox"></i> <span class="messenger-headTitle">MESSAGES</span> </a>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    {{-- <a href="#"><i class="fas fa-cog settings-btn"></i></a> --}}
                    <a href="#" class="listView-x"><i class="fas fa-times"></i></a>
                </nav>
            </nav>
            {{-- Search input --}}
            {{-- <input type="text" class="messenger-search" placeholder="Search" /> --}}
            {{-- Tabs --}}
            <div class="messenger-listView-tabs">
                <a href="#" @if($type == 'user') class="active-tab" @endif data-view="users">
                    <span class="far fa-user"></span> Customers</a>
                {{-- <a href="#" @if($type == 'group') class="active-tab" @endif data-view="groups">
                    <span class="fas fa-users"></span> Groups</a> --}}
            </div>
        </div>
        {{-- tabs and lists --}}
        <div class="m-body contacts-container">
           {{-- Lists [Users/Group] --}}
           {{-- ---------------- [ User Tab ] ---------------- --}}
           <div class="@if($type == 'user') show @endif messenger-tab users-tab app-scroll" data-view="users">

               {{-- Favorites --}}
               {{-- <div class="favorites-section">
                <p class="messenger-title">Favorites</p>
                <div class="messenger-favorites app-scroll-thin"></div>
               </div> --}}

               {{-- Saved Messages --}}
               {{-- {!! view('Chatify::layouts.listItem', ['get' => 'saved']) !!} --}}

               {{-- Contact --}}
               <div class="listOfContacts" style="width: 100%;height: calc(100% - 200px);position: relative;"></div>

           </div>

           {{-- ---------------- [ Group Tab ] ---------------- --}}
           {{-- <div class="@if($type == 'group') show @endif messenger-tab groups-tab app-scroll" data-view="groups"> --}}
                {{-- items --}}
                {{-- <p style="text-align: center;color:grey;margin-top:30px">
                    <a target="_blank" style="color:{{$messengerColor}};" href="https://chatify.munafio.com/notes#groups-feature">Click here</a> for more info!
                </p>
             </div> --}}

             {{-- ---------------- [ Search Tab ] ---------------- --}}
           {{-- <div class="messenger-tab search-tab app-scroll" data-view="search"> --}}
                {{-- items --}}
                {{-- <p class="messenger-title">Search</p>
                <div class="search-records">
                    <p class="message-hint center-el"><span>Type to search..</span></p>
                </div>
             </div> --}}
        </div>
    </div>

    {{-- ----------------------Messaging side---------------------- --}}
    <div class="messenger-messagingView">
        {{-- header title [conversation name] amd buttons --}}
        <div class="m-header m-header-messaging">
            <nav class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                {{-- header back button, avatar and user name --}}
                <div class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                    <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a>
                    <div class="avatar av-s header-avatar" style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;">
                    </div>
                    <a href="#" class="user-name">Chat</a>
                </div>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    <a href="#" class="add-to-favorite"><i class="fas fa-star"></i></a>
                    {{-- <a href="/"><i class="fas fa-home"></i></a> --}}
                    {{-- <a href="#" class="show-infoSide"><i class="fas fa-info-circle"></i></a> --}}
                </nav>
            </nav>
        </div>

        {{-- Messaging area --}}
        <div class="m-body messages-container app-scroll">
             {{-- Internet connection --}}
            <div class="internet-connection">
                <span class="ic-connected">Connected</span>
                <span class="ic-connecting">Connecting...</span>
                <span class="ic-noInternet">No internet access</span>
            </div>
            <div class="messages">
                <p class="message-hint center-el"><span>Please select a chat to start messaging</span></p>
            </div>
            {{-- Typing indicator --}}
            <div class="typing-indicator">
                <div class="message-card typing">
                    <p>
                        <span class="typing-dots">
                            <span class="dot dot-1"></span>
                            <span class="dot dot-2"></span>
                            <span class="dot dot-3"></span>
                        </span>
                    </p>
                </div>
            </div>

        </div>
        {{-- Send Message Form --}}
        @include('Chatify::layouts.sendForm')
    </div>
    {{-- ---------------------- Info side ---------------------- --}}
    {{-- <div class="messenger-infoView app-scroll"> --}}
        {{-- nav actions --}}
        {{-- <nav>
            <a href="#"><i class="fas fa-times"></i></a>
        </nav> --}}
        {{-- {!! view('Chatify::layouts.info')->render() !!} --}}
    {{-- </div> --}}
</div>

@include('Chatify::layouts.modals')
@include('Chatify::layouts.footerLinks')
            </div>
          </div>
        </div>
    </div>
</div>
@endsection


@section('pagespecificscripts')

<script src="https://js.pusher.com/7.0.3/pusher.min.js"></script>
<script >
  // Enable pusher logging - don't include this in production
  Pusher.logToConsole = true;

  var pusher = new Pusher("{{ config('chatify.pusher.key') }}", {
    encrypted: true,
    cluster: "{{ config('chatify.pusher.options.cluster') }}",
    authEndpoint: '{{route("pusher.auth")}}',
    auth: {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }
  });

    // Bellow are all the methods/variables that using php to assign globally.
    const allowedImages = {!! json_encode(config('chatify.attachments.allowed_images')) !!} || [];
    const allowedFiles = {!! json_encode(config('chatify.attachments.allowed_files')) !!} || [];
    const getAllowedExtensions = [...allowedImages, ...allowedFiles];
    const getMaxUploadSize = {{ Chatify::getMaxUploadSize() }};
</script>
<script src="{{ asset('js/chatify/code.js') }}"></script>

<script type="text/javascript">

    $( document ).ready(function(event) {
        
    });

</script>

@endsection