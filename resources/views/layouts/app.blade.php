<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EcuTech - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
    <link rel="apple-touch-icon" href="pages/ico/60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="pages/ico/76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="pages/ico/120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="pages/ico/152.png">
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="user" data-user="{{env('CHAT_USER_ID')}}">
    <meta name="type" data-user="engineer">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="{{ url('assets/plugins/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    {{-- <link href="{{ url('assets/plugins/fontawesome/all.min.css') }}" rel="stylesheet" type="text/css" /> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet">
    <link href="{{ url('assets/plugins/pace/pace-theme-flash.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/plugins/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/plugins/jquery-scrollbar/jquery.scrollbar.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ url('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ url('assets/plugins/switchery/css/switchery.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ url('assets/plugins/nvd3/nv.d3.min.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ url('assets/plugins/mapplic/css/mapplic.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/plugins/rickshaw/rickshaw.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/plugins/jquery-nestable/jquery.nestable.css')}}" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ url('assets/plugins/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ url('assets/plugins/jquery-metrojs/MetroJs.css') }}" rel="stylesheet" type="text/css" media="screen" />
    <link href="{{ url('assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet" type="text/css" media="screen">
    <link href="{{url('assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css')}}" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ url('pages/css/pages-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('assets/plugins/dropzone/css/dropzone.css')}} " rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/plugins/toastr/toastr.css') }}" rel="stylesheet" type="text/css" />
    <link class="main-stylesheet" href="{{ url('pages/css/pages.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ url('assets/plugins/toastr/toastify.min.css') }}">
    {{-- <link class="main-stylesheet" href="{{ url('pages/css/style.css') }}" rel="stylesheet" type="text/css" /> --}}
    {{-- @vite('resources/js/push.js', 'node_modules/push.js/bin/push.min.js') --}}
    <script type="text/javascript" src="{{ url('assets/plugins/pushjs/push.min.js') }}"></script>
    {{-- @livewireStyles --}}
    <style>
       [x-cloak] {
          display: none;
      }
      h3 {
        font-size: 27px !important
      }
    </style>
    
    {{-- <style>[x-cloak] { display: none !important; }</style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScripts --}}
   
    
    @yield('pagespecificstyles')
  </head>
  <body class="fixed-header dashboard menu-pin">
  
   @include('layouts.nav')
   <!-- START PAGE-CONTAINER -->
   <div class="page-container ">
       @include('layouts.header')

      @php
        $loggedInUser = Auth::user();
        $allEngineers = App\Models\User::whereIn('role_id', [2,3])->where('test', 0)->whereNull('subdealer_group_id')->orWhere('id', 3)->get();
      @endphp

{{-- <div class="page-content-wrapper" style="min-height: 0% !important; margin-top: 50px;">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class="container-fluid bg-white">
       <div>
                    @foreach($allEngineers as $engineer)
                      <div class="card social-card share col1 @if($loggedInUser->id == $engineer->id) flip-status @endif" data-social="item" style="" data-id="{{$engineer->id}}">
                        <div class="circle" data-toggle="tooltip" title="" data-container="body" data-original-title="Label">
                        </div>
                        <div class="card-header clearfix">
                          <div class="user-pic">
                            <img alt="Profile Image" width="33" height="33" data-src-retina="{{url('assets/img/profiles/4x.jpg')}}" data-src="{{url('assets/img/profiles/4.jpg')}}" src="{{url('assets/img/profiles/4x.jpg')}}">
                          </div>
                          <h5>{{$engineer->name}}</h5>
                            @if($engineer->online)
                              <h5 class="text-success pull-left fs-12">Online <i class="fa fa-circle text-success fs-11"></i></h5>
                            @else
                              <h5 class="text-danger pull-left fs-12">Offline <i class="fa fa-circle text-danger fs-11"></i></h5>
                            @endif
                        </div>
                      </div>
                    @endforeach
                  </div>
        </div>
    </div>
</div> --}}

       @yield('content')
   </div>
   @include('chat.chatview')
   <!-- BEGIN VENDOR JS -->
   <script type="text/javascript" src="{{ url('assets/plugins/toastify/toastify-js.js') }}"></script>
   <script src="{{ url('assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/jquery/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/modernizr.custom.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/popper/umd/popper.min.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/jquery/jquery-easy.js') }}" type="text/javascript"></script>
   <script src="{{ url('assets/plugins/jquery-unveil/jquery.unveil.min.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/jquery-ios-list/jquery.ioslist.min.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/jquery-actual/jquery.actual.min.js') }}"></script>
   <script src="{{url('assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
   <script type="text/javascript" src="{{url('assets/plugins/select2/js/select2.full.min.js') }}"></script>
   <script type="text/javascript" src="{{url('assets/plugins/classie/classie.js') }}"></script>
   <script src="{{url('assets/plugins/switchery/js/switchery.min.js') }}" type="text/javascript"></script>
   <script src="{{ url('assets/plugins/nvd3/lib/d3.v3.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/nvd3/nv.d3.min.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/nvd3/src/utils.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/nvd3/src/tooltip.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/nvd3/src/interactiveLayer.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/nvd3/src/models/axis.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/nvd3/src/models/line.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/nvd3/src/models/lineWithFocusChart.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/mapplic/js/hammer.min.js') }}"></script>
   <script src="{{url('assets/plugins/mapplic/js/jquery.mousewheel.js') }}"></script>
   <script src="{{url('assets/plugins/mapplic/js/mapplic.js') }}"></script>
   <script src="{{url('assets/plugins/rickshaw/rickshaw.min.js') }}"></script>
   <script src="{{url('assets/plugins/moment/moment.min.js')}}"></script>
   <script src="{{ url('assets/plugins/sweetalert/sweetalert.min.js') }}"></script>
   <script src="{{ url('assets/plugins/toastr/toastr.min.js') }}"></script>
   <script src="{{url('assets/plugins/jquery-metrojs/MetroJs.min.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/jquery-sparkline/jquery.sparkline.min.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/skycons/skycons.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js') }}" type="text/javascript"></script>
   
   <!-- END VENDOR JS -->
   <!-- BEGIN CORE TEMPLATE JS -->
   <!-- BEGIN CORE TEMPLATE JS -->
   <script src="{{url('pages/js/pages.js') }}"></script>
   <!-- END CORE TEMPLATE JS -->
   <!-- BEGIN PAGE LEVEL JS -->
   <script src="{{url('assets/js/scripts.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/js/jquery.ui.sound.js') }}" type="text/javascript"></script>

   <!-- END PAGE LEVEL JS -->
   <!-- END CORE TEMPLATE JS -->
   <!-- BEGIN PAGE LEVEL JS -->

   <!--CHATIFY AREA-->

   <style>

    .activeStatus {
        width: 10px;
        height: 10px;
        background: rgb(76, 175, 80);
        border-radius: 20px;
        position: absolute;
        bottom: 12%;
        right: 6%;
        transition: border 0.1s ease 0s;
    }

    .count {
      background: #2180f3;
      float: right;
      color: rgb(255, 255, 255);
      padding: 0px 4px;
      border-radius: 20px !important;
      font-size: 8px !important;
      width: 20px;
      height: 20px;;
      font-size: 12px;
      text-align: center;
      
      
    }

    .chat-user-list-ecu-tech {
      background-color: #e2deef;
    }

    .chat-user-list-tuningx {
      background-color: #fef6dd;
    }

   </style>

  <script src="{{ url('assets/plugins/nprogress/nprogress.js') }}""></script>
  <script src="{{ asset('js/chatify/autosize.js') }}"></script>

  {{-- styles --}}
  <link rel='stylesheet' href="{{ url('assets/plugins/nprogress/nprogress.css') }}" />

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

     const channelNameNew = "private-chatify-new";
        
        
     
   </script>
<meta name="id" content="{{ env('CHAT_USER_ID') }}">
<meta name="url" content="{{ url('').'/'.config('chatify.routes.prefix') }}" data-user="{{ env('CHAT_USER_ID') }}">
<script src="{{url('js/chatify/code2.js') }}" type="text/javascript"></script>

   <script src="{{url('assets/js/dashboard.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/js/scripts.js') }}" type="text/javascript"></script>
   <script type="text/javascript" src="{{ url('assets/plugins/dropzone/dropzone.min.js')}}"></script>
   <script src="{{url('assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js')}}"></script>
   <script src="{{url('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
   <script src="{{url('assets/plugins/jquery-nestable/jquery.nestable.js')}}" type="text/javascript"></script>
   <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" type="text/javascript"></script>
   <script src="{{url('js/jquery.table2excel.min.js') }}" type="text/javascript"></script>
   <script src="{{ url('assets/plugins/charts/charts.js') }}"></script>
   
   <script type="text/javascript">
      
      Dropzone.autoDiscover = false;

      $( document ).ready(function(event) {

        // const channelNameNew = "private-chatify-new";
        // var channelNew = pusher.subscribe(`${channelNameNew}`);


        // // const channelNameNew = "private-chatify-new";
        // // var channelNew = pusher.subscribe(`${channelName}.${auth_id}`);

        // console.log(channelNew);
        // console.log(clientListenChannel);

        // channelNew.bind("test", function (data) {
        //   console.log(data);
        //   let obj = Push.create("ECU Tech customer File upload!", {
        //       body: "Testing completed.",
        //       timeout: 5000,
        //   });

        // channelNew.bind("test", function (data) {
        //   console.log(data);
        //   let obj = Push.create("ECU Tech customer File upload!", {
        //       body: "Testing completed.",
        //       timeout: 5000,
        //   });

        //   console.log(obj);
        
        // });

        // clientListenChannel.bind("test", function (data) {
        //   console.log(data);
        //   let obj = Push.create("ECU Tech customer File upload!", {
        //       body: "Testing completed.",
        //       timeout: 5000,
        //   });

        //   console.log(obj);
        
        // });

        $(document).on('click','.flip-status',function(e) {

          console.log('here we are');

          let id = $(this).data('id');

          console.log(id);

          $.ajax({
                url: "/flip_engineer_status",
                type: "POST",
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'id': id,
                },
                success: function(items) {
                    console.log(items);
                    window.location.href = "/files";
                }
            });

        });
        
        $('.datepicker').datepicker({
          format: "dd/mm/yyyy",
        });

        $(document).on('click','.redirect-click',function(e) {
            if(!$(e.target).hasClass('switchery')){
                
                if( e.target.nodeName !== 'SMALL' && e.target.nodeName !== 'LABEL') {
                  window.location.href = $(this).data('redirect');
                }

                if(e.target.nodeName == 'LABEL'){
                  console.log( $(e.target).prev().is(":checked"));
                  if($(e.target).prev().is(":checked")){
                    $(e.target).prev().attr('checked', false);
                  }
                  else{
                    $(e.target).prev().attr('checked', true);
                  }
                }
            }
            return false;
        });

        var pageLength = 0;

        

        let table = $('.dataTable').DataTable({
          "aaSorting": [],

        });
        

        $('.dataTables_filter input').off().on('keyup', function() {
          var str = $(this).val();
          if (str.length === 0) {
            table.page.len(pageLength);
            pageLength = 0;
          }
          else if (pageLength === 0) {
              pageLength = table.page.len();
              table.page.len(10000);
          }
          
          table.search(str).draw();
          
        });

        $('.datepicker').datepicker();

      });
   </script>

   <!-- END PAGE LEVEL JS -->
   @yield('pagespecificscripts')
 </body>
 {{-- @livewireScripts --}}
 @stack('scripts')
</html>