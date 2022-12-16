<html>
<head>
<style>

td {
  text-align: center;
  border: 1px grey solid;
}

th {
  text-align: center;
  border: 1px grey solid;
  background: lightblue;
}

</style>
</head>
<body>
<div id="ct" style="padding: 20px;">
    <div class="invoice-00001">
        <div class="content-section">
            <div class="inv--head-section inv--detail-section">
                <div class="row">
                    <div class="col-sm-6 col-12 ">
                          <img style="width: 210px; margin-bottom: 15px; " src="https://files.ecutech.gr/assets/img/logo2.png">
                        <div class="d-flex">
                           <h3 style="    margin-left: 0;" class="in-heading align-self-center">EcuTech Greece</h3>
                        </div>
                    </div>
                    <div class="col-sm-6 text-sm-right" style="float: right;">
                        <p class="inv-list-number"><span class="inv-title">Invoice : </span> <span class="inv-number">ECUTECH-00001</span></p>
                    </div>
                    <div class="col-sm-6 align-self-center  ">
                        <p class="inv-street-addr" style="width: 50%">Sampsountos 4A, Giannitsa 58100, Greece
</p>
                        <p class="inv-email-address">files@ecutech.gr</p>
                        <p class="inv-email-address">+302382700100</p>
                    </div>
                    <div class="col-sm-6 align-self-center mt-3 text-sm-right" style="float: right;">
                        <p class="inv-created-date"><span class="inv-title">Start Date : {{$start}}</span> <span class="inv-date"></span></p>
                        <p class="inv-due-date"><span class="inv-title">End Date : {{$end}}</span> <span class="inv-date"></span></p>
                    </div>
                </div>
            </div>

            <div class="inv--detail-section inv--customer-detail-section" style="margin-top: 100px; border-top: 1px gray solid;">

                <div class="row">

                    <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4 align-self-center">
                        <p class="inv-to">{{ \App\Models\User::findOrFail($engineer)->name}}'s Report</p>
                    </div>



                    <div class="col-xl-8 col-lg-7 col-md-6 col-sm-4">
                        <p class="inv-customer-name">Kostas Tsichlakidis</p>
                        <p class="inv-street-addr">Sampsountos 4 GIANNITSA 85</p>
                        <p class="inv-email-address">demo@ecutech.gr</p>
                        <p class="inv-email-address">123123</p>
                    </div>

                </div>
            </div>

            <div class="inv--product-table-section">
                <div class="table-responsive">
                    <table class="table" style="width: 100%;">
                        <thead class="">
                        <tr>
                            <th scope="col" style="width: 2%"> #</th>
                            <th scope="col" style="width: 30%;">Name</th>
                            <th style="width: 68%;" scope="col">Stage and Options</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php $count = 1; @endphp
                            @foreach($files as $file)
                            @php 
                                $options = '';
                                if($file->options){
                                    foreach($file->options() as $option){
                                        $options .= '<img class="p-l-10" alt="'.$option.'" width="15" height="15" data-src-retina="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'" data-src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'" src="'.url('icons').'/'.\App\Models\Service::where('name', $option)->first()->icon.'">'.$option;
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$file->brand}} {{ $file->engine }} {{ $file->vehicle()->TORQUE_standard }}</td>
                                <td class="text-right">
                                    <img class="p-r-5" alt="{{$file->stages}}" width="15" height="15" data-src="{{url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon}}" data-src-retina="{{url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon}}" src="{{url('icons').'/'.\App\Models\Service::where('name', $file->stages)->first()->icon}}">{{$file->stages}}{!!$options!!}
                                </td>
                                
                            </tr>
                            @php $count++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="inv--total-amounts">

                <div class="row mt-4">
                    <div class="col-sm-5 col-12 order-sm-0 order-1">
                    </div>
                    <div class="col-sm-7 col-12 order-sm-1 order-0">
                        <div class="text-sm-right">
                            <div class="row" style="float: right">
                                
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="inv--note" style="margin-top: 150px; border-top: 1px grey solid;">

                <div class="row mt-4">
                    <div class="col-sm-12 col-12 order-sm-0 order-1">
                        <p>Note: Thank you for Working with us.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>