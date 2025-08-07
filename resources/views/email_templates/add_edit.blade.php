@extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title">
                  @if(isset($template))
                  <h5>
                    Edit Template
                  </h5>
                @else
                  <h5>
                    Add Template
                  </h5>
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('email-templates')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Templates</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="@if(isset($template)){{route('update-template')}}@else{{ route('post-template') }}@endif" enctype="multipart/form-data" novalidate>
                @csrf
                @if(isset($template))
                  <input name="id" type="hidden" value="{{ $template->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Name</label>
                  <input value="@if(isset($template)) {{ $template->name }} @else{{old('name') }}@endif"  name="name" type="text" class="form-control" readonly>
                </div>
                @error('name')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>HTML</label>
                  <textarea  name="html" type="text" class="form-control" required>@if(isset($template)) {{ $template->html }} @else{{old('html') }}@endif</textarea>
                </div>
                @error('html')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="text-center m-t-40">                    
                  <button id="submit" class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($template)) Update @else Add @endif</span></button>
                  @if(isset($template))
                    <button  class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$template->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                  @endif
                </div>
              </form>
                
            </div>
          </div>
        </div>
    </div>
</div>
@endsection


@section('pagespecificscripts')

<script src="https://cdn.tiny.cloud/1/kbsllb80d8jtmwqzieq95lytq6g3estvo7caidv5nmvg1zjn/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

@if(isset($template))
  
  <script type="text/javascript">
    $( document ).ready(function(event) {
      tinymce.init({
        selector: '#tinymce-editor',
        setup: function (editor) {
          editor.on('init', function (e) {
            editor.setContent('{{$template->html}}', { format: "text" });
          });
        }
      });
    });

    $( document ).on("click", "#submit" , function () {
            $('#' + 'tinymce-editor').html( tinymce.get('tinymce-editor').getContent({ format: "text" }),  );
              var f = $(this).parents("form");
              var action = f.attr("action");
              var serializedForm = f.serialize();
              //tinyMCE.triggerSave(); also tried putting here
              $.ajax({
                  type: 'POST',
                  url: action,
                  data: serializedForm,
                  async: false,
                  success: function (data, textStatus, request) {
                      location.href = '/email_templates';
                  },
                  error: function (req, status, error) {
                      alert&("Error occurred!");
                  }
              });
            return false;
          }); 

  </script>

@else
<script type="text/javascript">
  $( document ).ready(function(event) {
    $( document ).on("click", "#submit" , function () {
            $('#' + 'tinymce-editor').html( tinymce.get('tinymce-editor').getContent() );
              var f = $(this).parents("form");
              var action = f.attr("action");
              var serializedForm = f.serialize();
              //tinyMCE.triggerSave(); also tried putting here
              $.ajax({
                  type: 'POST',
                  url: action,
                  data: serializedForm,
                  async: false,
                  success: function (data, textStatus, request) {
                      location.href = '/email_templates';
                  },
                  error: function (req, status, error) {
                      alert&("Error occurred!");
                  }
              });
            return false;
          }); 

          tinymce.init({
          selector: 'textarea',
          plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
          toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
          tinycomments_mode: 'embedded',
          tinycomments_author: 'Author name',
          mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
          ]
       });
  });
  </script>
@endif

<script type="text/javascript">

      $( document ).ready(function(event) {

      //     $( document ).on("click", "#submit" , function () {
      //       $('#' + 'tinymce-editor').html( tinymce.get('tinymce-editor').getContent() );
      //         var f = $(this).parents("form");
      //         var action = f.attr("action");
      //         var serializedForm = f.serialize();
      //         //tinyMCE.triggerSave(); also tried putting here
      //         $.ajax({
      //             type: 'POST',
      //             url: action,
      //             data: serializedForm,
      //             async: false,
      //             success: function (data, textStatus, request) {
      //                 location.href = '/email_templates';
      //             },
      //             error: function (req, status, error) {
      //                 alert&("Error occurred!");
      //             }
      //         });
      //       return false;
      //    });
          

      //     tinymce.init({
      //     selector: 'textarea',
      //     plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
      //     toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
      //     tinycomments_mode: 'embedded',
      //     tinycomments_author: 'Author name',
      //     mergetags_list: [
      //       { value: 'First.Name', title: 'First Name' },
      //       { value: 'Email', title: 'Email' },
      //     ]
      //  });

        $('.btn-delete').click(function() {
          Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
            if (result.isConfirmed) {
                    $.ajax({
                        url: "/delete_template",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Template has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/email_templates';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection