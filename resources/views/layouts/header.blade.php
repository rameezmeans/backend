<!-- START HEADER -->
<div class="header ">
    <!-- START MOBILE SIDEBAR TOGGLE -->
    <a href="#" class="btn-link toggle-sidebar d-lg-none pg pg-menu" data-toggle="sidebar">
    </a>
    <!-- END MOBILE SIDEBAR TOGGLE -->
    <div class="">
      <div class="brand inline   ">
        <img src="{{ url('assets/img/logo.png')}}" alt="logo" data-src="{{ url('assets/img/logo.png') }}" data-src-retina="{{ url('assets/img/logo_2x.png') }}" width="78" height="22">
      </div>
      <!-- START NOTIFICATION LIST -->
      <ul class="d-lg-inline-block d-none notification-list no-margin d-lg-inline-block b-grey b-l b-r no-style p-l-50 p-r-20">
        <li class="p-r-10 inline">

          @php
          $loggedInUser = Auth::user();
          $allEngineers = App\Models\User::whereIn('role_id', [2,3])->where('test', 0)->whereNull('subdealer_group_id')->orWhere('id', 3)->get();
        @endphp
        {{-- <div>
                    @foreach($allEngineers as $engineer)
                      <div style="width: 40%;" class="card social-card share col1 @if($loggedInUser->id == $engineer->id) flip-status @endif" data-social="item" style="" data-id="{{$engineer->id}}">
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
                  </div> --}}
          <div class="dropdown">
            <a href="javascript:;" id="notification-center" class="header-icon pg pg-world" data-toggle="dropdown">
              <span class="bubble"></span>
            </a>
            <!-- START Notification Dropdown -->
            <div class="dropdown-menu notification-toggle" role="menu" aria-labelledby="notification-center">
              <!-- START Notification -->
              <div class="notification-panel">
                <!-- START Notification Body-->
                <div class="notification-body scrollable">
                  <!-- START Notification Item-->
                  
                    <!-- START Notification Item-->
                    @foreach($allEngineers as $engineer)
                    <div class="notification-item clearfix @if($loggedInUser->id == $engineer->id) flip-status @endif" data-id="{{$engineer->id}}">
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
                    </div>
                    @endforeach
                    <!-- END Notification Item-->
                    <!-- START Notification Item Right Side-->
                    {{-- <div class="option" data-toggle="tooltip" data-placement="left" title="mark as read">
                      <a href="#" class="mark"></a>
                    </div> --}}
                    <!-- END Notification Item Right Side-->
                  {{-- </div> --}}
                  <!-- START Notification Body-->
                  <!-- START Notification Item-->
                  {{-- <div class="notification-item  clearfix">
                    <div class="heading">
                      <a href="#" class="text-danger pull-left">
                        <i class="fa fa-exclamation-triangle m-r-10"></i>
                        <span class="bold">98% Server Load</span>
                        <span class="fs-12 m-l-10">Take Action</span>
                      </a>
                      <span class="pull-right time">2 mins ago</span>
                    </div>
                    <!-- START Notification Item Right Side-->
                    <div class="option">
                      <a href="#" class="mark"></a>
                    </div>
                    <!-- END Notification Item Right Side-->
                  </div> --}}
                  <!-- END Notification Item-->
                  <!-- START Notification Item-->
                  {{-- <div class="notification-item  clearfix">
                    <div class="heading">
                      <a href="#" class="text-warning-dark pull-left">
                        <i class="fa fa-exclamation-triangle m-r-10"></i>
                        <span class="bold">Warning Notification</span>
                        <span class="fs-12 m-l-10">Buy Now</span>
                      </a>
                      <span class="pull-right time">yesterday</span>
                    </div>
                    <!-- START Notification Item Right Side-->
                    <div class="option">
                      <a href="#" class="mark"></a>
                    </div>
                    <!-- END Notification Item Right Side-->
                  </div> --}}
                  <!-- END Notification Item-->
                  <!-- START Notification Item-->
                  {{-- <div class="notification-item unread clearfix">
                    <div class="heading">
                      <div class="thumbnail-wrapper d24 circular b-white m-r-5 b-a b-white m-t-10 m-r-10">
                        <img width="30" height="30" data-src-retina="{{ url('assets/img/profiles/1x.jpg') }}" data-src="{{ url('assets/img/profiles/1.jpg') }}" alt="" src="{{ url('assets/img/profiles/1.jpg') }}">
                      </div>
                      <a href="#" class="text-complete pull-left">
                        <span class="bold">Revox Design Labs</span>
                        <span class="fs-12 m-l-10">Owners</span>
                      </a>
                      <span class="pull-right time">11:00pm</span>
                    </div>
                    <!-- START Notification Item Right Side-->
                    <div class="option" data-toggle="tooltip" data-placement="left" title="mark as read">
                      <a href="#" class="mark"></a>
                    </div>
                    <!-- END Notification Item Right Side-->
                  </div> --}}
                  <!-- END Notification Item-->
                </div>
                <!-- END Notification Body-->
                <!-- START Notification Footer-->
                {{-- <div class="notification-footer text-center">
                  <a href="#" class="">Read all notifications</a>
                  <a data-toggle="refresh" class="portlet-refresh text-black pull-right" href="#">
                    <i class="pg-refresh_new"></i>
                  </a>
                </div> --}}
                <!-- START Notification Footer-->
              </div>
              <!-- END Notification -->
            </div>
            <!-- END Notification Dropdown -->
          </div>
        </li>
        <li class="p-r-10 inline">
          {{-- <a href="#" class="header-icon pg pg-link"></a> --}}
        </li>
        <li class="p-r-10 inline">
          {{-- <a href="#" class="header-icon pg pg-thumbs"></a> --}}
        </li>
      </ul>
      <!-- END NOTIFICATIONS LIST -->
      {{-- <a href="#" class="search-link d-lg-inline-block d-none" data-toggle="search"><i class="pg-search"></i>Type anywhere to <span class="bold">search</span></a> --}}
    </div>
    <div class="d-flex align-items-center">

      <div class="pull-right p-r-10 fs-14 font-heading d-lg-block d-none">
        
      </div>
      <!-- START User Info-->
      <div class="pull-left p-r-10 fs-14 font-heading d-lg-block d-none">
        <span class="semi-bold">{{ Auth::user()->name }}</span>
      </div>
      <div class="dropdown pull-right d-lg-block d-none">
        <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="thumbnail-wrapper d32 circular inline">
          <img src="{{ url('assets/img/profiles/avatar.jpg') }}" alt="" data-src=" {{ url('assets/img/profiles/avatar.jpg') }}" data-src-retina=" {{ url('assets/img/profiles/avatar_small2x.jpg') }}" width="32" height="32">
          </span>
        </button>
        <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
          <a href="#" class="dropdown-item"><i class="pg-settings_small"></i> Settings</a>
          {{--
          <a href="#" class="dropdown-item"><i class="pg-outdent"></i> Feedback</a>
          <a href="#" class="dropdown-item"><i class="pg-signals"></i> Help</a> --}}

          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
          <a href="{{ route('logout') }}"
          onclick="event.preventDefault();
          document.getElementById('logout-form').submit();" class="clearfix bg-master-lighter dropdown-item">
              
            <span class="pull-left">Logout</span>
            <span class="pull-right"><i class="pg-power"></i></span>
          </a>
        </div>
      </div>
      <!-- END User Info-->
      <a href="#" class="header-icon pg pg-alt_menu btn-link m-l-10 sm-no-margin d-inline-block" data-toggle="quickview" data-toggle-element="#quickview"></a>
    </div>
  </div>
  <!-- END HEADER -->

<script type="text/javascript">
      $( document ).ready(function(event) {

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


      });
</script>

    