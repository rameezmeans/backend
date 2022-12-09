@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">
          <div class="card card-transparent m-t-40">
              <div class="card-header">
                <a class="btn btn-success pull-right" href={{route('vehicle', $vehicle->id)}}>View Vehicle</a>
              </div>
            <div class="card-body">
               

                @php $first = 0; $second = 0; $third = 0; @endphp
                <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
                   
                    @foreach($ecus as $ecu)
                        <li class="nav-item">
                            <a href="#" class="@if($first == 0) active @endif" data-toggle="tab" data-target="#tab-fillup-{{$first}}"><span>{{$ecu}}</span></a>
                        </li> 
                        @php $first++; @endphp
                    @endforeach 
                </ul>
                   
                <div class="tab-content">
                    @foreach($ecus as $ecu)
                        <div class="tab-pane @if($second == 0) active @endif" id="tab-fillup-{{$second}}">
                            <div class="row">
                                <div class="col-lg-6">
                                  <h5 class="p-b-20">{{$vehicle->Name}}</h5>
                                 
                                  <div class="b-b b-t b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                    <p class="pull-left">Brand</p>
                                    <div class="pull-right">
                                      <span class="label label-success">{{$vehicle->Make}}<span>
                                    </div>
                                    <div class="clearfix"></div>
                                  </div>
                                  <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                    <p class="pull-left">Model</p>
                                    <div class="pull-right">
                                      <span class="label label-success">{{$vehicle->Model}}<span>
                                    </div>
                                    <div class="clearfix"></div>
                                  </div>
                                  <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                    <p class="pull-left">Generation</p>
                                    <div class="pull-right">
                                      <span class="label label-success">{{$vehicle->Generation}}<span>
                                    </div>
                                    <div class="clearfix"></div>
                                  </div>
                                  <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                    <p class="pull-left">Engine</p>
                                    <div class="pull-right">
                                      <span class="label label-success">{{$vehicle->Engine}}<span>
                                    </div>
                                    <div class="clearfix"></div>
                                  </div>
                                  <div class="b-b b-grey p-l-20 p-r-20 p-b-10 p-t-10">
                                    <p class="pull-left">Engine ECU</p>
                                    <div class="pull-right">
                                      <span class="label label-success">{{$ecu}}<span>
                                    </div>
                                    <div class="clearfix"></div>
                                  </div>
                                  <div class="p-b-20 p-t-40">
                                    <h5 class="">Comments</h5>
                                        @foreach($comments as $comment)
                                            @if($ecu == $comment->ecu)
                                                <div class="p-t-10">
                                                    <img alt="{{$comment->option}}" width="40" height="40" data-src-retina="{{ url('icons').'/'.\App\Models\Service::where('name', $comment->option)->first()->icon }}" data-src="{{ url('icons').'/'.\App\Models\Service::where('name', $comment->option)->first()->icon }}" src="{{ url('icons').'/'.\App\Models\Service::where('name', $comment->option)->first()->icon }}">
                                                    {{$comment->option}}
                                                </div> 
                                                <p> {{$comment->comments}}</p>
                                                @php $third++; @endphp
                                            @endif
                                        @endforeach
                                        @if($third == 0)
                                            <p>No Comments.</p>
                                        @endif
                                    <div class="text-center"><button data-toggle="modal" data-target="#modalSlideUp-{{$second}}" class="btn btn-success">Add New Comments</button></div>
                                </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="modal fade slide-up disable-scroll" id="modalSlideUp-{{$second}}" tabindex="-1" role="dialog" aria-hidden="false">
                            <div class="modal-dialog">
                              <div class="modal-content-wrapper">
                                <div class="modal-content">
                                  <div class="modal-header clearfix text-left">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                                    </button>
                                    <h5>Options <span class="semi-bold"> and Comment</span></h5>
                                    <p class="p-b-10">Please select option and add comments.</p>
                                  </div>
                                  <div class="modal-body">
                                    <form role="form" action="{{route('add-option-comments')}}" method="POST">
                                      @csrf
                                      <input type="hidden" name="engine" value="{{$vehicle->Engine}}">
                                      <input type="hidden" name="make" value="{{$vehicle->Make}}">
                                      <input type="hidden" name="ecu" value="{{$ecu}}">
                                      <input type="hidden" name="generation" value="{{$vehicle->Generation}}">
                                      <input type="hidden" name="model" value="{{$vehicle->Model}}">
                                      <input type="hidden" name="id" value="{{$vehicle->id}}">
                                      <div class="form-group form-group-default required ">
                                        <label>Option</label>
                                        <select class="full-width" data-init-plugin="select2" name="option">
                                          @foreach($options as $option)
                                            @if(!in_array($option->name, $includedOptions))
                                            <option value="{{$option->name}}">{{$option->name}}</option>
                                            @endif
                                          @endforeach
                                        </select>
                                      </div>
                                      <div class="form-group-attached ">
                                        <div class="row">
                                          <div class="col-md-12">
                                            
                                            <div class="form-group form-group-default required">
                                              <label>Comment</label>
                                              <textarea name="comments" required style="height: 100px;" class="form-control"></textarea>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                   
                                    <div class="row">
                                      <div class="col-md-4 m-t-10 sm-m-t-10 text-center">
                                        <button type="submit" class="btn btn-success btn-block m-t-5">Add Comment</button>
                                      </div>
                                    </div>
                                  </form>
                                  </div>
                                </div>
                              </div>
                              <!-- /.modal-content -->
                            </div>
                          </div>
                        @php $second++; @endphp
                    @endforeach 
                        
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