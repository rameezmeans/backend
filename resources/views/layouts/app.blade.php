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
    <meta name="url" data-user="{{env('CHAT_USER_ID')}}">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" rel="stylesheet" type="text/css" />
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet" type="text/css" />
    <link class="main-stylesheet" href="{{ url('pages/css/pages.css') }}" rel="stylesheet" type="text/css" />
    <link class="main-stylesheet" href="{{ url('pages/css/style.css') }}" rel="stylesheet" type="text/css" />
    
    @yield('pagespecificstyles')
  </head>
  <body class="fixed-header dashboard menu-pin">
  
  

   @include('layouts.nav')
   <!-- START PAGE-CONTAINER -->
   <div class="page-container ">
       @include('layouts.header')
       @yield('content')
   </div>
   
   <!-- BEGIN VENDOR JS -->
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
   <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
   <!-- END PAGE LEVEL JS -->
   <!-- END CORE TEMPLATE JS -->
   <!-- BEGIN PAGE LEVEL JS -->
   <script src="{{url('assets/js/dashboard.js') }}" type="text/javascript"></script>
   <script src="{{url('assets/js/scripts.js') }}" type="text/javascript"></script>
   <script type="text/javascript" src="{{ url('assets/plugins/dropzone/dropzone.min.js')}}"></script>
   <script src="{{url('assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js')}}"></script>
   <script src="{{url('assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
   <script src="{{url('assets/plugins/jquery-nestable/jquery.nestable.js')}}" type="text/javascript"></script>
   <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" type="text/javascript"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
   
   <script type="text/javascript">
   

      Dropzone.autoDiscover = false;

      $( document ).ready(function(event) {
        
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
          "aaSorting": []
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
</html>