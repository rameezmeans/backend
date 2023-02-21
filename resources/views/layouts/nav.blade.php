<nav class="page-sidebar" data-pages="sidebar">
    <!-- BEGIN SIDEBAR MENU TOP TRAY CONTENT-->
    <div class="sidebar-overlay-slide from-top" id="appMenu">
      <div class="row">
        <div class="col-xs-6 no-padding">
          <a href="#" class="p-l-40"><img src="{{ url('assets/img/demo/social_app.svg')}}" alt="socail">
          </a>
        </div>
        <div class="col-xs-6 no-padding">
          <a href="#" class="p-l-10"><img src="{{ url('assets/img/demo/email_app.svg') }}" alt="socail">
          </a>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-6 m-t-20 no-padding">
          <a href="#" class="p-l-40"><img src="{{ url('assets/img/demo/calendar_app.svg') }}" alt="socail">
          </a>
        </div>
        <div class="col-xs-6 m-t-20 no-padding">
          <a href="#" class="p-l-10"><img src="{{ url('assets/img/demo/add_more.svg') }}" alt="socail">
          </a>
        </div>
      </div>
    </div>
    <!-- END SIDEBAR MENU TOP TRAY CONTENT-->
    <!-- BEGIN SIDEBAR MENU HEADER-->
    <div class="sidebar-header">
      <span class="bold font-montserrat">EcuTech</span>
      <div class="sidebar-header-controls">
        {{-- <button type="button" class="btn btn-xs sidebar-slide-toggle btn-link m-l-20" data-pages-toggle="#appMenu"><i class="fa fa-angle-down fs-16"></i>
        </button> --}}
        <button type="button" class="btn btn-link m-l-50 d-lg-inline-block d-xlg-inline-block d-md-inline-block d-sm-none d-none" data-toggle-pin="sidebar"><i class="fa fs-12"></i>
        </button>
      </div>
    </div>
    <!-- END SIDEBAR MENU HEADER-->
    <!-- START SIDEBAR MENU -->
    <div class="sidebar-menu">
      <!-- BEGIN SIDEBAR MENU ITEMS-->
      <ul class="menu-items">
        <li class="m-t-30 ">
          <a href="{{ route('home') }}" class="detailed">
            <span class="title">Dashboard</span>
          </a>
          <span class="bg-success icon-thumbnail"><i class="pg-home"></i></span>
        </li>
        
        <li class="m-t-30 ">
          <a href="{{ route('files') }}" class="detailed">
            <span class="title">Files</span>
            @if(Auth::user()->is_admin)
              @if(count_of_files() > 0)
                <span class="badge badged-warning text-black">{{count_of_files()}}</span>
              @endif
            @endif
          </a>
          <span class="bg-success icon-thumbnail"><i class="pg-save"></i></span>
        </li>

        @if(Auth::user()->is_admin || Auth::user()->is_head)
        <li class="m-t-30 ">
          <a href="javascript:;">
            <span class="title">Reports</span>
            <span class=" arrow"></span>
          </a>
          <span class="icon-thumbnail bg-success"><i class="pg-form"></i></span>
          <ul class="sub-menu">
            <li class="">
              <a href="{{ route('reports') }}">Engineer's Report</a>
              <span class="icon-thumbnail">ER</span>
            </li>
            <li class="">
              <a href="{{ route('feedback-reports') }}">Feedback Report</a>
              <span class="icon-thumbnail">FR</span>
            </li>
            <li class="">
              <a href="{{ route('credits-reports') }}">Credits Report</a>
              <span class="icon-thumbnail">FR</span>
            </li>
          </ul>
        </li>
        <li class="m-t-30 ">
          <a href="{{ route('credits') }}" class="detailed">
            <span class="title">Transactions</span>
          </a>
          <span class="bg-success icon-thumbnail"><i class="fa fa-file"></i></span>
        </li>
        <li class="m-t-30 ">
          <a href="javascript:;">
            <span class="title">Settings</span>
            <span class=" arrow"></span>
          </a>
          <span class="icon-thumbnail bg-success"><i class="pg-form"></i></span>
          <ul class="sub-menu">
            <li class="">
              <a href="{{ route('vehicles') }}">Vehicles</a>
              <span class="icon-thumbnail">Ve</span>
            </li>
            <li class="">
              <a href="{{ route('services') }}">Services</a>
              <span class="icon-thumbnail">Sr</span>
            </li>
            <li class="">
              <a href="{{ route('sorting-services') }}">Sorting Services</a>
              <span class="icon-thumbnail">Sr</span>
            </li>
            <li class="">
              <a href="{{ route('unit-price') }}">Unit Price</a>
              <span class="icon-thumbnail">UP</span>
            </li>
            <li class="">
              <a href="{{route('customers')}}">Customers</a>
              <span class="icon-thumbnail">Cu</span>
            </li>
            <li class="">
              <a href="{{ route('groups') }}">Customer Groups</a>
              <span class="icon-thumbnail">Gr</span>
            </li>
            <li class="">
              <a href="{{ route('engineers') }}">Engineers</a>
              <span class="icon-thumbnail">En</span>
            </li>
            <li class="">
              <a href="{{ route('tools') }}">Tools</a>
              <span class="icon-thumbnail">To</span>
            </li>
            <li class="">
              <a href="{{ route('feeds') }}">News Feed</a>
              <span class="icon-thumbnail">NF</span>
            </li>
            <li class="">
              <a href="{{ route('email-templates') }}">Email Templates</a>
              <span class="icon-thumbnail">ET</span>
            </li>
            <li class="">
              <a href="{{ route('message-templates') }}">Message Templates</a>
              <span class="icon-thumbnail">MT</span>
            </li>
            <li class="">
              <a href="{{ route('work-hours') }}">Work Hours</a>
              <span class="icon-thumbnail">WH</span>
            </li>
            <li class="">
              <a href="{{ route('reminder-manager') }}">Reminder Manager</a>
              <span class="icon-thumbnail">Rm</span>
            </li>
            <li class="">
              <a href="{{ route('frontends') }}">Frontends</a>
              <span class="icon-thumbnail">Fr</span>
            </li>
           
            <li class="">
              <a href="{{ route(config('chatify.routes.prefix'))}}">Chat</a>
              <span class="icon-thumbnail">Ch</span>
            </li>
           
          </ul>
        </li>
        <li class="m-t-30 ">
          <a href="{{ route('numbers') }}" class="detailed">
            <span class="title">Bosch ECU Numbers</span>
          </a>
          <span class="bg-success icon-thumbnail"><i class="pg-save"></i></span>
        </li>
        @endif
      </ul>
      <div class="clearfix"></div>
    </div>
    <!-- END SIDEBAR MENU -->
  </nav>
  <!-- END SIDEBAR -->
  <!-- END SIDEBPANEL-->