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
      <ul class="d-lg-inline-block d-none notification-list no-margin d-lg-inline-block b-grey b-l b-r no-style p-l-30 p-r-20">
        <li class="p-r-10 inline">
          {{-- <div class="dropdown">
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
                  <div class="notification-item unread clearfix">
                    <!-- START Notification Item-->
                    <div class="heading open">
                      <a href="#" class="text-complete pull-left">
                        <i class="pg-map fs-16 m-r-10"></i>
                        <span class="bold">Carrot Design</span>
                        <span class="fs-12 m-l-10">David Nester</span>
                      </a>
                      <div class="pull-right">
                        <div class="thumbnail-wrapper d16 circular inline m-t-15 m-r-10 toggle-more-details">
                          <div><i class="fa fa-angle-left"></i>
                          </div>
                        </div>
                        <span class=" time">few sec ago</span>
                      </div>
                      <div class="more-details">
                        <div class="more-details-inner">
                          <h5 class="semi-bold fs-16">“Apple’s Motivation - Innovation <br>
                                                        distinguishes between <br>
                                                        A leader and a follower.”</h5>
                          <p class="small hint-text">
                            Commented on john Smiths wall.
                            <br> via pages framework.
                          </p>
                        </div>
                      </div>
                    </div>
                    <!-- END Notification Item-->
                    <!-- START Notification Item Right Side-->
                    <div class="option" data-toggle="tooltip" data-placement="left" title="mark as read">
                      <a href="#" class="mark"></a>
                    </div>
                    <!-- END Notification Item Right Side-->
                  </div>
                  <!-- START Notification Body-->
                  <!-- START Notification Item-->
                  <div class="notification-item  clearfix">
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
                  </div>
                  <!-- END Notification Item-->
                  <!-- START Notification Item-->
                  <div class="notification-item  clearfix">
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
                  </div>
                  <!-- END Notification Item-->
                  <!-- START Notification Item-->
                  <div class="notification-item unread clearfix">
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
                  </div>
                  <!-- END Notification Item-->
                </div>
                <!-- END Notification Body-->
                <!-- START Notification Footer-->
                <div class="notification-footer text-center">
                  <a href="#" class="">Read all notifications</a>
                  <a data-toggle="refresh" class="portlet-refresh text-black pull-right" href="#">
                    <i class="pg-refresh_new"></i>
                  </a>
                </div>
                <!-- START Notification Footer-->
              </div>
              <!-- END Notification -->
            </div>
            <!-- END Notification Dropdown -->
          </div> --}}
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