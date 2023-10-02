@extends('layouts.app')
@section('pagespecificstyles')
<style>
    .main-table tbody tr td .checkbox label::after{
        left:3px !important;
    }
</style>
@endsection
@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title"><h3>Reminder Manager</h3>
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    {{-- <button data-redirect="" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Blank</span>
                    </button> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div class="card card-transparent">
                    <div class="card-header ">
                      
                    </div>
                    <div class="card-body">

                        <ul class="nav nav-tabs nav-tabs-fillup" data-init-reponsive-tabs="dropdownfx">
                            
                            <li class="nav-item">
                                <a href="#" data-toggle="tab" class="active" data-target="#slide1"><span>ECU Tech</span></a>
                            </li>

                            <li class="nav-item">
                              <a href="#" data-toggle="tab"  data-target="#slide2"><span>TuningX</span></a>
                            </li>

                            
                            
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane slide-left active" id="slide1">
                      <div class="table-responsive">
                        <div id="condensedTable_wrapper" class="dataTables_wrapper no-footer">
                            <table class="main-table table table-hover table-condensed no-footer" id="condensedTable" role="grid">
                            <thead>
                                <tr role="row">
                                    <th style="width:200px; color: black;" class="sorting_asc" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending text-black">Title</th>
                                    <th style="width: 200px; color:black;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column descending text-black">Admin</th>
                                    <th style="width: 200px; color: black;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column descending text-black">Admin</th>
                                    <th style="width: 200px; color:  black;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column descending text-black">Engineer</th>
                                    <th style="width: 200px; color: black;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column descending text-black">Engineer</th>
                                    <th style="width: 200px; color: black;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column descending text-black">Customer</th>
                                    <th style="width: 200px; color: black;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column descending text-black">Customer</th>
                            </thead>
                            <tbody>
                            <tr role="row" class="odd">
                                <td class="v-align-middle semi-bold sorting_1">Engineer Assignment</td>
                                <td class="v-align-middle">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['eng_assign_admin_email']) checked @endif id="eng_assign_admin_email" data-id="eng_assign_admin_email" data-frontend_id='1'>
                                        <label for="eng_assign_admin_email" class="">Enable Email</label>
                                    </div>
                                </td>
                                <td class="v-align-middle semi-bold">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['eng_assign_admin_sms']) checked @endif id="eng_assign_admin_sms" data-id="eng_assign_admin_sms" data-frontend_id='1'>
                                        <label for="eng_assign_admin_sms" class="">Enable SMS</label>
                                    </div>
                                </td>
                                <td class="v-align-middle">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['eng_assign_eng_email']) checked @endif id="eng_assign_eng_email" data-id="eng_assign_eng_email" data-frontend_id='1'>
                                        <label for="eng_assign_eng_email" class="">Enable Email</label>
                                    </div>
                                </td>
                                <td class="v-align-middle semi-bold">
                                    <div class="checkbox check-success">
                                        <input type="checkbox" @if($ecuTechManager['eng_assign_eng_sms']) checked @endif id="eng_assign_eng_sms" data-id="eng_assign_eng_sms" data-frontend_id='1'>
                                        <label for="eng_assign_eng_sms" class="">Enable SMS</label>
                                    </div>
                                </td>
                                <td class="v-align-middle">
                                    {{-- <div class="checkbox check-success ">
                                        <input type="checkbox" @if($manager['eng_assign_cus_email']) checked @endif id="eng_assign_cus_email">
                                        <label for="eng_assign_cus_email" class="">Enable Email</label>
                                    </div> --}}
                                </td>
                                <td class="v-align-middle semi-bold">
                                    {{-- <div class="checkbox check-success ">
                                        <input type="checkbox" @if($manager['eng_assign_cus_sms']) checked @endif id="eng_assign_cus_sms">
                                        <label for="eng_assign_cus_sms" class="">Enable SMS</label>
                                    </div> --}}
                                </td>
                                </tr>
                                <tr role="row" class="even">
                                <td class="v-align-middle semi-bold sorting_1">Customer File Upload</td>
                                <td class="v-align-middle">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['file_upload_admin_email']) checked @endif id="file_upload_admin_email" data-id="file_upload_admin_email" data-frontend_id='1'>
                                        <label for="file_upload_admin_email" class="">Enable Email</label>
                                    </div>
                                </td>
                                <td class="v-align-middle semi-bold">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['file_upload_admin_sms']) checked @endif id="file_upload_admin_sms" data-id="file_upload_admin_sms" data-frontend_id='1'>
                                        <label for="file_upload_admin_sms" class="">Enable SMS</label>
                                    </div>
                                </td>
                                <td class="v-align-middle">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['file_upload_eng_email']) checked @endif id="file_upload_eng_email" data-id="file_upload_eng_email" data-frontend_id='1'>
                                        <label for="file_upload_eng_email" class="">Enable Email</label>
                                    </div>
                                </td>
                                <td class="v-align-middle semi-bold">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['file_upload_eng_sms']) checked @endif id="file_upload_eng_sms" data-id="file_upload_eng_sms" data-frontend_id='1'>
                                        <label for="file_upload_eng_sms" class="">Enable SMS</label>
                                    </div>
                                </td>
                                <td class="v-align-middle">
                                    
                                </td>
                                <td class="v-align-middle semi-bold">
                                    
                                </td>
                                </tr>
                                <tr role="row" class="odd">
                                <td class="v-align-middle semi-bold sorting_1">Engineer File Upload</td>
                                <td class="v-align-middle">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['eng_file_upload_admin_email']) checked @endif id="eng_file_upload_admin_email" data-id="eng_file_upload_admin_email" data-frontend_id='1'>
                                        <label for="eng_file_upload_admin_email" class="">Enable Email</label>
                                    </div>
                                </td>
                                <td class="v-align-middle semi-bold">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['eng_file_upload_admin_sms']) checked @endif id="eng_file_upload_admin_sms" data-id="eng_file_upload_admin_sms" data-frontend_id='1'>
                                        <label for="eng_file_upload_admin_sms" class="">Enable SMS</label>
                                    </div>
                                </td>
                                <td class="v-align-middle">
                                    
                                </td>
                                <td class="v-align-middle semi-bold">
                                   
                                </td>
                                <td class="v-align-middle">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['eng_file_upload_cus_email']) checked @endif id="eng_file_upload_cus_email" data-id="eng_file_upload_cus_email" data-frontend_id='1'>
                                        <label for="eng_file_upload_cus_email" class="">Enable Email</label>
                                    </div>
                                </td>
                                <td class="v-align-middle semi-bold">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['eng_file_upload_cus_sms']) checked @endif id="eng_file_upload_cus_sms" data-id="eng_file_upload_cus_sms" data-frontend_id='1'>
                                        <label for="eng_file_upload_cus_sms" class="">Enable SMS</label>
                                    </div>
                                </td>
                                </tr>
                                <tr role="row" class="even">
                                <td class="v-align-middle semi-bold sorting_1">File New Request</td>
                                <td class="v-align-middle">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['file_new_req_admin_email']) checked @endif id="file_new_req_admin_email" data-id="file_new_req_admin_email" data-frontend_id='1'>
                                        <label for="file_new_req_admin_email" class="">Enable Email</label>
                                    </div>
                                </td>
                                <td class="v-align-middle semi-bold">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['file_new_req_admin_sms']) checked @endif id="file_new_req_admin_sms" data-id="file_new_req_admin_sms" data-frontend_id='1'>
                                        <label for="file_new_req_admin_sms" class="">Enable SMS</label>
                                    </div>
                                </td>
                                <td class="v-align-middle">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['file_new_req_eng_email']) checked @endif id="file_new_req_eng_email" data-id="file_new_req_eng_email" data-frontend_id='1'>
                                        <label for="file_new_req_eng_email" class="">Enable Email</label>
                                    </div>
                                </td>
                                <td class="v-align-middle semi-bold">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['file_new_req_eng_sms']) checked @endif id="file_new_req_eng_sms" data-id="file_new_req_eng_sms" data-frontend_id='1'>
                                        <label for="file_new_req_eng_sms" class="">Enable SMS</label>
                                    </div>
                                </td>
                                <td class="v-align-middle">
                                    
                                </td>
                                <td class="v-align-middle semi-bold">
                                    
                                </td>
                                </tr>
                                <tr role="row" class="odd">
                                <td class="v-align-middle semi-bold sorting_1">Message from Customer</td>
                                <td class="v-align-middle">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['msg_cus_admin_email']) checked @endif id="msg_cus_admin_email" data-id="msg_cus_admin_email" data-frontend_id='1'>
                                        <label for="msg_cus_admin_email" class="">Enable Email</label>
                                    </div>
                                </td>
                                <td class="v-align-middle semi-bold">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['msg_cus_admin_sms']) checked @endif id="msg_cus_admin_sms" data-id="msg_cus_admin_sms" data-frontend_id='1'>
                                        <label for="msg_cus_admin_sms" class="">Enable SMS</label>
                                    </div>
                                </td>
                                <td class="v-align-middle">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['msg_cus_eng_email']) checked @endif id="msg_cus_eng_email" data-id="msg_cus_eng_email" data-frontend_id='1'>
                                        <label for="msg_cus_eng_email" class="">Enable Email</label>
                                    </div>
                                </td>
                                <td class="v-align-middle semi-bold">
                                    <div class="checkbox check-success ">
                                        <input type="checkbox" @if($ecuTechManager['msg_cus_eng_sms']) checked @endif id="msg_cus_eng_sms" data-id="msg_cus_eng_sms" data-frontend_id='1'>
                                        <label for="msg_cus_eng_sms" class="">Enable SMS</label>
                                    </div>
                                </td>
                                <td class="v-align-middle">
                                    
                                </td>
                                <td class="v-align-middle semi-bold">
                                    
                                </td>
                                </tr>
                                <tr role="row" class="odd">
                                    <td class="v-align-middle semi-bold sorting_1">Message from Engineer</td>
                                    <td class="v-align-middle">
                                        <div class="checkbox check-success ">
                                            <input type="checkbox" @if($ecuTechManager['msg_eng_admin_email']) checked @endif id="msg_eng_admin_email" data-id="msg_eng_admin_email" data-frontend_id='1'>
                                            <label for="msg_eng_admin_email" class="">Enable Email</label>
                                        </div>
                                    </td>
                                    <td class="v-align-middle semi-bold">
                                        <div class="checkbox check-success ">
                                            <input type="checkbox" @if($ecuTechManager['msg_eng_admin_sms']) checked @endif id="msg_eng_admin_sms" data-id="msg_eng_admin_sms" data-frontend_id='1'>
                                            <label for="msg_eng_admin_sms" class="">Enable SMS</label>
                                        </div>
                                    </td>
                                    <td class="v-align-middle">
                                        
                                    </td>
                                    <td class="v-align-middle semi-bold">
                                        
                                    </td>
                                    <td class="v-align-middle">
                                        <div class="checkbox check-success ">
                                            <input type="checkbox" @if($ecuTechManager['msg_eng_cus_email']) checked @endif id="msg_eng_cus_email" data-id="msg_eng_cus_email" data-frontend_id='1'>
                                            <label for="msg_eng_cus_email" class="">Enable Email</label>
                                        </div>
                                    </td>
                                    <td class="v-align-middle semi-bold">
                                        <div class="checkbox check-success ">
                                            <input type="checkbox" @if($ecuTechManager['msg_eng_cus_sms']) checked @endif id="msg_eng_cus_sms" data-id="msg_eng_cus_sms" data-frontend_id='1'>
                                            <label for="msg_eng_cus_sms" class="">Enable SMS</label>
                                        </div>
                                    </td>
                                    </tr>
                                    <tr role="row" class="odd">
                                        <td class="v-align-middle semi-bold sorting_1">Status Change</td>
                                        <td class="v-align-middle">
                                            <div class="checkbox check-success ">
                                                <input type="checkbox" @if($ecuTechManager['status_change_admin_email']) checked @endif id="status_change_admin_email" data-id="status_change_admin_email" data-frontend_id='1'>
                                                <label for="status_change_admin_email" class="">Enable Email</label>
                                            </div>
                                        </td>
                                        <td class="v-align-middle semi-bold">
                                            <div class="checkbox check-success ">
                                                <input type="checkbox" @if($ecuTechManager['status_change_admin_sms']) checked @endif id="status_change_admin_sms" data-id="status_change_admin_sms" data-frontend_id='1'>
                                                <label for="status_change_admin_sms" class="">Enable SMS</label>
                                            </div>
                                        </td>
                                        <td class="v-align-middle">
                                            {{-- <div class="checkbox check-success ">
                                                <input type="checkbox" @if($manager['status_change_eng_email']) checked @endif id="status_change_eng_email">
                                                <label for="status_change_eng_email" class="">Enable Email</label>
                                            </div> --}}
                                        </td>
                                        <td class="v-align-middle semi-bold">
                                            {{-- <div class="checkbox check-success ">
                                                <input type="checkbox" @if($manager['status_change_eng_sms']) checked @endif id="status_change_eng_sms">
                                                <label for="status_change_eng_sms" class="">Enable SMS</label>
                                            </div> --}}
                                        </td>
                                        <td class="v-align-middle">
                                            <div class="checkbox check-success ">
                                                <input type="checkbox" @if($ecuTechManager['status_change_cus_email']) checked @endif id="status_change_cus_email" data-id="status_change_cus_email" data-frontend_id='1'>
                                                <label for="status_change_cus_email" class="">Enable Email</label>
                                            </div>
                                        </td>
                                        <td class="v-align-middle semi-bold">
                                            <div class="checkbox check-success ">
                                                <input type="checkbox" @if($ecuTechManager['status_change_cus_sms']) checked @endif id="status_change_cus_sms" data-id="status_change_cus_sms" data-frontend_id='1'>
                                                <label for="status_change_cus_sms" class="">Enable SMS</label>
                                            </div>
                                        </td>
                                        </tr>
                            </tbody>
                            </table>
                        </div>
                      </div>

                            </div>
                            <div class="tab-pane slide-left" id="slide2">

                                <div class="table-responsive">
                                    <div id="condensedTable_wrapper" class="dataTables_wrapper no-footer">
                                        <table class="main-table table table-hover table-condensed no-footer" id="condensedTable" role="grid">
                                        <thead>
                                            <tr role="row">
                                                <th style="width:200px; color: black;" class="sorting_asc" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Title: activate to sort column descending text-black">Title</th>
                                                <th style="width: 200px; color:black;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column descending text-black">Admin</th>
                                                <th style="width: 200px; color: black;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column descending text-black">Admin</th>
                                                <th style="width: 200px; color:  black;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column descending text-black">Engineer</th>
                                                <th style="width: 200px; color: black;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column descending text-black">Engineer</th>
                                                <th style="width: 200px; color: black;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column descending text-black">Customer</th>
                                                <th style="width: 200px; color: black;" class="sorting" tabindex="0" aria-controls="condensedTable" rowspan="1" colspan="1" aria-label="Key: activate to sort column descending text-black">Customer</th>
                                        </thead>
                                        <tbody>
                                        <tr role="row" class="odd">
                                            <td class="v-align-middle semi-bold sorting_1">Engineer Assignment</td>
                                            <td class="v-align-middle">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['eng_assign_admin_email']) checked @endif id="{{'eng_assign_admin_email2'}}" data-id="{{'eng_assign_admin_email'}}" data-frontend_id='2'>
                                                    <label for="eng_assign_admin_email2" class="">Enable Email</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle semi-bold">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['eng_assign_admin_sms']) checked @endif id="{{'eng_assign_admin_sms2'}}" data-id="{{'eng_assign_admin_sms'}}" data-frontend_id='2'>
                                                    <label for="eng_assign_admin_sms2" class="">Enable SMS</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['eng_assign_eng_email']) checked @endif id="{{'eng_assign_eng_email2'}}" data-id="{{'eng_assign_eng_email'}}" data-frontend_id='2'>
                                                    <label for="eng_assign_eng_email2" class="">Enable Email</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle semi-bold">
                                                <div class="checkbox check-success">
                                                    <input type="checkbox" @if($tuningxManager['eng_assign_eng_sms']) checked @endif id="{{'eng_assign_eng_sms2'}}" data-id="{{'eng_assign_eng_sms'}}" data-frontend_id='2'>
                                                    <label for="eng_assign_eng_sms2" class="">Enable SMS</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle">
                                                {{-- <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($manager['eng_assign_cus_email']) checked @endif id="eng_assign_cus_email">
                                                    <label for="eng_assign_cus_email" class="">Enable Email</label>
                                                </div> --}}
                                            </td>
                                            <td class="v-align-middle semi-bold">
                                                {{-- <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($manager['eng_assign_cus_sms']) checked @endif id="eng_assign_cus_sms">
                                                    <label for="eng_assign_cus_sms" class="">Enable SMS</label>
                                                </div> --}}
                                            </td>
                                            </tr>
                                            <tr role="row" class="even">
                                            <td class="v-align-middle semi-bold sorting_1">Customer File Upload</td>
                                            <td class="v-align-middle">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['file_upload_admin_email']) checked @endif id="file_upload_admin_email2" id="file_upload_admin_email" data-frontend_id='2'>
                                                    <label for="file_upload_admin_email2" class="">Enable Email</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle semi-bold">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['file_upload_admin_sms']) checked @endif id="file_upload_admin_sms2"  data-id="file_upload_admin_sms" data-frontend_id='2'>
                                                    <label for="file_upload_admin_sms2" class="">Enable SMS</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['file_upload_eng_email']) checked @endif id="file_upload_eng_email2" data-id="file_upload_eng_email" data-frontend_id='2'>
                                                    <label for="file_upload_eng_email2" class="">Enable Email</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle semi-bold">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['file_upload_eng_sms']) checked @endif id="file_upload_eng_sms2" data-id="file_upload_eng_sms" data-frontend_id='2'>
                                                    <label for="file_upload_eng_sms2" class="">Enable SMS</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle">
                                                
                                            </td>
                                            <td class="v-align-middle semi-bold">
                                                
                                            </td>
                                            </tr>
                                            <tr role="row" class="odd">
                                            <td class="v-align-middle semi-bold sorting_1">Engineer File Upload</td>
                                            <td class="v-align-middle">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['eng_file_upload_admin_email']) checked @endif id="eng_file_upload_admin_email2" data-id="eng_file_upload_admin_email" data-frontend_id='2'>
                                                    <label for="eng_file_upload_admin_email2" class="">Enable Email</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle semi-bold">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['eng_file_upload_admin_sms']) checked @endif id="eng_file_upload_admin_sms2" data-id="eng_file_upload_admin_sms" data-frontend_id='2'>
                                                    <label for="eng_file_upload_admin_sms2" class="">Enable SMS</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle">
                                                
                                            </td>
                                            <td class="v-align-middle semi-bold">
                                               
                                            </td>
                                            <td class="v-align-middle">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['eng_file_upload_cus_email']) checked @endif id="eng_file_upload_cus_email2" data-id="eng_file_upload_cus_email" data-frontend_id='2'>
                                                    <label for="eng_file_upload_cus_email2" class="">Enable Email</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle semi-bold">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['eng_file_upload_cus_sms']) checked @endif id="eng_file_upload_cus_sms2" data-id="eng_file_upload_cus_sms" data-frontend_id='2'>
                                                    <label for="eng_file_upload_cus_sms2" class="">Enable SMS</label>
                                                </div>
                                            </td>
                                            </tr>
                                            <tr role="row" class="even">
                                            <td class="v-align-middle semi-bold sorting_1">File New Request</td>
                                            <td class="v-align-middle">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['file_new_req_admin_email']) checked @endif id="file_new_req_admin_email2" data-id="file_new_req_admin_email" data-frontend_id='2'>
                                                    <label for="file_new_req_admin_email2" class="">Enable Email</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle semi-bold">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['file_new_req_admin_sms']) checked @endif id="file_new_req_admin_sms2" data-id="file_new_req_admin_sms" data-frontend_id='2'>
                                                    <label for="file_new_req_admin_sms2" class="">Enable SMS</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['file_new_req_eng_email']) checked @endif id="file_new_req_eng_email2" data-id="file_new_req_eng_email" data-frontend_id='2'>
                                                    <label for="file_new_req_eng_email2" class="">Enable Email</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle semi-bold">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['file_new_req_eng_sms']) checked @endif id="file_new_req_eng_sms2" data-id="file_new_req_eng_sms" data-frontend_id='2'>
                                                    <label for="file_new_req_eng_sms2" class="">Enable SMS</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle">
                                                
                                            </td>
                                            <td class="v-align-middle semi-bold">
                                                
                                            </td>
                                            </tr>
                                            <tr role="row" class="odd">
                                            <td class="v-align-middle semi-bold sorting_1">Message from Customer</td>
                                            <td class="v-align-middle">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['msg_cus_admin_email']) checked @endif id="msg_cus_admin_email2" data-id="msg_cus_admin_email" data-frontend_id='2'>
                                                    <label for="msg_cus_admin_email2" class="">Enable Email</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle semi-bold">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['msg_cus_admin_sms']) checked @endif id="msg_cus_admin_sms2" data-id="msg_cus_admin_sms" data-frontend_id='2'>
                                                    <label for="msg_cus_admin_sms2" class="">Enable SMS</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['msg_cus_eng_email']) checked @endif id="msg_cus_eng_email2" data-id="msg_cus_eng_email" data-frontend_id='2'>
                                                    <label for="msg_cus_eng_email2" class="">Enable Email</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle semi-bold">
                                                <div class="checkbox check-success ">
                                                    <input type="checkbox" @if($tuningxManager['msg_cus_eng_sms']) checked @endif id="msg_cus_eng_sms2" data-id="msg_cus_eng_sms" data-frontend_id='2'>
                                                    <label for="msg_cus_eng_sms2" class="">Enable SMS</label>
                                                </div>
                                            </td>
                                            <td class="v-align-middle">
                                                
                                            </td>
                                            <td class="v-align-middle semi-bold">
                                                
                                            </td>
                                            </tr>
                                            <tr role="row" class="odd">
                                                <td class="v-align-middle semi-bold sorting_1">Message from Engineer</td>
                                                <td class="v-align-middle">
                                                    <div class="checkbox check-success ">
                                                        <input type="checkbox" @if($tuningxManager['msg_eng_admin_email']) checked @endif id="msg_eng_admin_email2" data-id="msg_eng_admin_email" data-frontend_id='2'>
                                                        <label for="msg_eng_admin_email2" class="">Enable Email</label>
                                                    </div>
                                                </td>
                                                <td class="v-align-middle semi-bold">
                                                    <div class="checkbox check-success ">
                                                        <input type="checkbox" @if($tuningxManager['msg_eng_admin_sms']) checked @endif id="msg_eng_admin_sms2" data-id="msg_eng_admin_sms" data-frontend_id='2'>
                                                        <label for="msg_eng_admin_sms2" class="">Enable SMS</label>
                                                    </div>
                                                </td>
                                                <td class="v-align-middle">
                                                    
                                                </td>
                                                <td class="v-align-middle semi-bold">
                                                    
                                                </td>
                                                <td class="v-align-middle">
                                                    <div class="checkbox check-success ">
                                                        <input type="checkbox" @if($tuningxManager['msg_eng_cus_email']) checked @endif id="msg_eng_cus_email2" data-id="msg_eng_cus_email" data-frontend_id='2'>
                                                        <label for="msg_eng_cus_email2" class="">Enable Email</label>
                                                    </div>
                                                </td>
                                                <td class="v-align-middle semi-bold">
                                                    <div class="checkbox check-success ">
                                                        <input type="checkbox" @if($tuningxManager['msg_eng_cus_sms']) checked @endif id="msg_eng_cus_sms2" data-id="msg_eng_cus_sms" data-frontend_id='2'>
                                                        <label for="msg_eng_cus_sms2" class="">Enable SMS</label>
                                                    </div>
                                                </td>
                                                </tr>
                                                <tr role="row" class="odd">
                                                    <td class="v-align-middle semi-bold sorting_1">Status Change</td>
                                                    <td class="v-align-middle">
                                                        <div class="checkbox check-success ">
                                                            <input type="checkbox" @if($tuningxManager['status_change_admin_email']) checked @endif id="status_change_admin_email2" data-id="status_change_admin_email" data-frontend_id='2'>
                                                            <label for="status_change_admin_email2" class="">Enable Email</label>
                                                        </div>
                                                    </td>
                                                    <td class="v-align-middle semi-bold">
                                                        <div class="checkbox check-success ">
                                                            <input type="checkbox" @if($tuningxManager['status_change_admin_sms']) checked @endif id="status_change_admin_sms2" data-id="status_change_admin_sms" data-frontend_id='2'>
                                                            <label for="status_change_admin_sms2" class="">Enable SMS</label>
                                                        </div>
                                                    </td>
                                                    <td class="v-align-middle">
                                                        {{-- <div class="checkbox check-success ">
                                                            <input type="checkbox" @if($manager['status_change_eng_email']) checked @endif id="status_change_eng_email">
                                                            <label for="status_change_eng_email" class="">Enable Email</label>
                                                        </div> --}}
                                                    </td>
                                                    <td class="v-align-middle semi-bold">
                                                        {{-- <div class="checkbox check-success ">
                                                            <input type="checkbox" @if($manager['status_change_eng_sms']) checked @endif id="status_change_eng_sms">
                                                            <label for="status_change_eng_sms" class="">Enable SMS</label>
                                                        </div> --}}
                                                    </td>
                                                    <td class="v-align-middle">
                                                        <div class="checkbox check-success ">
                                                            <input type="checkbox" @if($tuningxManager['status_change_cus_email']) checked @endif id="status_change_cus_email2" data-id="status_change_cus_email" data-frontend_id='2'>
                                                            <label for="status_change_cus_email2" class="">Enable Email</label>
                                                        </div>
                                                    </td>
                                                    <td class="v-align-middle semi-bold">
                                                        <div class="checkbox check-success ">
                                                            <input type="checkbox" @if($tuningxManager['status_change_cus_sms']) checked @endif id="status_change_cus_sms2" data-id="status_change_cus_sms" data-frontend_id='2'>
                                                            <label for="status_change_cus_sms2" class="">Enable SMS</label>
                                                        </div>
                                                    </td>
                                                    </tr>
                                        </tbody>
                                        </table>
                                    </div>
                                  </div>

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
            let set_status_url = "{{ route('set-status-for-reminder-manager') }}";
            $('.checkbox').on('change', function(e){
                // let field = $(this).children(":first").attr("id");
                let field = $(this).children(":first").attr("data-id");
                let front_end_id = $(this).children(":first").attr("data-frontend_id");
                let checked = $(this).children(":first").is(":checked");
                $.ajax({
                url: set_status_url,
                type: "POST",
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    'checked': checked,
                    'field': field,
                    'front_end_id': front_end_id,
                },
                success: function(d) {
                    toastr.success('Reminder are saved!', 'Reminder Saved');
                }
            });
            });
    });

</script>

@endsection