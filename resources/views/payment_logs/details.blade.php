@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title"><h3>Details</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('all-payments')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">All Payments</span>
                    </button>
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <div class="card card-transparent flex-row">
                <ul class="nav nav-tabs nav-tabs-simple nav-tabs-left bg-white" id="tab-3">
                  <li class="nav-item">
                    <a href="#" class="active" data-toggle="tab" data-target="#tab3hellowWorld">One</a>
                  </li>
                  <li class="nav-item">
                    <a href="#" data-toggle="tab" data-target="#tab3FollowUs">Two</a>
                  </li>
                  <li class="nav-item">
                    <a href="#" data-toggle="tab" data-target="#tab3Inspire">Three</a>
                  </li>
                </ul>
                <div class="tab-content bg-white">
                  <div class="tab-pane" id="tab3hellowWorld">
                    <div class="row column-seperation">
                      <div class="col-lg-6">
                        <h3>
                        <span class="semi-bold">Sometimes </span>Small things in life
                        means the most
                        </h3>
                      </div>
                      <div class="col-lg-6">
                        <h3 class="semi-bold">
                          great tabs
                        </h3>
                        <p>Native boostrap tabs customized to Pages look and feel, simply changing class name you can change color as well as its animations</p>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane active" id="tab3FollowUs">
                    <h3>
                      “ Nothing is <span class="semi-bold">impossible</span>, the word
                      itself says 'I'm <span class="semi-bold">possible</span>'! ”
                    </h3>
                    <p>
                      A style represents visual customizations on top of a layout. By editing a style, you can use Squarespace's visual interface to customize your...
                    </p>
                    <br>
                    <p class="pull-right">
                      <button class="btn btn-default btn-cons" type="button">White</button>
                      <button class="btn btn-success btn-cons" type="button">Success</button>
                    </p>
                  </div>
                  <div class="tab-pane" id="tab3Inspire">
                    <h3>
                      Follow us &amp; get updated!
                    </h3>
                    <p>
                      Instantly connect to what's most important to you. Follow your friends, experts, favorite celebrities, and breaking news.
                    </p>
                    <br>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection


@section('pagespecificscripts')

<script type="text/javascript">

    $( document ).ready(function(event) {
        
      
    });

</script>

@endsection