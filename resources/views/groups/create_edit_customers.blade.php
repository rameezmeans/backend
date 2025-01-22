 @extends('layouts.app')

@section('content')
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
      <!-- START CONTAINER FLUID -->
        <div class=" container-fluid   container-fixed-lg bg-white">

          @if (Session::get('success'))
                <div class="pgn-wrapper" data-position="top" style="top: 59px;">
                    <div class="pgn push-on-sidebar-open pgn-bar">
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            {{ Session::get('success') }}
                        </div>
                    </div>
                </div>
            @endif
            @php
              Session::forget('success')
            @endphp
            <!-- START card -->

          <div class="card card-transparent m-t-40">
            <div class="card-header ">
                <div class="card-title">
                  @if(isset($customer))
                    <h5>
                      Edit Customer
                    </h5>
                  @else
                    <h5>
                      Add Customer
                    </h5>
                  @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    @if(isset($customer))
                      <button data-redirect="{{route('edit-credit', $customer->id)}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Transactions</span>
                      </button>
                    @endif

                    <button data-redirect="{{route('customers')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Customers</span>
                    </button>

                    <button data-redirect="{{route('changes', $customer->id)}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Logs</span>
                    </button>

                    {{-- <button id="testButton" class="btn btn-success btn-cons m-b-10" type="button"><i class="pg-plus_circle"></i> <span class="bold">Set Test Status</span>
                    </button> --}}

                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">

              @if(isset($customer))
              <div class="m-b-20">
                <span class="label @if($customer->front_end_id == 1) text-white bg-primary @elseif($customer->front_end_id == 3) text-white bg-info @else text-black bg-warning @endif">{{$customer->frontend->name}}<span>
              </div>
              @endif
              <form class="" role="form" method="POST" action="@if(isset($customer)){{route('update-customer')}}@else{{ route('add-customer') }}@endif" enctype="multipart/form-data" autocomplete="off">
                @csrf
                @if(isset($customer))
                  <input name="id" type="hidden" value="{{ $customer->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Name</label>
                  <input value="@if(isset($customer)){{ $customer->name }}@else{{old('name') }}@endif"  name="name" type="text" class="form-control" required>
                </div>
                @error('name')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Email</label>
                  <input @if(isset($customer)) disabled @endif value="@if(isset($customer)){{$customer->email}}@else{{old('email')}}@endif"  name="email" type="text" class="form-control" required>
                </div>
                @error('email')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                @if(isset($customer))
                  <div class="form-group form-group-default ">
                    <label>Change Password</label>
                    <input value=""  name="password" type="password" class="form-control">
                  </div>
                  @error('password')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                  @else
                  <div class="form-group form-group-default required">
                    <label>Password</label>
                    <input value=""  name="password" type="password" class="form-control" required>
                  </div>
                  @error('password')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                @endif
                <div class="form-group form-group-default required ">
                  <label>Phone</label>
                  <input value="@if(isset($customer)){{ $customer->phone }}@else{{old('phone') }}@endif"  name="phone" type="text" class="form-control" required>
                </div>
                @error('phone')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
               
                  <div class="form-group form-group-default required ">
                    <label>Language</label>
                    <select class="full-width" data-init-plugin="select2" name="language">
                      <option @if(isset($customer) && $customer->language == 'English') selected @endif value="English">English</option>
                      <option  @if(isset($customer) && $customer->language == 'French') selected @endif value="French">Frensh</option>
                    </select>
                  </div>
                
                @error('language')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Address</label>
                  <input value="@if(isset($customer)){{ $customer->address }}@else{{old('address') }}@endif"  name="address" type="text" class="form-control" required>
                </div>
                @error('address')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Zip</label>
                  <input value="@if(isset($customer)){{ $customer->zip }}@else{{old('zip') }}@endif"  name="zip" type="text" class="form-control" required>
                </div>
                @error('zip')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>City</label>
                  <input value="@if(isset($customer)){{ $customer->city }}@else{{old('city') }}@endif"  name="city" type="text" class="form-control" required>
                </div>
                @error('city')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Country</label>
                  <select name="country" id="country" class="full-width country" data-init-plugin="select2">
  
                    <option @if(isset($customer) && $customer->country == 'AF') selected @endif value="AF">Afghanistan</option>
                    <option @if(isset($customer) && $customer->country == 'AX') selected @endif value="AX">Aland Islands</option>
                    <option @if(isset($customer) && $customer->country == 'AL') selected @endif value="AL">Albania</option>
                    <option @if(isset($customer) && $customer->country == 'DZ') selected @endif value="DZ">Algeria</option>
                    <option @if(isset($customer) && $customer->country == 'AS') selected @endif value="AS">American Samoa</option>
                    <option @if(isset($customer) && $customer->country == 'AD') selected @endif value="AD">Andorra</option>
                    <option @if(isset($customer) && $customer->country == 'AO') selected @endif value="AO">Angola</option>
                    <option @if(isset($customer) && $customer->country == 'AI') selected @endif value="AI">Anguilla</option>
                    <option @if(isset($customer) && $customer->country == 'AQ') selected @endif value="AQ">Antarctica</option>
                    <option @if(isset($customer) && $customer->country == 'AG') selected @endif value="AG">Antigua and Barbuda</option>
                    <option @if(isset($customer) && $customer->country == 'AR') selected @endif value="AR">Argentina</option>
                    <option @if(isset($customer) && $customer->country == 'AM') selected @endif value="AM">Armenia</option>
                    <option @if(isset($customer) && $customer->country == 'AW') selected @endif value="AW">Aruba</option>
                    <option @if(isset($customer) && $customer->country == 'AU') selected @endif value="AU">Australia</option>
                    <option @if(isset($customer) && $customer->country == 'AT') selected @endif value="AT">Austria</option>
                    <option @if(isset($customer) && $customer->country == 'AZ') selected @endif value="AZ">Azerbaijan</option>
                    <option @if(isset($customer) && $customer->country == 'BS') selected @endif value="BS">Bahamas</option>
                    <option @if(isset($customer) && $customer->country == 'BH') selected @endif value="BH">Bahrain</option>
                    <option @if(isset($customer) && $customer->country == 'BD') selected @endif value="BD">Bangladesh</option>
                    <option @if(isset($customer) && $customer->country == 'BB') selected @endif value="BB">Barbados</option>
                    <option @if(isset($customer) && $customer->country == 'BY') selected @endif value="BY">Belarus</option>
                    <option @if(isset($customer) && $customer->country == 'BE') selected @endif value="BE">Belgium</option>
                    <option @if(isset($customer) && $customer->country == 'BZ') selected @endif value="BZ">Belize</option>
                    <option @if(isset($customer) && $customer->country == 'BJ') selected @endif value="BJ">Benin</option>
                    <option @if(isset($customer) && $customer->country == 'BM') selected @endif value="BM">Bermuda</option>
                    <option @if(isset($customer) && $customer->country == 'BT') selected @endif value="BT">Bhutan</option>
                    <option @if(isset($customer) && $customer->country == 'BO') selected @endif value="BO">Bolivia</option>
                    <option @if(isset($customer) && $customer->country == 'BQ') selected @endif value="BQ">Bonaire, Sint Eustatius and Saba</option>
                    <option @if(isset($customer) && $customer->country == 'BA') selected @endif value="BA">Bosnia and Herzegovina</option>
                    <option @if(isset($customer) && $customer->country == 'BW') selected @endif value="BW">Botswana</option>
                    <option @if(isset($customer) && $customer->country == 'BV') selected @endif value="BV">Bouvet Island</option>
                    <option @if(isset($customer) && $customer->country == 'BR') selected @endif  value="BR">Brazil</option>
                    <option @if(isset($customer) && $customer->country == 'IO') selected @endif value="IO">British Indian Ocean Territory</option>
                    <option @if(isset($customer) && $customer->country == 'BN') selected @endif value="BN">Brunei Darussalam</option>
                    <option @if(isset($customer) && $customer->country == 'BG') selected @endif value="BG">Bulgaria</option>
                    <option @if(isset($customer) && $customer->country == 'BF') selected @endif value="BF">Burkina Faso</option>
                    <option @if(isset($customer) && $customer->country == 'BI') selected @endif value="BI">Burundi</option>
                    <option @if(isset($customer) && $customer->country == 'KH') selected @endif value="KH">Cambodia</option>
                    <option @if(isset($customer) && $customer->country == 'CM') selected @endif value="CM">Cameroon</option>
                    <option @if(isset($customer) && $customer->country == 'CA') selected @endif value="CA">Canada</option>
                    <option @if(isset($customer) && $customer->country == 'CV') selected @endif value="CV">Cape Verde</option>
                    <option @if(isset($customer) && $customer->country == 'KY') selected @endif value="KY">Cayman Islands</option>
                    <option @if(isset($customer) && $customer->country == 'CF') selected @endif value="CF">Central African Republic</option>
                    <option @if(isset($customer) && $customer->country == 'TD') selected @endif value="TD">Chad</option>
                    <option @if(isset($customer) && $customer->country == 'CL') selected @endif value="CL">Chile</option>
                    <option @if(isset($customer) && $customer->country == 'CN') selected @endif value="CN">China</option>
                    <option @if(isset($customer) && $customer->country == 'CX') selected @endif value="CX">Christmas Island</option>
                    <option @if(isset($customer) && $customer->country == 'CC') selected @endif value="CC">Cocos (Keeling) Islands</option>
                    <option @if(isset($customer) && $customer->country == 'CO') selected @endif value="CO">Colombia</option>
                    <option @if(isset($customer) && $customer->country == 'KM') selected @endif value="KM">Comoros</option>
                    <option @if(isset($customer) && $customer->country == 'CG') selected @endif value="CG">Congo</option>
                    <option @if(isset($customer) && $customer->country == 'CD') selected @endif value="CD">Congo, Democratic Republic of the Congo</option>
                    <option @if(isset($customer) && $customer->country == 'CK') selected @endif value="CK">Cook Islands</option>
                    <option @if(isset($customer) && $customer->country == 'CR') selected @endif value="CR">Costa Rica</option>
                    <option @if(isset($customer) && $customer->country == 'CI') selected @endif value="CI">Cote D'Ivoire</option>
                    <option @if(isset($customer) && $customer->country == 'HR') selected @endif value="HR">Croatia</option>
                    <option @if(isset($customer) && $customer->country == 'CU') selected @endif value="CU">Cuba</option>
                    <option @if(isset($customer) && $customer->country == 'CW') selected @endif value="CW">Curacao</option>
                    <option @if(isset($customer) && $customer->country == 'CY') selected @endif value="CY">Cyprus</option>
                    <option @if(isset($customer) && $customer->country == 'CZ') selected @endif value="CZ">Czech Republic</option>
                    <option @if(isset($customer) && $customer->country == 'DK') selected @endif value="DK">Denmark</option>
                    <option @if(isset($customer) && $customer->country == 'DJ') selected @endif value="DJ">Djibouti</option>
                    <option @if(isset($customer) && $customer->country == 'DM') selected @endif value="DM">Dominica</option>
                    <option @if(isset($customer) && $customer->country == 'DO') selected @endif value="DO">Dominican Republic</option>
                    <option @if(isset($customer) && $customer->country == 'EC') selected @endif value="EC">Ecuador</option>
                    <option @if(isset($customer) && $customer->country == 'EG') selected @endif  value="EG">Egypt</option>
                    <option @if(isset($customer) && $customer->country == 'SV') selected @endif value="SV">El Salvador</option>
                    <option @if(isset($customer) && $customer->country == 'GQ') selected @endif value="GQ">Equatorial Guinea</option>
                    <option @if(isset($customer) && $customer->country == 'ER') selected @endif value="ER">Eritrea</option>
                    <option @if(isset($customer) && $customer->country == 'EE') selected @endif value="EE">Estonia</option>
                    <option @if(isset($customer) && $customer->country == 'ET') selected @endif value="ET">Ethiopia</option>
                    <option @if(isset($customer) && $customer->country == 'FK') selected @endif value="FK">Falkland Islands (Malvinas)</option>
                    <option @if(isset($customer) && $customer->country == 'FO') selected @endif value="FO">Faroe Islands</option>
                    <option @if(isset($customer) && $customer->country == 'FJ') selected @endif value="FJ">Fiji</option>
                    <option @if(isset($customer) && $customer->country == 'FI') selected @endif value="FI">Finland</option>
                    <option @if(isset($customer) && $customer->country == 'FR') selected @endif value="FR">France</option>
                    <option @if(isset($customer) && $customer->country == 'GF') selected @endif value="GF">French Guiana</option>
                    <option @if(isset($customer) && $customer->country == 'PF') selected @endif value="PF">French Polynesia</option>
                    <option @if(isset($customer) && $customer->country == 'TF') selected @endif value="TF">French Southern Territories</option>
                    <option @if(isset($customer) && $customer->country == 'GA') selected @endif value="GA">Gabon</option>
                    <option @if(isset($customer) && $customer->country == 'GM') selected @endif value="GM">Gambia</option>
                    <option @if(isset($customer) && $customer->country == 'GE') selected @endif value="GE">Georgia</option>
                    <option @if(isset($customer) && $customer->country == 'DE') selected @endif value="DE">Germany</option>
                    <option @if(isset($customer) && $customer->country == 'GH') selected @endif value="GH">Ghana</option>
                    <option @if(isset($customer) && $customer->country == 'GI') selected @endif value="GI">Gibraltar</option>
                    <option @if(isset($customer) && $customer->country == 'GR') selected @endif value="GR">Greece</option>
                    <option @if(isset($customer) && $customer->country == 'GL') selected @endif value="GL">Greenland</option>
                    <option @if(isset($customer) && $customer->country == 'GD') selected @endif value="GD">Grenada</option>
                    <option @if(isset($customer) && $customer->country == 'GP') selected @endif value="GP">Guadeloupe</option>
                    <option @if(isset($customer) && $customer->country == 'GU') selected @endif value="GU">Guam</option>
                    <option @if(isset($customer) && $customer->country == 'GT') selected @endif value="GT">Guatemala</option>
                    <option @if(isset($customer) && $customer->country == 'GG') selected @endif value="GG">Guernsey</option>
                    <option @if(isset($customer) && $customer->country == 'GN') selected @endif value="GN">Guinea</option>
                    <option @if(isset($customer) && $customer->country == 'GW') selected @endif value="GW">Guinea-Bissau</option>
                    <option @if(isset($customer) && $customer->country == 'GY') selected @endif value="GY">Guyana</option>
                    <option @if(isset($customer) && $customer->country == 'HT') selected @endif value="HT">Haiti</option>
                    <option @if(isset($customer) && $customer->country == 'HM') selected @endif value="HM">Heard Island and Mcdonald Islands</option>
                    <option @if(isset($customer) && $customer->country == 'VA') selected @endif value="VA">Holy See (Vatican City State)</option>
                    <option @if(isset($customer) && $customer->country == 'HN') selected @endif value="HN">Honduras</option>
                    <option @if(isset($customer) && $customer->country == 'HK') selected @endif value="HK">Hong Kong</option>
                    <option @if(isset($customer) && $customer->country == 'HU') selected @endif  value="HU">Hungary</option>
                    <option @if(isset($customer) && $customer->country == 'IS') selected @endif value="IS">Iceland</option>
                    <option @if(isset($customer) && $customer->country == 'IN') selected @endif value="IN">India</option>
                    <option @if(isset($customer) && $customer->country == 'ID') selected @endif value="ID">Indonesia</option>
                    <option @if(isset($customer) && $customer->country == 'IR') selected @endif value="IR">Iran, Islamic Republic of</option>
                    <option @if(isset($customer) && $customer->country == 'IQ') selected @endif value="IQ">Iraq</option>
                    <option @if(isset($customer) && $customer->country == 'IE') selected @endif value="IE">Ireland</option>
                    <option @if(isset($customer) && $customer->country == 'IM') selected @endif value="IM">Isle of Man</option>
                    <option @if(isset($customer) && $customer->country == 'IL') selected @endif value="IL">Israel</option>
                    <option @if(isset($customer) && $customer->country == 'IT') selected @endif value="IT">Italy</option>
                    <option @if(isset($customer) && $customer->country == 'JM') selected @endif value="JM">Jamaica</option>
                    <option @if(isset($customer) && $customer->country == 'JP') selected @endif value="JP">Japan</option>
                    <option @if(isset($customer) && $customer->country == 'JE') selected @endif value="JE">Jersey</option>
                    <option @if(isset($customer) && $customer->country == 'JO') selected @endif value="JO">Jordan</option>
                    <option @if(isset($customer) && $customer->country == 'KZ') selected @endif value="KZ">Kazakhstan</option>
                    <option @if(isset($customer) && $customer->country == 'KE') selected @endif value="KE">Kenya</option>
                    <option @if(isset($customer) && $customer->country == 'KI') selected @endif value="KI">Kiribati</option>
                    <option @if(isset($customer) && $customer->country == 'KP') selected @endif value="KP">Korea, Democratic People's Republic of</option>
                    <option @if(isset($customer) && $customer->country == 'KR') selected @endif value="KR">Korea, Republic of</option>
                    <option @if(isset($customer) && $customer->country == 'XK') selected @endif value="XK">Kosovo</option>
                    <option @if(isset($customer) && $customer->country == 'KW') selected @endif value="KW">Kuwait</option>
                    <option @if(isset($customer) && $customer->country == 'KG') selected @endif value="KG">Kyrgyzstan</option>
                    <option @if(isset($customer) && $customer->country == 'LA') selected @endif value="LA">Lao People's Democratic Republic</option>
                    <option @if(isset($customer) && $customer->country == 'LV') selected @endif value="LV">Latvia</option>
                    <option @if(isset($customer) && $customer->country == 'LB') selected @endif value="LB">Lebanon</option>
                    <option @if(isset($customer) && $customer->country == 'LS') selected @endif value="LS">Lesotho</option>
                    <option @if(isset($customer) && $customer->country == 'LR') selected @endif value="LR">Liberia</option>
                    <option @if(isset($customer) && $customer->country == 'LY') selected @endif value="LY">Libyan Arab Jamahiriya</option>
                    <option @if(isset($customer) && $customer->country == 'LI') selected @endif value="LI">Liechtenstein</option>
                    <option @if(isset($customer) && $customer->country == 'LT') selected @endif value="LT">Lithuania</option>
                    <option @if(isset($customer) && $customer->country == 'LU') selected @endif value="LU">Luxembourg</option>
                    <option @if(isset($customer) && $customer->country == 'MO') selected @endif value="MO">Macao</option>
                    <option @if(isset($customer) && $customer->country == 'MK') selected @endif value="MK">Macedonia, the Former Yugoslav Republic of</option>
                    <option @if(isset($customer) && $customer->country == 'MG') selected @endif value="MG">Madagascar</option>
                    <option @if(isset($customer) && $customer->country == 'MW') selected @endif value="MW">Malawi</option>
                    <option @if(isset($customer) && $customer->country == 'MY') selected @endif value="MY">Malaysia</option>
                    <option @if(isset($customer) && $customer->country == 'MV') selected @endif value="MV">Maldives</option>
                    <option @if(isset($customer) && $customer->country == 'ML') selected @endif value="ML">Mali</option>
                    <option @if(isset($customer) && $customer->country == 'MT') selected @endif value="MT">Malta</option>
                    <option @if(isset($customer) && $customer->country == 'MH') selected @endif value="MH">Marshall Islands</option>
                    <option @if(isset($customer) && $customer->country == 'MQ') selected @endif value="MQ">Martinique</option>
                    <option @if(isset($customer) && $customer->country == 'MR') selected @endif value="MR">Mauritania</option>
                    <option @if(isset($customer) && $customer->country == 'MU') selected @endif value="MU">Mauritius</option>
                    <option @if(isset($customer) && $customer->country == 'YT') selected @endif value="YT">Mayotte</option>
                    <option @if(isset($customer) && $customer->country == 'MX') selected @endif value="MX">Mexico</option>
                    <option @if(isset($customer) && $customer->country == 'FM') selected @endif value="FM">Micronesia, Federated States of</option>
                    <option @if(isset($customer) && $customer->country == 'MD') selected @endif value="MD">Moldova, Republic of</option>
                    <option @if(isset($customer) && $customer->country == 'MC') selected @endif value="MC">Monaco</option>
                    <option @if(isset($customer) && $customer->country == 'MN') selected @endif value="MN">Mongolia</option>
                    <option @if(isset($customer) && $customer->country == 'MR') selected @endif value="ME">Montenegro</option>
                    <option @if(isset($customer) && $customer->country == 'MS') selected @endif value="MS">Montserrat</option>
                    <option @if(isset($customer) && $customer->country == 'MA') selected @endif value="MA">Morocco</option>
                    <option @if(isset($customer) && $customer->country == 'MZ') selected @endif value="MZ">Mozambique</option>
                    <option @if(isset($customer) && $customer->country == 'MM') selected @endif value="MM">Myanmar</option>
                    <option @if(isset($customer) && $customer->country == 'NA') selected @endif value="NA">Namibia</option>
                    <option @if(isset($customer) && $customer->country == 'NR') selected @endif value="NR">Nauru</option>
                    <option @if(isset($customer) && $customer->country == 'NP') selected @endif value="NP">Nepal</option>
                    <option @if(isset($customer) && $customer->country == 'NL') selected @endif value="NL">Netherlands</option>
                    <option @if(isset($customer) && $customer->country == 'AN') selected @endif value="AN">Netherlands Antilles</option>
                    <option @if(isset($customer) && $customer->country == 'NC') selected @endif value="NC">New Caledonia</option>
                    <option @if(isset($customer) && $customer->country == 'NZ') selected @endif value="NZ">New Zealand</option>
                    <option @if(isset($customer) && $customer->country == 'NI') selected @endif value="NI">Nicaragua</option>
                    <option @if(isset($customer) && $customer->country == 'NE') selected @endif value="NE">Niger</option>
                    <option @if(isset($customer) && $customer->country == 'NG') selected @endif value="NG">Nigeria</option>
                    <option @if(isset($customer) && $customer->country == 'NU') selected @endif value="NU">Niue</option>
                    <option @if(isset($customer) && $customer->country == 'NF') selected @endif value="NF">Norfolk Island</option>
                    <option @if(isset($customer) && $customer->country == 'MP') selected @endif value="MP">Northern Mariana Islands</option>
                    <option @if(isset($customer) && $customer->country == 'NO') selected @endif value="NO">Norway</option>
                    <option @if(isset($customer) && $customer->country == 'OM') selected @endif value="OM">Oman</option>
                    <option @if(isset($customer) && $customer->country == 'PK') selected @endif  value="PK">Pakistan</option>
                    <option @if(isset($customer) && $customer->country == 'PW') selected @endif value="PW">Palau</option>
                    <option @if(isset($customer) && $customer->country == 'PS') selected @endif value="PS">Palestinian Territory, Occupied</option>
                    <option @if(isset($customer) && $customer->country == 'PA') selected @endif value="PA">Panama</option>
                    <option @if(isset($customer) && $customer->country == 'PG') selected @endif value="PG">Papua New Guinea</option>
                    <option @if(isset($customer) && $customer->country == 'PY') selected @endif  value="PY">Paraguay</option>
                    <option @if(isset($customer) && $customer->country == 'PE') selected @endif value="PE">Peru</option>
                    <option @if(isset($customer) && $customer->country == 'PH') selected @endif value="PH">Philippines</option>
                    <option @if(isset($customer) && $customer->country == 'PN') selected @endif value="PN">Pitcairn</option>
                    <option @if(isset($customer) && $customer->country == 'PL') selected @endif value="PL">Poland</option>
                    <option @if(isset($customer) && $customer->country == 'PT') selected @endif value="PT">Portugal</option>
                    <option @if(isset($customer) && $customer->country == 'PR') selected @endif value="PR">Puerto Rico</option>
                    <option @if(isset($customer) && $customer->country == 'QA') selected @endif value="QA">Qatar</option>
                    <option @if(isset($customer) && $customer->country == 'RE') selected @endif value="RE">Reunion</option>
                    <option @if(isset($customer) && $customer->country == 'RO') selected @endif value="RO">Romania</option>
                    <option @if(isset($customer) && $customer->country == 'RU') selected @endif value="RU">Russian Federation</option>
                    <option @if(isset($customer) && $customer->country == 'RW') selected @endif value="RW">Rwanda</option>
                    <option @if(isset($customer) && $customer->country == 'BL') selected @endif value="BL">Saint Barthelemy</option>
                    <option @if(isset($customer) && $customer->country == 'SH') selected @endif value="SH">Saint Helena</option>
                    <option @if(isset($customer) && $customer->country == 'KN') selected @endif value="KN">Saint Kitts and Nevis</option>
                    <option @if(isset($customer) && $customer->country == 'LC') selected @endif value="LC">Saint Lucia</option>
                    <option @if(isset($customer) && $customer->country == 'MF') selected @endif value="MF">Saint Martin</option>
                    <option @if(isset($customer) && $customer->country == 'PM') selected @endif value="PM">Saint Pierre and Miquelon</option>
                    <option @if(isset($customer) && $customer->country == 'VC') selected @endif value="VC">Saint Vincent and the Grenadines</option>
                    <option @if(isset($customer) && $customer->country == 'WS') selected @endif value="WS">Samoa</option>
                    <option @if(isset($customer) && $customer->country == 'SM') selected @endif value="SM">San Marino</option>
                    <option @if(isset($customer) && $customer->country == 'ST') selected @endif value="ST">Sao Tome and Principe</option>
                    <option @if(isset($customer) && $customer->country == 'SA') selected @endif value="SA">Saudi Arabia</option>
                    <option @if(isset($customer) && $customer->country == 'SN') selected @endif value="SN">Senegal</option>
                    <option @if(isset($customer) && $customer->country == 'RS') selected @endif value="RS">Serbia</option>
                    <option @if(isset($customer) && $customer->country == 'CS') selected @endif value="CS">Serbia and Montenegro</option>
                    <option @if(isset($customer) && $customer->country == 'SC') selected @endif value="SC">Seychelles</option>
                    <option @if(isset($customer) && $customer->country == 'SL') selected @endif value="SL">Sierra Leone</option>
                    <option @if(isset($customer) && $customer->country == 'SG') selected @endif value="SG">Singapore</option>
                    <option @if(isset($customer) && $customer->country == 'SX') selected @endif value="SX">Sint Maarten</option>
                    <option @if(isset($customer) && $customer->country == 'SK') selected @endif value="SK">Slovakia</option>
                    <option @if(isset($customer) && $customer->country == 'SI') selected @endif value="SI">Slovenia</option>
                    <option @if(isset($customer) && $customer->country == 'SB') selected @endif value="SB">Solomon Islands</option>
                    <option @if(isset($customer) && $customer->country == 'SO') selected @endif value="SO">Somalia</option>
                    <option @if(isset($customer) && $customer->country == 'ZA') selected @endif value="ZA">South Africa</option>
                    <option @if(isset($customer) && $customer->country == 'GS') selected @endif value="GS">South Georgia and the South Sandwich Islands</option>
                    <option @if(isset($customer) && $customer->country == 'SS') selected @endif value="SS">South Sudan</option>
                    <option @if(isset($customer) && $customer->country == 'ES') selected @endif value="ES">Spain</option>
                    <option @if(isset($customer) && $customer->country == 'LK') selected @endif value="LK">Sri Lanka</option>
                    <option @if(isset($customer) && $customer->country == 'SD') selected @endif value="SD">Sudan</option>
                    <option @if(isset($customer) && $customer->country == 'SR') selected @endif value="SR">Suriname</option>
                    <option @if(isset($customer) && $customer->country == 'SJ') selected @endif value="SJ">Svalbard and Jan Mayen</option>
                    <option @if(isset($customer) && $customer->country == 'SZ') selected @endif value="SZ">Swaziland</option>
                    <option @if(isset($customer) && $customer->country == 'SE') selected @endif value="SE">Sweden</option>
                    <option @if(isset($customer) && $customer->country == 'CH') selected @endif value="CH">Switzerland</option>
                    <option @if(isset($customer) && $customer->country == 'SY') selected @endif value="SY">Syrian Arab Republic</option>
                    <option @if(isset($customer) && $customer->country == 'TW') selected @endif value="TW">Taiwan, Province of China</option>
                    <option @if(isset($customer) && $customer->country == 'TJ') selected @endif value="TJ">Tajikistan</option>
                    <option @if(isset($customer) && $customer->country == 'TZ') selected @endif value="TZ">Tanzania, United Republic of</option>
                    <option @if(isset($customer) && $customer->country == 'TH') selected @endif value="TH">Thailand</option>
                    <option @if(isset($customer) && $customer->country == 'TL') selected @endif value="TL">Timor-Leste</option>
                    <option @if(isset($customer) && $customer->country == 'TG') selected @endif value="TG">Togo</option>
                    <option @if(isset($customer) && $customer->country == 'TK') selected @endif value="TK">Tokelau</option>
                    <option @if(isset($customer) && $customer->country == 'TO') selected @endif  value="TO">Tonga</option>
                    <option @if(isset($customer) && $customer->country == 'TT') selected @endif value="TT">Trinidad and Tobago</option>
                    <option @if(isset($customer) && $customer->country == 'TN') selected @endif value="TN">Tunisia</option>
                    <option @if(isset($customer) && $customer->country == 'TR') selected @endif  value="TR">Turkey</option>
                    <option @if(isset($customer) && $customer->country == 'TM') selected @endif value="TM">Turkmenistan</option>
                    <option @if(isset($customer) && $customer->country == 'TC') selected @endif value="TC">Turks and Caicos Islands</option>
                    <option @if(isset($customer) && $customer->country == 'TV') selected @endif value="TV">Tuvalu</option>
                    <option @if(isset($customer) && $customer->country == 'UG') selected @endif value="UG">Uganda</option>
                    <option @if(isset($customer) && $customer->country == 'UA') selected @endif value="UA">Ukraine</option>
                    <option @if(isset($customer) && $customer->country == 'AE') selected @endif value="AE">United Arab Emirates</option>
                    <option @if(isset($customer) && $customer->country == 'GB') selected @endif value="GB">United Kingdom</option>
                    <option @if(isset($customer) && $customer->country == 'US') selected @endif value="US">United States</option>
                    <option @if(isset($customer) && $customer->country == 'UM') selected @endif value="UM">United States Minor Outlying Islands</option>
                    <option @if(isset($customer) && $customer->country == 'UY') selected @endif value="UY">Uruguay</option>
                    <option @if(isset($customer) && $customer->country == 'UZ') selected @endif value="UZ">Uzbekistan</option>
                    <option @if(isset($customer) && $customer->country == 'VU') selected @endif value="VU">Vanuatu</option>
                    <option @if(isset($customer) && $customer->country == 'VE') selected @endif value="VE">Venezuela</option>
                    <option @if(isset($customer) && $customer->country == 'VN') selected @endif value="VN">Viet Nam</option>
                    <option @if(isset($customer) && $customer->country == 'VG') selected @endif value="VG">Virgin Islands, British</option>
                    <option @if(isset($customer) && $customer->country == 'VI') selected @endif value="VI">Virgin Islands, U.s.</option>
                    <option @if(isset($customer) && $customer->country == 'WF') selected @endif value="WF">Wallis and Futuna</option>
                    <option @if(isset($customer) && $customer->country == 'EH') selected @endif value="EH">Western Sahara</option>
                    <option @if(isset($customer) && $customer->country == 'YE') selected @endif value="YE">Yemen</option>
                    <option @if(isset($customer) && $customer->country == 'ZM') selected @endif value="ZM">Zambia</option>
                    <option @if(isset($customer) && $customer->country == 'ZW') selected @endif value="ZW">Zimbabwe</option>
                  </select>
                </div>
                @error('country')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Status</label>
                  <select class="full-width" data-init-plugin="select2" name="status">
                    <option @if(isset($customer) && $customer->status == 'company') selected @endif  value="company">Company</option>
                    <option @if(isset($customer) && $customer->status == 'private') selected @endif  value="private">Private</option>
                    <option @if(isset($customer) && $customer->status == 'entrepreneur_microentreprise') selected @endif  value="entrepreneur_microentreprise">Auto Entrepreneur / Microentreprise</option>
                  </select>
                </div>
                @error('status')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Company Name</label>
                  <input value="@if(isset($customer)){{ $customer->company_name }}@else{{old('company_name') }}@endif"  name="company_name" type="text" class="form-control" required>
                </div>
                @error('company_name')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Company ID</label>
                  <input value="@if(isset($customer)){{ $customer->company_id }}@else{{old('company_id') }}@endif"  name="company_id" type="text" class="form-control" required>
                </div>
                @error('company_id')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror

                <div class="form-group form-group-default">
                  <label>Elorus Account ID</label>
                  <input value="@if(isset($customer)) @if($customer->elorus_id != NULL){{$customer->elorus_id}}@endif @else{{old('elorus_id')}}@endif" name="elorus_id" type="text" class="form-control" autocomplete="off">
                </div>
                @error('elorus_id')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror

                <div class="form-group form-group-default">
                  <label>EVC Customer ID</label>
                  <input value="@if(isset($customer)){{ $customer->evc_customer_id }}@else{{old('evc_customer_id')}}@endif"  name="evc_customer_id" type="text" class="form-control">
                </div>
                @error('evc_customer_id')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror

                <div class="form-group form-group-default">
                  <label>Magic Serial Number</label>
                  <input value="@if(isset($customer)){{ $customer->sn }}@else{{old('sn')}}@endif"  name="sn" type="text" class="form-control">
                </div>
                @error('sn')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror

                <div class="form-group form-group-default required ">
                  <label>Customer Group</label>
                  <select class="full-width" data-init-plugin="select2" name="group_id">
                    @foreach($groups as $group)
                      <option @if(isset($customer) && $customer->group_id == $group->id) selected @endif value="{{$group->id}}">{{$group->name}}</option>
                    @endforeach
                  </select>
                </div>
              
              @error('group_id')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
              
              <div class="form-group form-group-default required ">
                <label>Frontend</label>
                <select class="full-width" data-init-plugin="select2" name="front_end_id">
                  @foreach($frontends as $frontend)
                    <option @if(isset($customer) && $customer->frontend->id == $frontend->id) selected @endif value="{{$frontend->id}}">{{$frontend->name}}</option>
                  @endforeach
                </select>
              </div>
            
            @error('front_end_id')
              <span class="text-danger" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror

            <div class="checkbox check-success m-t-20">
              <input type="checkbox" name="exclude_vat_check" @if(isset($customer) && $customer->exclude_vat_check) checked="checked" @endif id="checkbox2">
              <label for="checkbox2">Exclude VAT Checks</label>
            </div>
            
                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($customer)) Update @else Add @endif</span></button>
                  @if(isset($customer))
                    @if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'delete-customers'))
                      <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$customer->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
                    @endif
                  @endif
                </div>
              </form>

              @if(isset($customer))


              <h5 class="m-t-50">
                Update Customer Tools
              </h5>

              <form method="POST" action="{{ route('update-tools') }}">

                @csrf
                <input type="hidden" name="user_id" value="{{$customer->id}}">

                <div class="form-group form-group-default form-group-default-select2">
                  <label>Master Tools</label>
                  <select name="master_tools[]" class=" full-width" data-init-plugin="select2" multiple>
                    @foreach($allMasterTools as $mtool)
                      <option value="{{ $mtool->id }}" @if( in_array($mtool->id, $masterTools)) selected @endif>{{$mtool->name}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group form-group-default form-group-default-select2">
                  <label>Slave Tools</label>
                  <select name="slave_tools[]" class=" full-width" data-init-plugin="select2" multiple>
                    @foreach($allSlaveTools as $stool)
                      <option value="{{ $stool->id }}" @if( in_array($stool->id, $slaveTools)) selected @endif>{{$stool->name}}</option>
                    @endforeach
                  </select>
                </div>
                
                <div class="row">
                  <div class="col-md-4 m-t-10 sm-m-t-10">
                    <button type="submit" class="btn btn-primary btn-block m-t-5">Save Changes</button>
                  </div>
                </div>
                
              </form>


              <h5 class="m-t-50">
                Customer Test Status
              </h5>

              <form action="{{route('update-test-status')}}" method="POST" role="form">
                
                @csrf

                <input name="customer_id" value="{{$customer->id}}" type="hidden">

                <div class="">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="checkbox check-success">
                          <input name="status" type="checkbox" @if($customer->test == 1) @checked(true) @endif id="checkbox1">
                          <label for="checkbox1">Test Status</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-8">
                      <div class="form-group form-group-default">
                        <label>Pass Code</label>
                        <input type="password" class="form-control" name="passkey">
                      </div>
                    </div>
                    
                  </div>
                </div>
              
              <div class="row">
                
                <div class="col-md-4 m-t-10 sm-m-t-10">
                  <button type="submit" class="btn btn-primary btn-block m-t-5">Set Status</button>
                </div>
              </div>
    
            </form>

            @endif
    
                
            </div>
          </div>
        </div>
    </div>
</div>

<div class="modal fade stick-up disable-scroll" id="testModal" tabindex="-1" role="dialog" aria-hidden="false">
  <div class="modal-dialog ">
    <div class="modal-content-wrapper">
      <div class="modal-content">
        <div class="modal-header clearfix text-left">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
          </button>
          <h5>Set Test <span class="semi-bold">Status</span></h5>
          <p class="p-b-10">We need set the test status for user.</p>
        </div>
        <div class="modal-body">
          
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>
<!-- /.modal-dialog -->
<!-- MODAL SLIDE UP SMALL  -->
<!-- Modal -->

@endsection

@section('pagespecificscripts')

<script type="text/javascript">

      $( document ).ready(function(event) {

        // $(document).on('click', '#testButton', function(e){

        //   e.preventDefault();
        //   $('#testModal').modal('show');

        //   });
        
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
                        url: "/delete_customer",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Customer has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/customers';
                        }
                    });            
                }
            });
        });
    });
  
</script>

@endsection