<!--START QUICKVIEW -->
<div id="quickview" class="quickview-wrapper open" data-pages="quickview">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist" style="padding-left: 10px !important;">
      <li class="hide">
        <a href="#quickview-notes" data-target="#quickview-notes" data-toggle="tab" role="tab">Notes</a>
      </li>
      <li class="hide">
        <a href="#quickview-alerts" data-target="#quickview-alerts" data-toggle="tab" role="tab">Alerts</a>
      </li>
      <li class="">
        <a class="active" href="#quickview-chat" data-toggle="tab" role="tab">Live Support</a>
      </li>
    </ul>
    <a class="btn-link quickview-toggle" data-toggle-element="#quickview" data-toggle="quickview"><i class="pg-close"></i></a>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- BEGIN Notes !-->
      {{-- <div class="tab-pane no-padding" id="quickview-notes">
        <div class="view-port clearfix quickview-notes" id="note-views">
          <!-- BEGIN Note List !-->
          <div class="view list" id="quick-note-list">
            <div class="toolbar clearfix">
              <ul class="pull-right ">
                <li>
                  <a href="#" class="delete-note-link"><i class="fa fa-trash-o"></i></a>
                </li>
                <li>
                  <a href="#" class="new-note-link" data-navigate="view" data-view-port="#note-views" data-view-animation="push"><i class="fa fa-plus"></i></a>
                </li>
              </ul>
              <button class="btn-remove-notes btn btn-xs btn-block hide"><i class="fa fa-times"></i> Delete</button>
            </div>
            <ul>
              <!-- BEGIN Note Item !-->
              <li data-noteid="1">
                <div class="left">
                  <!-- BEGIN Note Action !-->
                  <div class="checkbox check-warning no-margin">
                    <input id="qncheckbox1" type="checkbox" value="1">
                    <label for="qncheckbox1"></label>
                  </div>
                  <!-- END Note Action !-->
                  <!-- BEGIN Note Preview Text !-->
                  <p class="note-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                  <!-- BEGIN Note Preview Text !-->
                </div>
                <!-- BEGIN Note Details !-->
                <div class="right pull-right">
                  <!-- BEGIN Note Date !-->
                  <span class="date">12/12/14</span>
                  <a href="#" data-navigate="view" data-view-port="#note-views" data-view-animation="push"><i class="fa fa-chevron-right"></i></a>
                  <!-- END Note Date !-->
                </div>
                <!-- END Note Details !-->
              </li>
              <!-- END Note List !-->
              <!-- BEGIN Note Item !-->
              <li data-noteid="2">
                <div class="left">
                  <!-- BEGIN Note Action !-->
                  <div class="checkbox check-warning no-margin">
                    <input id="qncheckbox2" type="checkbox" value="1">
                    <label for="qncheckbox2"></label>
                  </div>
                  <!-- END Note Action !-->
                  <!-- BEGIN Note Preview Text !-->
                  <p class="note-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                  <!-- BEGIN Note Preview Text !-->
                </div>
                <!-- BEGIN Note Details !-->
                <div class="right pull-right">
                  <!-- BEGIN Note Date !-->
                  <span class="date">12/12/14</span>
                  <a href="#"><i class="fa fa-chevron-right"></i></a>
                  <!-- END Note Date !-->
                </div>
                <!-- END Note Details !-->
              </li>
              <!-- END Note List !-->
              <!-- BEGIN Note Item !-->
              <li data-noteid="2">
                <div class="left">
                  <!-- BEGIN Note Action !-->
                  <div class="checkbox check-warning no-margin">
                    <input id="qncheckbox3" type="checkbox" value="1">
                    <label for="qncheckbox3"></label>
                  </div>
                  <!-- END Note Action !-->
                  <!-- BEGIN Note Preview Text !-->
                  <p class="note-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                  <!-- BEGIN Note Preview Text !-->
                </div>
                <!-- BEGIN Note Details !-->
                <div class="right pull-right">
                  <!-- BEGIN Note Date !-->
                  <span class="date">12/12/14</span>
                  <a href="#"><i class="fa fa-chevron-right"></i></a>
                  <!-- END Note Date !-->
                </div>
                <!-- END Note Details !-->
              </li>
              <!-- END Note List !-->
              <!-- BEGIN Note Item !-->
              <li data-noteid="3">
                <div class="left">
                  <!-- BEGIN Note Action !-->
                  <div class="checkbox check-warning no-margin">
                    <input id="qncheckbox4" type="checkbox" value="1">
                    <label for="qncheckbox4"></label>
                  </div>
                  <!-- END Note Action !-->
                  <!-- BEGIN Note Preview Text !-->
                  <p class="note-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                  <!-- BEGIN Note Preview Text !-->
                </div>
                <!-- BEGIN Note Details !-->
                <div class="right pull-right">
                  <!-- BEGIN Note Date !-->
                  <span class="date">12/12/14</span>
                  <a href="#"><i class="fa fa-chevron-right"></i></a>
                  <!-- END Note Date !-->
                </div>
                <!-- END Note Details !-->
              </li>
              <!-- END Note List !-->
              <!-- BEGIN Note Item !-->
              <li data-noteid="4">
                <div class="left">
                  <!-- BEGIN Note Action !-->
                  <div class="checkbox check-warning no-margin">
                    <input id="qncheckbox5" type="checkbox" value="1">
                    <label for="qncheckbox5"></label>
                  </div>
                  <!-- END Note Action !-->
                  <!-- BEGIN Note Preview Text !-->
                  <p class="note-preview">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                  <!-- BEGIN Note Preview Text !-->
                </div>
                <!-- BEGIN Note Details !-->
                <div class="right pull-right">
                  <!-- BEGIN Note Date !-->
                  <span class="date">12/12/14</span>
                  <a href="#"><i class="fa fa-chevron-right"></i></a>
                  <!-- END Note Date !-->
                </div>
                <!-- END Note Details !-->
              </li>
              <!-- END Note List !-->
            </ul>
          </div>
          <!-- END Note List !-->
          <div class="view note" id="quick-note">
            <div>
              <ul class="toolbar">
                <li><a href="#" class="close-note-link"><i class="pg-arrow_left"></i></a>
                </li>
                <li><a href="#" data-action="Bold" class="fs-12"><i class="fa fa-bold"></i></a>
                </li>
                <li><a href="#" data-action="Italic" class="fs-12"><i class="fa fa-italic"></i></a>
                </li>
                <li><a href="#" class="fs-12"><i class="fa fa-link"></i></a>
                </li>
              </ul>
              <div class="body">
                <div>
                  <div class="top">
                    <span>21st april 2014 2:13am</span>
                  </div>
                  <div class="content">
                    <div class="quick-note-editor full-width full-height js-input" contenteditable="true"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> --}}
      <!-- END Notes !-->
      <!-- BEGIN Alerts !-->
      {{-- <div class="tab-pane no-padding" id="quickview-alerts">
        <div class="view-port clearfix" id="alerts">
          <!-- BEGIN Alerts View !-->
          <div class="view bg-white">
            <!-- BEGIN View Header !-->
            <div class="navbar navbar-default navbar-sm">
              <div class="navbar-inner">
                <!-- BEGIN Header Controler !-->
                <a href="javascript:;" class="inline action p-l-10 link text-master" data-navigate="view" data-view-port="#chat" data-view-animation="push-parrallax">
                  <i class="pg-more"></i>
                </a>
                <!-- END Header Controler !-->
                <div class="view-heading">
                  Notications
                </div>
                <!-- BEGIN Header Controler !-->
                <a href="#" class="inline action p-r-10 pull-right link text-master">
                  <i class="pg-search"></i>
                </a>
                <!-- END Header Controler !-->
              </div>
            </div>
            <!-- END View Header !-->
            <!-- BEGIN Alert List !-->
            <div data-init-list-view="ioslist" class="list-view boreded no-top-border">
              <!-- BEGIN List Group !-->
              <div class="list-view-group-container">
                <!-- BEGIN List Group Header!-->
                <div class="list-view-group-header text-uppercase">
                  Calendar
                </div>
                <!-- END List Group Header!-->
                <ul>
                  <!-- BEGIN List Group Item!-->
                  <li class="alert-list">
                    <!-- BEGIN Alert Item Set Animation using data-view-animation !-->
                    <a href="javascript:;" class="align-items-center" data-navigate="view" data-view-port="#chat" data-view-animation="push-parrallax">
                      <p class="">
                        <span class="text-warning fs-10"><i class="fa fa-circle"></i></span>
                      </p>
                      <p class="p-l-10 overflow-ellipsis fs-12">
                        <span class="text-master">David Nester Birthday</span>
                      </p>
                      <p class="p-r-10 ml-auto fs-12 text-right">
                        <span class="text-warning">Today <br></span>
                        <span class="text-master">All Day</span>
                      </p>
                    </a>
                    <!-- END Alert Item!-->
                    <!-- BEGIN List Group Item!-->
                  </li>
                  <!-- END List Group Item!-->
                  <!-- BEGIN List Group Item!-->
                  <li class="alert-list">
                    <!-- BEGIN Alert Item Set Animation using data-view-animation !-->
                    <a href="#" class="align-items-center" data-navigate="view" data-view-port="#chat" data-view-animation="push-parrallax">
                      <p class="">
                        <span class="text-warning fs-10"><i class="fa fa-circle"></i></span>
                      </p>
                      <p class="p-l-10 overflow-ellipsis fs-12">
                        <span class="text-master">Meeting at 2:30</span>
                      </p>
                      <p class="p-r-10 ml-auto fs-12 text-right">
                        <span class="text-warning">Today</span>
                      </p>
                    </a>
                    <!-- END Alert Item!-->
                  </li>
                  <!-- END List Group Item!-->
                </ul>
              </div>
              <!-- END List Group !-->
              <div class="list-view-group-container">
                <!-- BEGIN List Group Header!-->
                <div class="list-view-group-header text-uppercase">
                  Social
                </div>
                <!-- END List Group Header!-->
                <ul>
                  <!-- BEGIN List Group Item!-->
                  <li class="alert-list">
                    <!-- BEGIN Alert Item Set Animation using data-view-animation !-->
                    <a href="javascript:;" class="p-t-10 p-b-10 align-items-center" data-navigate="view" data-view-port="#chat" data-view-animation="push-parrallax">
                      <p class="">
                        <span class="text-complete fs-10"><i class="fa fa-circle"></i></span>
                      </p>
                      <p class="col overflow-ellipsis fs-12 p-l-10">
                        <span class="text-master link">Jame Smith commented on your status<br></span>
                        <span class="text-master">“Perfection Simplified - Company Revox"</span>
                      </p>
                    </a>
                    <!-- END Alert Item!-->
                  </li>
                  <!-- END List Group Item!-->
                  <!-- BEGIN List Group Item!-->
                  <li class="alert-list">
                    <!-- BEGIN Alert Item Set Animation using data-view-animation !-->
                    <a href="javascript:;" class="p-t-10 p-b-10 align-items-center" data-navigate="view" data-view-port="#chat" data-view-animation="push-parrallax">
                      <p class="">
                        <span class="text-complete fs-10"><i class="fa fa-circle"></i></span>
                      </p>
                      <p class="col overflow-ellipsis fs-12 p-l-10">
                        <span class="text-master link">Jame Smith commented on your status<br></span>
                        <span class="text-master">“Perfection Simplified - Company Revox"</span>
                      </p>
                    </a>
                    <!-- END Alert Item!-->
                  </li>
                  <!-- END List Group Item!-->
                </ul>
              </div>
              <div class="list-view-group-container">
                <!-- BEGIN List Group Header!-->
                <div class="list-view-group-header text-uppercase">
                  Sever Status
                </div>
                <!-- END List Group Header!-->
                <ul>
                  <!-- BEGIN List Group Item!-->
                  <li class="alert-list">
                    <!-- BEGIN Alert Item Set Animation using data-view-animation !-->
                    <a href="#" class="p-t-10 p-b-10 align-items-center" data-navigate="view" data-view-port="#chat" data-view-animation="push-parrallax">
                      <p class="">
                        <span class="text-danger fs-10"><i class="fa fa-circle"></i></span>
                      </p>
                      <p class="col overflow-ellipsis fs-12 p-l-10">
                        <span class="text-master link">12:13AM GTM, 10230, ID:WR174s<br></span>
                        <span class="text-master">Server Load Exceeted. Take action</span>
                      </p>
                    </a>
                    <!-- END Alert Item!-->
                  </li>
                  <!-- END List Group Item!-->
                </ul>
              </div>
            </div>
            <!-- END Alert List !-->
          </div>
          <!-- EEND Alerts View !-->
        </div>
      </div> --}}
      <!-- END Alerts !-->
      <div class="tab-pane active no-padding" id="quickview-chat">
        <div class="view-port clearfix" id="chat">
          <div class="view bg-white" id="contacts">
            <!-- BEGIN View Header !-->
            <div class="navbar navbar-default">
              <div class="navbar-inner">
                <!-- BEGIN Header Controler !-->
                <a href="javascript:;" class="inline action p-l-10 link text-master" data-navigate="view" data-view-port="#chat" data-view-animation="push-parrallax">
                  <i class="pg-plus hide"></i>
                </a>
                <!-- END Header Controler !-->
                <div class="view-heading">
                  Customer List
                  <div class="fs-11 hide">Show All</div>
                </div>
                <!-- BEGIN Header Controler !-->
                <a href="#" class="inline action p-r-10 pull-right link text-master">
                  <i class="pg-more hide"></i>
                </a>
                <!-- END Header Controler !-->
              </div>
            </div>
            <!-- END View Header !-->
            <div data-init-list-view="ioslist" class="list-view boreded no-top-border">
              <div class="list-view-group-container">
               
                <ul class="listOfContactsMain">
                  <!-- BEGIN Chat User List Item  !-->
                  
                  {{-- <li class="chat-user-list clearfix">
                    <a data-view-animation="push-parrallax" data-view-port="#chat" data-navigate="view" class="" href="#">
                      <span class="thumbnail-wrapper d32 circular bg-success">
                          <img width="34" height="34" alt="" data-src-retina="/users-avatar/avatar.png" data-src="/users-avatar/avatar.png" src="/users-avatar/avatar.png" class="col-top">
                      </span>
                      <p class="p-l-10 ">
                        <span class="text-master">ava flores</span>
                        <span class="block text-master hint-text fs-12">Hello there</span>
                      </p>
                    </a>
                  </li> --}}
                 
                  <!-- END Chat User List Item  !-->
                </ul>
              </div>
            </div>
          </div>
          <!-- BEGIN Conversation View  !-->
          <div class="view chat-view bg-white clearfix" id="conversation-view">
            <!-- BEGIN Header  !-->
            <div class="navbar navbar-default">
              <div class="navbar-inner">
                <a href="javascript:;" class="close-conversation link text-master inline action p-l-10 p-r-10" data-navigate="view" data-view-port="#chat" data-view-animation="push-parrallax">
                  <i class="pg-arrow_left"></i>
                </a>
                <div class="view-heading">
                  <span id="username"></span>
                  <div class="fs-11 hint-text" id="user-active">Online</div>
                </div>
                <a href="#" class="link text-master inline action p-r-10 pull-right ">
                  <i class="pg-more hide"></i>
                </a>
              </div>
            </div>
            <!-- END Header  !-->
            <!-- BEGIN Conversation  !-->
            <div class="chat-inner" id="conversation">
              <!-- BEGIN From Me Message  !-->
              
              
              <!-- END From Them Message  !-->
            </div>
            <!-- BEGIN Conversation  !-->
            <!-- BEGIN Chat Input  !-->
            <div class="b-t b-grey bg-white clearfix p-l-10 p-r-10">
              <div class="row">
                {{-- <form id="message-form" method="POST" action="{{route('send.message')}}" enctype="multipart/form-data">
                  @csrf     
                  <input type="hidden" name="type" value="engineer">
                  <label>
                    <svg style="width: 10%;" class="svg-inline--fa fa-paperclip fa-w-14" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="paperclip" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M43.246 466.142c-58.43-60.289-57.341-157.511 1.386-217.581L254.392 34c44.316-45.332 116.351-45.336 160.671 0 43.89 44.894 43.943 117.329 0 162.276L232.214 383.128c-29.855 30.537-78.633 30.111-107.982-.998-28.275-29.97-27.368-77.473 1.452-106.953l143.743-146.835c6.182-6.314 16.312-6.422 22.626-.241l22.861 22.379c6.315 6.182 6.422 16.312.241 22.626L171.427 319.927c-4.932 5.045-5.236 13.428-.648 18.292 4.372 4.634 11.245 4.711 15.688.165l182.849-186.851c19.613-20.062 19.613-52.725-.011-72.798-19.189-19.627-49.957-19.637-69.154 0L90.39 293.295c-34.763 35.56-35.299 93.12-1.191 128.313 34.01 35.093 88.985 35.137 123.058.286l172.06-175.999c6.177-6.319 16.307-6.433 22.626-.256l22.877 22.364c6.319 6.177 6.434 16.307.256 22.626l-172.06 175.998c-59.576 60.938-155.943 60.216-214.77-.485z"></path></svg><!-- <span class="fas fa-paperclip"></span> --><input type="file" class="upload-attachment" name="file" accept=".png, .jpg, .jpeg, .gif, .zip, .rar, .txt"></label>
                <textarea name="message" class="m-send app-scroll" placeholder="Type a message.." style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                <button type="submit" class="btn" id="sendBtn" autofocus>Send</button>
                </form> --}}
                <form id="message-form-main" method="POST" action="{{route('send.message')}}" enctype="multipart/form-data">
                <div class="col-1 p-t-15">
                  <input type="hidden" value="25" id="chat-user-id">
                  <input name="file" type="file" id="upload-attachment">
                </div>
                <div class="col-10 p-b-10 text-center">
                  <input autocomplete="off" name="message" type="text" class="form-control m-t-10 m-b-10 full-width" id="chat-input" placeholder="Say something">
                  <button class="btn btn-success" type="submit">Send</button>
                </div>
                </form>
                {{-- <div class="col-2 link text-master m-l-10 m-t-15 p-l-10 b-l b-grey col-top">
                  <a href="#" class="link text-master"><i class="pg-camera"></i></a>
                </div> --}}
              </div>
            </div>
            <!-- END Chat Input  !-->
          </div>
          <!-- END Conversation View  !-->
        </div>

      </div>
    </div>
  </div>
<!-- END QUICKVIEW-->