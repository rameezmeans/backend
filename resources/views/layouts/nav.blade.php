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
        <button type="button" style="margin-left: 100px;" class="btn btn-link d-lg-inline-block d-xlg-inline-block d-md-inline-block d-sm-none d-none" data-toggle-pin="sidebar"><i class="fa fs-12"></i>
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
            <span class="title" style="width: 80% !important;">Files</span>
              <span id="file-count" class="badge badged-warning text-black @if(count_of_files() == 0) hide @endif">{{count_of_files()}}</span>
          </a>
          <span class="bg-success icon-thumbnail"><i class="pg-save"></i></span>
        </li>

        <li class="m-t-30 ">
          <a href="{{ route('my-files') }}" class="detailed">
            <span class="title" style="width: 80% !important;">My Files</span>
              
          </a>
          <span class="bg-success icon-thumbnail"><i class="pg-save"></i></span>
        </li>
        
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
              <span class="icon-thumbnail">CR</span>
            </li>
            <li class="">
              <a href="{{ route('softwares-report') }}">Softwares Report</a>
              <span class="icon-thumbnail">SR</span>
            </li>
            <li class="">
              <a href="{{ route('countries-report') }}">Countries Report</a>
              <span class="icon-thumbnail">CR</span>
            </li>

            <li class="">
              <a href="{{ route('services-report') }}">Services Report</a>
              <span class="icon-thumbnail">CR</span>
            </li>

            <li class="">
              <a href="{{ route('database-import') }}">Database Import</a>
              <span class="icon-thumbnail">DI</span>
            </li>

            <li class="m-t-30 ">
              <a href="{{ route('download-terms') }}" class="detailed">
                <span class="title">Download Terms Docs</span>
              </a>
              <span class="icon-thumbnail bg-success"><i class="fa file"></i></span>
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
          <a href="{{ route('original-files') }}" class="detailed">
            <span class="title">Original Files</span>
          </a>
          <span class="bg-success icon-thumbnail"><i class="fa fa-file"></i></span>
        </li>

        <li class="m-t-30 ">
          <a href="javascript:;">
            <span class="title">Payment Logs</span>
            <span class=" arrow"></span>
          </a>
          <span class="icon-thumbnail bg-success"><i class="pg-form"></i></span>
          <ul class="sub-menu">
            <li class="">
              <a href="{{ route('payment-and-customers') }}">Customers</a>
              <span class="icon-thumbnail">Cu</span>
            </li>
            
            <li class="">
              <a href="{{ route('all-payment-logs') }}">Payment Logs Report</a>
              <span class="icon-thumbnail">Gr</span>
            </li>

            <li class="">
              <a href="{{ route('all-payments') }}">All Payment Report</a>
              <span class="icon-thumbnail">Gr</span>
            </li>
            
          </ul>
        </li>

        <li class="m-t-30 ">
          <a href="javascript:;">
            <span class="title">Customers</span>
            <span class=" arrow"></span>
          </a>
          <span class="icon-thumbnail bg-success"><i class="pg-form"></i></span>
          <ul class="sub-menu">
            <li class="">
              <a href="{{ route('customers') }}">Customers</a>
              <span class="icon-thumbnail">Cu</span>
            </li>

            <li class="">
              <a href="{{ route('groups') }}">VAT Groups</a>
              <span class="icon-thumbnail">Vg</span>
            </li>
            
          </ul>
        </li>

       

        <li class="m-t-30 ">
          <a href="javascript:;">
            <span class="title">Vehicles & Services</span>
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
              <span class="icon-thumbnail">Se</span>
            </li>
            <li class="">
              <a href="{{ route('sorting-services') }}">Sorting Services</a>
              <span class="icon-thumbnail">Sr</span>
            </li>
            <li class="m-t-30 ">
                <a href="{{ route('combinations') }}">Combinations</a>
                <span class="icon-thumbnail">Co</span>
            </li>
            <li class="m-t-30 ">
              <a href="{{ route('processing-softwares') }}">Processing Softwares</a>
              <span class="icon-thumbnail">Ps</span>
            </li>
            <li class="m-t-30 ">
              <a href="{{ route('options-comments') }}">Options Comments</a>
              <span class="icon-thumbnail">Oc</span>
            </li>
          </ul>
        </li>

        <li class="m-t-30 ">
          <a href="{{ route('vehicles') }}" class="detailed">
            <span class="title">Vehicles</span>
          </a>
          <span class="bg-success icon-thumbnail"><i class="fa fa-file"></i></span>
        </li>

        <li class="m-t-30 ">
          <a href="{{ route('services') }}" class="detailed">
            <span class="title">Servcies</span>
          </a>
          <span class="bg-success icon-thumbnail"><i class="fa fa-file"></i></span>
        </li>

        <li class="m-t-30 ">
          <a href="{{ route('payment-accounts') }}" class="detailed">
            <span class="title">Payment Methods</span>
          </a>
          <span class="bg-success icon-thumbnail"><i class="fa fa-file"></i></span>
        </li>

        <li class="m-t-30 ">
            <a href="javascript:;">
                <span class="title">Subdealers</span>
                <span class=" arrow"></span>
            </a>
            <span class="icon-thumbnail bg-success"><i class="pg-form"></i></span>
            <ul class="sub-menu">
                <li class="m-t-30 ">
                    <a href="{{ route('subdealers-entity') }}">Subdealers</a>
                    <span class="icon-thumbnail">Sg</span>
                </li>
                <li class="m-t-30 ">
                    <a href="{{ route('subdealer-groups') }}">Subdealer Groups</a>
                    <span class="icon-thumbnail">Sg</span>
                </li>
            </ul>
        </li>

        <li class="m-t-30 ">
          <a href="javascript:;">
            <span class="title">Credits And Payments</span>
            <span class=" arrow"></span>
          </a>
          <span class="icon-thumbnail bg-success"><i class="pg-form"></i></span>
          <ul class="sub-menu">

            <li class="m-t-30 ">
              <a href="{{ route('payment-accounts') }}">Payment Methods</a>
              <span class="icon-thumbnail">Pa</span>
            </li>
            
            <li class="m-t-30 ">
              <a href="{{ route('packages') }}">Packages</a>
              <span class="icon-thumbnail">Pa</span>
            </li>
            <li class="m-t-30 ">
              <a href="{{ route('fms-packages') }}">Packages For Subdealers</a>
              <span class="icon-thumbnail">Pa</span>
            </li>
            <li class="">
              <a href="{{ route('unit-price') }}">Prices</a>
              <span class="icon-thumbnail">UP</span>
            </li>
            <li class="">
              <a href="{{ route('default-elorus-template') }}">Default Elorus Template ID</a>
              <span class="icon-thumbnail">UP</span>
            </li>
          </ul>
        </li>

        

        <li class="m-t-30 ">
          <a href="javascript:;">
            <span class="title">Messaging</span>
            <span class=" arrow"></span>
          </a>
          <span class="icon-thumbnail bg-success"><i class="pg-form"></i></span>
          <ul class="sub-menu">
            <li class="">
              <a href="{{ route('feeds') }}">Notifications</a>
              <span class="icon-thumbnail">NF</span>
            </li>
            <li class="">
              <a href="{{ route('email-templates') }}">Email Templates</a>
              <span class="icon-thumbnail">ET</span>
            </li>
            <li class="">
              <a href="{{ route('message-templates') }}">SMS Templates</a>
              <span class="icon-thumbnail">MT</span>
            </li>
            <li class="">
              <a href="{{ route('work-hours') }}">Working Hours</a>
              <span class="icon-thumbnail">WH</span>
            </li>
            <li class="">
              <a href="{{ route('reminder-manager') }}">Messaging Manager</a>
              <span class="icon-thumbnail">Rm</span>
          </ul>
        </li>
        
       <li class="m-t-30 ">
          <a href="javascript:;">
            <span class="title">API Logs</span>
            <span class=" arrow"></span>
          </a>
          <span class="icon-thumbnail bg-success"><i class="pg-form"></i></span>
          <ul class="sub-menu">
            <li class="">
              <a href="{{ route('stripe-logs') }}">Stripe Logs</a>
              <span class="icon-thumbnail">NF</span>
            </li>
            <li class="">
              <a href="{{ route('paypal-logs') }}">Paypal Logs</a>
              <span class="icon-thumbnail">NF</span>
            </li>
            <li class="">
              <a href="{{ route('alientech-logs') }}">Alientech Logs</a>
              <span class="icon-thumbnail">NF</span>
            </li>
            <li class="">
              <a href="{{ route('autoturner-logs') }}">Autoturner Logs</a>
              <span class="icon-thumbnail">NF</span>
            </li>
            <li class="">
              <a href="{{ route('magic-logs') }}">Magic Logs</a>
              <span class="icon-thumbnail">ET</span>
            </li>
            <li class="">
              <a href="{{ route('elorus-logs') }}">Elorus Logs</a>
              <span class="icon-thumbnail">MT</span>
            </li>
            <li class="">
              <a href="{{ route('zoho-logs') }}">Zoho Logs</a>
              <span class="icon-thumbnail">WH</span>
            </li>
            <li class="">
              <a href="{{ route('sms-logs') }}">SMS Logs</a>
              <span class="icon-thumbnail">Rm</span>
            </li>
            <li class="">
              <a href="{{ route('email-logs') }}">Email Logs</a>
              <span class="icon-thumbnail">Rm</span>
            </li>
            </ul>
        </li>

        <li class="m-t-30 ">
          <a href="javascript:;">
            <span class="title">Platform Settings</span>
            <span class=" arrow"></span>
          </a>
          <span class="icon-thumbnail bg-success"><i class="pg-form"></i></span>
          <ul class="sub-menu">

            {{-- <li class="">
              <a href="{{ route('frontends') }}">Front Ends</a>
              <span class="icon-thumbnail">FE</span>
            </li> --}}

            <li class="">
              <a href="{{ route('modifications') }}">Modification</a>
              <span class="icon-thumbnail">MO</span>
            </li>

            <li class="">
              <a href="{{ route('timers') }}">Timers</a>
              <span class="icon-thumbnail">Ti</span>
            </li>

            <li class="">
              <a href="{{ route('dtc-lookup') }}">DTC Lookup</a>
              <span class="icon-thumbnail">DL</span>
            </li>

            <li class="">
              <a href="{{ route('bosch-lookup') }}">BOSCH Lookup</a>
              <span class="icon-thumbnail">BL</span>
            </li>

            <li class="m-t-30 ">
              <a href="{{ route('logs') }}">Logs</a>
              <span class="icon-thumbnail">Lg</span>
            </li>
            
            <li class="">
              <a href="{{ route('engineers') }}">Engineers</a>
              <span class="icon-thumbnail">En</span>
            </li>
            <li class="">
              <a href="{{ route('engineers-assignment') }}">Engineers Assignment Rules</a>
              <span class="icon-thumbnail">Ea</span>
            </li>
            <li class="">
              <a href="{{ route('tools') }}">Tools</a>
              <span class="icon-thumbnail">To</span>
            </li>

            <li class="">
              <a href="{{ route('message-search') }}">Message Search</a>
              <span class="icon-thumbnail">MS</span>
            </li>

            <li class="">
              <a href="{{ route('reasons-to-reject') }}">Reasons To Reject</a>
              <span class="icon-thumbnail">RR</span>
            </li>

            <li class="">
              <a href="{{ route('brand-ecu-comments') }}">Brand ECU Comments</a>
              <span class="icon-thumbnail">BEC</span>
            </li>

            <li class="">
              <a href="{{ route('sample-messages.index') }}">Sample Messages</a>
              <span class="icon-thumbnail">SM</span>
            </li>
            
            {{-- <li class="">
              <a href="{{ route('frontends') }}">Frontends</a>
              <span class="icon-thumbnail">Fr</span>
            </li> --}}
           
            {{-- <li class="">
              <a href="{{ route(config('chatify.routes.prefix'))}}">Chat</a>
              <span class="icon-thumbnail">Ch</span>
            </li>
           --}}

          </ul>
        </li> 
        {{-- <li class="m-t-30 ">
          <a href="{{ route('numbers') }}" class="detailed">
            <span class="title">Bosch ECU Numbers</span>
          </a>
          <span class="bg-success icon-thumbnail"><i class="pg-save"></i></span>
        </li> --}}

      </ul>
      <div class="clearfix"></div>
    </div>
    <!-- END SIDEBAR MENU -->
  </nav>
  <!-- END SIDEBAR -->
  <!-- END SIDEBPANEL-->