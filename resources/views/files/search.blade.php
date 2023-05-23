@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title"><h3>Search</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('file', $file->id)}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Back To File</span>
                    </button>
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div class="card-body text-center">
                    <img class="image-responsive-height demo-mw-50 loading" src="assets/img/demo/progress.svg" alt="Progress">
                    <h5 class="msg hide"></h5>
                    <a href="" class="btn btn-success download-btn hide">Download</a>
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

        const channelName = "private-chatify-download";
        var channel = pusher.subscribe(`${channelName}`);
        var clientSendChannel;
        var clientListenChannel;
        let page_file_id = '{{$file->id}}';
        
        channel.bind("download-button", function(data) {
        
        let file_id = data.file_id;

        if(data.status == 'completed'){

            if(file_id == page_file_id){
                $('.loading').addClass('hide');
                $('.msg').removeClass('hide');
                $('.msg').html('File Found. Ready for donwload and customer is notified.');
            }
        }
        if(data.status == 'download'){
                $('.loading').addClass('hide');
                $('.msg').removeClass('hide');
                $(".download-btn").attr("href", data.download_link);
                $('.download-btn').removeClass('hide');
                $('.msg').html('File Found and can be donwloaded.');
        }

        else{
            if(file_id == page_file_id){
                $('.loading').addClass('hide');
                $('.msg').removeClass('hide');
                $('.msg').html('File does not Found.');
            }
        }
        });

        setTimeout(
        function(){
           
            $.ajax({
                        url: '{{route("get-change-status")}}',
                        type: "POST",
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            'file_id': page_file_id
                        },
                        success: function(d) {
                            console.log(d);

                            if(d.fail == 1 && d.file_id == page_file_id){
                                $('.loading').addClass('hide');
                                $('.msg').removeClass('hide');
                                $('.msg').html(d.msg);
                            }

                            if(d.fail == 0 && d.file_id == page_file_id){
                                $('.loading').addClass('hide');
                                $('.msg').removeClass('hide');
                                $('.msg').html(d.msg);
                            }
                        }
                    });
            
        // }, 90000);
        }, 50000);

    });

</script>

@endsection