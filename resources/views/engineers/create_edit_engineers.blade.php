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
                  @if(isset($engineer))
                  <h5>
                    Edit Engineer
                  </h5>
                @else
                  <h5>
                    Add Engineer
                  </h5>
                @endif
                </div>
                <div class="pull-right">
                <div class="col-xs-12">
                    <button data-redirect="{{route('engineers')}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Engineers</span>
                    </button>
                    <button data-redirect="{{route('engineers-permissions', $engineer->id)}}" class="btn btn-success btn-cons m-b-10 redirect-click" type="button"><i class="pg-plus_circle"></i> <span class="bold">Engineer's Permissions</span>
                    </button>
                    {{-- <input type="text" id="search-table" class="form-control pull-right" placeholder="Search"> --}}
                </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
              <form class="" role="form" method="POST" action="@if(isset($engineer)){{route('update-engineer')}}@else{{ route('add-engineer') }}@endif" enctype="multipart/form-data">
                @csrf
                @if(isset($engineer))
                  <input name="id" type="hidden" value="{{ $engineer->id }}">
                @endif
                <div class="form-group form-group-default required ">
                  <label>Name</label>
                  <input value="@if(isset($engineer)) {{ $engineer->name }} @else{{old('name') }}@endif"  name="name" type="text" class="form-control" required>
                </div>
                @error('name')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Email</label>
                  <input value="@if(isset($engineer)) {{ $engineer->email }} @else{{old('email') }}@endif"  name="email" type="text" class="form-control" required>
                </div>
                @error('email')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                @if(isset($engineer))
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
                  <input value="@if(isset($engineer)) {{ $engineer->phone }} @else{{old('phone') }}@endif"  name="phone" type="text" class="form-control" required>
                </div>
                @error('phone')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Address</label>
                  <input value="@if(isset($engineer)) {{ $engineer->address }} @else{{old('address') }}@endif"  name="address" type="text" class="form-control" required>
                </div>
                @error('address')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Zip</label>
                  <input value="@if(isset($engineer)) {{ $engineer->zip }} @else{{old('zip') }}@endif"  name="zip" type="text" class="form-control" required>
                </div>
                @error('zip')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>City</label>
                  <input value="@if(isset($engineer)) {{ $engineer->city }} @else{{old('city') }}@endif"  name="city" type="text" class="form-control" required>
                </div>
                @error('city')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="form-group form-group-default required ">
                  <label>Country</label>
                  <select name="country" id="country" class="full-width country" data-init-plugin="select2">
  
                    <option @if(isset($engineer) && $engineer->country == 'AF') selected @endif value="AF">Afghanistan</option>
                    <option @if(isset($engineer) && $engineer->country == 'AX') selected @endif value="AX">Aland Islands</option>
                    <option @if(isset($engineer) && $engineer->country == 'AL') selected @endif value="AL">Albania</option>
                    <option @if(isset($engineer) && $engineer->country == 'DZ') selected @endif value="DZ">Algeria</option>
                    <option @if(isset($engineer) && $engineer->country == 'AS') selected @endif value="AS">American Samoa</option>
                    <option @if(isset($engineer) && $engineer->country == 'AD') selected @endif value="AD">Andorra</option>
                    <option @if(isset($engineer) && $engineer->country == 'AO') selected @endif value="AO">Angola</option>
                    <option @if(isset($engineer) && $engineer->country == 'AI') selected @endif value="AI">Anguilla</option>
                    <option @if(isset($engineer) && $engineer->country == 'AQ') selected @endif value="AQ">Antarctica</option>
                    <option @if(isset($engineer) && $engineer->country == 'AG') selected @endif value="AG">Antigua and Barbuda</option>
                    <option @if(isset($engineer) && $engineer->country == 'AR') selected @endif value="AR">Argentina</option>
                    <option @if(isset($engineer) && $engineer->country == 'AM') selected @endif value="AM">Armenia</option>
                    <option @if(isset($engineer) && $engineer->country == 'AW') selected @endif value="AW">Aruba</option>
                    <option @if(isset($engineer) && $engineer->country == 'AU') selected @endif value="AU">Australia</option>
                    <option @if(isset($engineer) && $engineer->country == 'AT') selected @endif value="AT">Austria</option>
                    <option @if(isset($engineer) && $engineer->country == 'AZ') selected @endif value="AZ">Azerbaijan</option>
                    <option @if(isset($engineer) && $engineer->country == 'BS') selected @endif value="BS">Bahamas</option>
                    <option @if(isset($engineer) && $engineer->country == 'BH') selected @endif value="BH">Bahrain</option>
                    <option @if(isset($engineer) && $engineer->country == 'BD') selected @endif value="BD">Bangladesh</option>
                    <option @if(isset($engineer) && $engineer->country == 'BB') selected @endif value="BB">Barbados</option>
                    <option @if(isset($engineer) && $engineer->country == 'BY') selected @endif value="BY">Belarus</option>
                    <option @if(isset($engineer) && $engineer->country == 'BE') selected @endif value="BE">Belgium</option>
                    <option @if(isset($engineer) && $engineer->country == 'BZ') selected @endif value="BZ">Belize</option>
                    <option @if(isset($engineer) && $engineer->country == 'BJ') selected @endif value="BJ">Benin</option>
                    <option @if(isset($engineer) && $engineer->country == 'BM') selected @endif value="BM">Bermuda</option>
                    <option @if(isset($engineer) && $engineer->country == 'BT') selected @endif value="BT">Bhutan</option>
                    <option @if(isset($engineer) && $engineer->country == 'BO') selected @endif value="BO">Bolivia</option>
                    <option @if(isset($engineer) && $engineer->country == 'BQ') selected @endif value="BQ">Bonaire, Sint Eustatius and Saba</option>
                    <option @if(isset($engineer) && $engineer->country == 'BA') selected @endif value="BA">Bosnia and Herzegovina</option>
                    <option @if(isset($engineer) && $engineer->country == 'BW') selected @endif value="BW">Botswana</option>
                    <option @if(isset($engineer) && $engineer->country == 'BV') selected @endif value="BV">Bouvet Island</option>
                    <option @if(isset($engineer) && $engineer->country == 'BR') selected @endif  value="BR">Brazil</option>
                    <option @if(isset($engineer) && $engineer->country == 'IO') selected @endif value="IO">British Indian Ocean Territory</option>
                    <option @if(isset($engineer) && $engineer->country == 'BN') selected @endif value="BN">Brunei Darussalam</option>
                    <option @if(isset($engineer) && $engineer->country == 'BG') selected @endif value="BG">Bulgaria</option>
                    <option @if(isset($engineer) && $engineer->country == 'BF') selected @endif value="BF">Burkina Faso</option>
                    <option @if(isset($engineer) && $engineer->country == 'BI') selected @endif value="BI">Burundi</option>
                    <option @if(isset($engineer) && $engineer->country == 'KH') selected @endif value="KH">Cambodia</option>
                    <option @if(isset($engineer) && $engineer->country == 'CM') selected @endif value="CM">Cameroon</option>
                    <option @if(isset($engineer) && $engineer->country == 'CA') selected @endif value="CA">Canada</option>
                    <option @if(isset($engineer) && $engineer->country == 'CV') selected @endif value="CV">Cape Verde</option>
                    <option @if(isset($engineer) && $engineer->country == 'KY') selected @endif value="KY">Cayman Islands</option>
                    <option @if(isset($engineer) && $engineer->country == 'CF') selected @endif value="CF">Central African Republic</option>
                    <option @if(isset($engineer) && $engineer->country == 'TD') selected @endif value="TD">Chad</option>
                    <option @if(isset($engineer) && $engineer->country == 'CL') selected @endif value="CL">Chile</option>
                    <option @if(isset($engineer) && $engineer->country == 'CN') selected @endif value="CN">China</option>
                    <option @if(isset($engineer) && $engineer->country == 'CX') selected @endif value="CX">Christmas Island</option>
                    <option @if(isset($engineer) && $engineer->country == 'CC') selected @endif value="CC">Cocos (Keeling) Islands</option>
                    <option @if(isset($engineer) && $engineer->country == 'CO') selected @endif value="CO">Colombia</option>
                    <option @if(isset($engineer) && $engineer->country == 'KM') selected @endif value="KM">Comoros</option>
                    <option @if(isset($engineer) && $engineer->country == 'CG') selected @endif value="CG">Congo</option>
                    <option @if(isset($engineer) && $engineer->country == 'CD') selected @endif value="CD">Congo, Democratic Republic of the Congo</option>
                    <option @if(isset($engineer) && $engineer->country == 'CK') selected @endif value="CK">Cook Islands</option>
                    <option @if(isset($engineer) && $engineer->country == 'CR') selected @endif value="CR">Costa Rica</option>
                    <option @if(isset($engineer) && $engineer->country == 'CI') selected @endif value="CI">Cote D'Ivoire</option>
                    <option @if(isset($engineer) && $engineer->country == 'HR') selected @endif value="HR">Croatia</option>
                    <option @if(isset($engineer) && $engineer->country == 'CU') selected @endif value="CU">Cuba</option>
                    <option @if(isset($engineer) && $engineer->country == 'CW') selected @endif value="CW">Curacao</option>
                    <option @if(isset($engineer) && $engineer->country == 'CY') selected @endif value="CY">Cyprus</option>
                    <option @if(isset($engineer) && $engineer->country == 'CZ') selected @endif value="CZ">Czech Republic</option>
                    <option @if(isset($engineer) && $engineer->country == 'DK') selected @endif value="DK">Denmark</option>
                    <option @if(isset($engineer) && $engineer->country == 'DJ') selected @endif value="DJ">Djibouti</option>
                    <option @if(isset($engineer) && $engineer->country == 'DM') selected @endif value="DM">Dominica</option>
                    <option @if(isset($engineer) && $engineer->country == 'DO') selected @endif value="DO">Dominican Republic</option>
                    <option @if(isset($engineer) && $engineer->country == 'EC') selected @endif value="EC">Ecuador</option>
                    <option @if(isset($engineer) && $engineer->country == 'EG') selected @endif  value="EG">Egypt</option>
                    <option @if(isset($engineer) && $engineer->country == 'SV') selected @endif value="SV">El Salvador</option>
                    <option @if(isset($engineer) && $engineer->country == 'GQ') selected @endif value="GQ">Equatorial Guinea</option>
                    <option @if(isset($engineer) && $engineer->country == 'ER') selected @endif value="ER">Eritrea</option>
                    <option @if(isset($engineer) && $engineer->country == 'EE') selected @endif value="EE">Estonia</option>
                    <option @if(isset($engineer) && $engineer->country == 'ET') selected @endif value="ET">Ethiopia</option>
                    <option @if(isset($engineer) && $engineer->country == 'FK') selected @endif value="FK">Falkland Islands (Malvinas)</option>
                    <option @if(isset($engineer) && $engineer->country == 'FO') selected @endif value="FO">Faroe Islands</option>
                    <option @if(isset($engineer) && $engineer->country == 'FJ') selected @endif value="FJ">Fiji</option>
                    <option @if(isset($engineer) && $engineer->country == 'FI') selected @endif value="FI">Finland</option>
                    <option @if(isset($engineer) && $engineer->country == 'FR') selected @endif value="FR">France</option>
                    <option @if(isset($engineer) && $engineer->country == 'GF') selected @endif value="GF">French Guiana</option>
                    <option @if(isset($engineer) && $engineer->country == 'PF') selected @endif value="PF">French Polynesia</option>
                    <option @if(isset($engineer) && $engineer->country == 'TF') selected @endif value="TF">French Southern Territories</option>
                    <option @if(isset($engineer) && $engineer->country == 'GA') selected @endif value="GA">Gabon</option>
                    <option @if(isset($engineer) && $engineer->country == 'GM') selected @endif value="GM">Gambia</option>
                    <option @if(isset($engineer) && $engineer->country == 'GE') selected @endif value="GE">Georgia</option>
                    <option @if(isset($engineer) && $engineer->country == 'DE') selected @endif value="DE">Germany</option>
                    <option @if(isset($engineer) && $engineer->country == 'GH') selected @endif value="GH">Ghana</option>
                    <option @if(isset($engineer) && $engineer->country == 'GI') selected @endif value="GI">Gibraltar</option>
                    <option @if(isset($engineer) && $engineer->country == 'GR') selected @endif value="GR">Greece</option>
                    <option @if(isset($engineer) && $engineer->country == 'GL') selected @endif value="GL">Greenland</option>
                    <option @if(isset($engineer) && $engineer->country == 'GD') selected @endif value="GD">Grenada</option>
                    <option @if(isset($engineer) && $engineer->country == 'GP') selected @endif value="GP">Guadeloupe</option>
                    <option @if(isset($engineer) && $engineer->country == 'GU') selected @endif value="GU">Guam</option>
                    <option @if(isset($engineer) && $engineer->country == 'GT') selected @endif value="GT">Guatemala</option>
                    <option @if(isset($engineer) && $engineer->country == 'GG') selected @endif value="GG">Guernsey</option>
                    <option @if(isset($engineer) && $engineer->country == 'GN') selected @endif value="GN">Guinea</option>
                    <option @if(isset($engineer) && $engineer->country == 'GW') selected @endif value="GW">Guinea-Bissau</option>
                    <option @if(isset($engineer) && $engineer->country == 'GY') selected @endif value="GY">Guyana</option>
                    <option @if(isset($engineer) && $engineer->country == 'HT') selected @endif value="HT">Haiti</option>
                    <option @if(isset($engineer) && $engineer->country == 'HM') selected @endif value="HM">Heard Island and Mcdonald Islands</option>
                    <option @if(isset($engineer) && $engineer->country == 'VA') selected @endif value="VA">Holy See (Vatican City State)</option>
                    <option @if(isset($engineer) && $engineer->country == 'HN') selected @endif value="HN">Honduras</option>
                    <option @if(isset($engineer) && $engineer->country == 'HK') selected @endif value="HK">Hong Kong</option>
                    <option @if(isset($engineer) && $engineer->country == 'HU') selected @endif  value="HU">Hungary</option>
                    <option @if(isset($engineer) && $engineer->country == 'IS') selected @endif value="IS">Iceland</option>
                    <option @if(isset($engineer) && $engineer->country == 'IN') selected @endif value="IN">India</option>
                    <option @if(isset($engineer) && $engineer->country == 'ID') selected @endif value="ID">Indonesia</option>
                    <option @if(isset($engineer) && $engineer->country == 'IR') selected @endif value="IR">Iran, Islamic Republic of</option>
                    <option @if(isset($engineer) && $engineer->country == 'IQ') selected @endif value="IQ">Iraq</option>
                    <option @if(isset($engineer) && $engineer->country == 'IE') selected @endif value="IE">Ireland</option>
                    <option @if(isset($engineer) && $engineer->country == 'IM') selected @endif value="IM">Isle of Man</option>
                    <option @if(isset($engineer) && $engineer->country == 'IL') selected @endif value="IL">Israel</option>
                    <option @if(isset($engineer) && $engineer->country == 'IT') selected @endif value="IT">Italy</option>
                    <option @if(isset($engineer) && $engineer->country == 'JM') selected @endif value="JM">Jamaica</option>
                    <option @if(isset($engineer) && $engineer->country == 'JP') selected @endif value="JP">Japan</option>
                    <option @if(isset($engineer) && $engineer->country == 'JE') selected @endif value="JE">Jersey</option>
                    <option @if(isset($engineer) && $engineer->country == 'JO') selected @endif value="JO">Jordan</option>
                    <option @if(isset($engineer) && $engineer->country == 'KZ') selected @endif value="KZ">Kazakhstan</option>
                    <option @if(isset($engineer) && $engineer->country == 'KE') selected @endif value="KE">Kenya</option>
                    <option @if(isset($engineer) && $engineer->country == 'KI') selected @endif value="KI">Kiribati</option>
                    <option @if(isset($engineer) && $engineer->country == 'KP') selected @endif value="KP">Korea, Democratic People's Republic of</option>
                    <option @if(isset($engineer) && $engineer->country == 'KR') selected @endif value="KR">Korea, Republic of</option>
                    <option @if(isset($engineer) && $engineer->country == 'XK') selected @endif value="XK">Kosovo</option>
                    <option @if(isset($engineer) && $engineer->country == 'KW') selected @endif value="KW">Kuwait</option>
                    <option @if(isset($engineer) && $engineer->country == 'KG') selected @endif value="KG">Kyrgyzstan</option>
                    <option @if(isset($engineer) && $engineer->country == 'LA') selected @endif value="LA">Lao People's Democratic Republic</option>
                    <option @if(isset($engineer) && $engineer->country == 'LV') selected @endif value="LV">Latvia</option>
                    <option @if(isset($engineer) && $engineer->country == 'LB') selected @endif value="LB">Lebanon</option>
                    <option @if(isset($engineer) && $engineer->country == 'LS') selected @endif value="LS">Lesotho</option>
                    <option @if(isset($engineer) && $engineer->country == 'LR') selected @endif value="LR">Liberia</option>
                    <option @if(isset($engineer) && $engineer->country == 'LY') selected @endif value="LY">Libyan Arab Jamahiriya</option>
                    <option @if(isset($engineer) && $engineer->country == 'LI') selected @endif value="LI">Liechtenstein</option>
                    <option @if(isset($engineer) && $engineer->country == 'LT') selected @endif value="LT">Lithuania</option>
                    <option @if(isset($engineer) && $engineer->country == 'LU') selected @endif value="LU">Luxembourg</option>
                    <option @if(isset($engineer) && $engineer->country == 'MO') selected @endif value="MO">Macao</option>
                    <option @if(isset($engineer) && $engineer->country == 'MK') selected @endif value="MK">Macedonia, the Former Yugoslav Republic of</option>
                    <option @if(isset($engineer) && $engineer->country == 'MG') selected @endif value="MG">Madagascar</option>
                    <option @if(isset($engineer) && $engineer->country == 'MW') selected @endif value="MW">Malawi</option>
                    <option @if(isset($engineer) && $engineer->country == 'MY') selected @endif value="MY">Malaysia</option>
                    <option @if(isset($engineer) && $engineer->country == 'MV') selected @endif value="MV">Maldives</option>
                    <option @if(isset($engineer) && $engineer->country == 'ML') selected @endif value="ML">Mali</option>
                    <option @if(isset($engineer) && $engineer->country == 'MT') selected @endif value="MT">Malta</option>
                    <option @if(isset($engineer) && $engineer->country == 'MH') selected @endif value="MH">Marshall Islands</option>
                    <option @if(isset($engineer) && $engineer->country == 'MQ') selected @endif value="MQ">Martinique</option>
                    <option @if(isset($engineer) && $engineer->country == 'MR') selected @endif value="MR">Mauritania</option>
                    <option @if(isset($engineer) && $engineer->country == 'MU') selected @endif value="MU">Mauritius</option>
                    <option @if(isset($engineer) && $engineer->country == 'YT') selected @endif value="YT">Mayotte</option>
                    <option @if(isset($engineer) && $engineer->country == 'MX') selected @endif value="MX">Mexico</option>
                    <option @if(isset($engineer) && $engineer->country == 'FM') selected @endif value="FM">Micronesia, Federated States of</option>
                    <option @if(isset($engineer) && $engineer->country == 'MD') selected @endif value="MD">Moldova, Republic of</option>
                    <option @if(isset($engineer) && $engineer->country == 'MC') selected @endif value="MC">Monaco</option>
                    <option @if(isset($engineer) && $engineer->country == 'MN') selected @endif value="MN">Mongolia</option>
                    <option @if(isset($engineer) && $engineer->country == 'MR') selected @endif value="ME">Montenegro</option>
                    <option @if(isset($engineer) && $engineer->country == 'MS') selected @endif value="MS">Montserrat</option>
                    <option @if(isset($engineer) && $engineer->country == 'MA') selected @endif value="MA">Morocco</option>
                    <option @if(isset($engineer) && $engineer->country == 'MZ') selected @endif value="MZ">Mozambique</option>
                    <option @if(isset($engineer) && $engineer->country == 'MM') selected @endif value="MM">Myanmar</option>
                    <option @if(isset($engineer) && $engineer->country == 'NA') selected @endif value="NA">Namibia</option>
                    <option @if(isset($engineer) && $engineer->country == 'NR') selected @endif value="NR">Nauru</option>
                    <option @if(isset($engineer) && $engineer->country == 'NP') selected @endif value="NP">Nepal</option>
                    <option @if(isset($engineer) && $engineer->country == 'NL') selected @endif value="NL">Netherlands</option>
                    <option @if(isset($engineer) && $engineer->country == 'AN') selected @endif value="AN">Netherlands Antilles</option>
                    <option @if(isset($engineer) && $engineer->country == 'NC') selected @endif value="NC">New Caledonia</option>
                    <option @if(isset($engineer) && $engineer->country == 'NZ') selected @endif value="NZ">New Zealand</option>
                    <option @if(isset($engineer) && $engineer->country == 'NI') selected @endif value="NI">Nicaragua</option>
                    <option @if(isset($engineer) && $engineer->country == 'NE') selected @endif value="NE">Niger</option>
                    <option @if(isset($engineer) && $engineer->country == 'NG') selected @endif value="NG">Nigeria</option>
                    <option @if(isset($engineer) && $engineer->country == 'NU') selected @endif value="NU">Niue</option>
                    <option @if(isset($engineer) && $engineer->country == 'NF') selected @endif value="NF">Norfolk Island</option>
                    <option @if(isset($engineer) && $engineer->country == 'MP') selected @endif value="MP">Northern Mariana Islands</option>
                    <option @if(isset($engineer) && $engineer->country == 'NO') selected @endif value="NO">Norway</option>
                    <option @if(isset($engineer) && $engineer->country == 'OM') selected @endif value="OM">Oman</option>
                    <option @if(isset($engineer) && $engineer->country == 'PK') selected @endif  value="PK">Pakistan</option>
                    <option @if(isset($engineer) && $engineer->country == 'PW') selected @endif value="PW">Palau</option>
                    <option @if(isset($engineer) && $engineer->country == 'PS') selected @endif value="PS">Palestinian Territory, Occupied</option>
                    <option @if(isset($engineer) && $engineer->country == 'PA') selected @endif value="PA">Panama</option>
                    <option @if(isset($engineer) && $engineer->country == 'PG') selected @endif value="PG">Papua New Guinea</option>
                    <option @if(isset($engineer) && $engineer->country == 'PY') selected @endif  value="PY">Paraguay</option>
                    <option @if(isset($engineer) && $engineer->country == 'PE') selected @endif value="PE">Peru</option>
                    <option @if(isset($engineer) && $engineer->country == 'PH') selected @endif value="PH">Philippines</option>
                    <option @if(isset($engineer) && $engineer->country == 'PN') selected @endif value="PN">Pitcairn</option>
                    <option @if(isset($engineer) && $engineer->country == 'PL') selected @endif value="PL">Poland</option>
                    <option @if(isset($engineer) && $engineer->country == 'PT') selected @endif value="PT">Portugal</option>
                    <option @if(isset($engineer) && $engineer->country == 'PR') selected @endif value="PR">Puerto Rico</option>
                    <option @if(isset($engineer) && $engineer->country == 'QA') selected @endif value="QA">Qatar</option>
                    <option @if(isset($engineer) && $engineer->country == 'RE') selected @endif value="RE">Reunion</option>
                    <option @if(isset($engineer) && $engineer->country == 'RO') selected @endif value="RO">Romania</option>
                    <option @if(isset($engineer) && $engineer->country == 'RU') selected @endif value="RU">Russian Federation</option>
                    <option @if(isset($engineer) && $engineer->country == 'RW') selected @endif value="RW">Rwanda</option>
                    <option @if(isset($engineer) && $engineer->country == 'BL') selected @endif value="BL">Saint Barthelemy</option>
                    <option @if(isset($engineer) && $engineer->country == 'SH') selected @endif value="SH">Saint Helena</option>
                    <option @if(isset($engineer) && $engineer->country == 'KN') selected @endif value="KN">Saint Kitts and Nevis</option>
                    <option @if(isset($engineer) && $engineer->country == 'LC') selected @endif value="LC">Saint Lucia</option>
                    <option @if(isset($engineer) && $engineer->country == 'MF') selected @endif value="MF">Saint Martin</option>
                    <option @if(isset($engineer) && $engineer->country == 'PM') selected @endif value="PM">Saint Pierre and Miquelon</option>
                    <option @if(isset($engineer) && $engineer->country == 'VC') selected @endif value="VC">Saint Vincent and the Grenadines</option>
                    <option @if(isset($engineer) && $engineer->country == 'WS') selected @endif value="WS">Samoa</option>
                    <option @if(isset($engineer) && $engineer->country == 'SM') selected @endif value="SM">San Marino</option>
                    <option @if(isset($engineer) && $engineer->country == 'ST') selected @endif value="ST">Sao Tome and Principe</option>
                    <option @if(isset($engineer) && $engineer->country == 'SA') selected @endif value="SA">Saudi Arabia</option>
                    <option @if(isset($engineer) && $engineer->country == 'SN') selected @endif value="SN">Senegal</option>
                    <option @if(isset($engineer) && $engineer->country == 'RS') selected @endif value="RS">Serbia</option>
                    <option @if(isset($engineer) && $engineer->country == 'CS') selected @endif value="CS">Serbia and Montenegro</option>
                    <option @if(isset($engineer) && $engineer->country == 'SC') selected @endif value="SC">Seychelles</option>
                    <option @if(isset($engineer) && $engineer->country == 'SL') selected @endif value="SL">Sierra Leone</option>
                    <option @if(isset($engineer) && $engineer->country == 'SG') selected @endif value="SG">Singapore</option>
                    <option @if(isset($engineer) && $engineer->country == 'SX') selected @endif value="SX">Sint Maarten</option>
                    <option @if(isset($engineer) && $engineer->country == 'SK') selected @endif value="SK">Slovakia</option>
                    <option @if(isset($engineer) && $engineer->country == 'SI') selected @endif value="SI">Slovenia</option>
                    <option @if(isset($engineer) && $engineer->country == 'SB') selected @endif value="SB">Solomon Islands</option>
                    <option @if(isset($engineer) && $engineer->country == 'SO') selected @endif value="SO">Somalia</option>
                    <option @if(isset($engineer) && $engineer->country == 'ZA') selected @endif value="ZA">South Africa</option>
                    <option @if(isset($engineer) && $engineer->country == 'GS') selected @endif value="GS">South Georgia and the South Sandwich Islands</option>
                    <option @if(isset($engineer) && $engineer->country == 'SS') selected @endif value="SS">South Sudan</option>
                    <option @if(isset($engineer) && $engineer->country == 'ES') selected @endif value="ES">Spain</option>
                    <option @if(isset($engineer) && $engineer->country == 'LK') selected @endif value="LK">Sri Lanka</option>
                    <option @if(isset($engineer) && $engineer->country == 'SD') selected @endif value="SD">Sudan</option>
                    <option @if(isset($engineer) && $engineer->country == 'SR') selected @endif value="SR">Suriname</option>
                    <option @if(isset($engineer) && $engineer->country == 'SJ') selected @endif value="SJ">Svalbard and Jan Mayen</option>
                    <option @if(isset($engineer) && $engineer->country == 'SZ') selected @endif value="SZ">Swaziland</option>
                    <option @if(isset($engineer) && $engineer->country == 'SE') selected @endif value="SE">Sweden</option>
                    <option @if(isset($engineer) && $engineer->country == 'CH') selected @endif value="CH">Switzerland</option>
                    <option @if(isset($engineer) && $engineer->country == 'SY') selected @endif value="SY">Syrian Arab Republic</option>
                    <option @if(isset($engineer) && $engineer->country == 'TW') selected @endif value="TW">Taiwan, Province of China</option>
                    <option @if(isset($engineer) && $engineer->country == 'TJ') selected @endif value="TJ">Tajikistan</option>
                    <option @if(isset($engineer) && $engineer->country == 'TZ') selected @endif value="TZ">Tanzania, United Republic of</option>
                    <option @if(isset($engineer) && $engineer->country == 'TH') selected @endif value="TH">Thailand</option>
                    <option @if(isset($engineer) && $engineer->country == 'TL') selected @endif value="TL">Timor-Leste</option>
                    <option @if(isset($engineer) && $engineer->country == 'TG') selected @endif value="TG">Togo</option>
                    <option @if(isset($engineer) && $engineer->country == 'TK') selected @endif value="TK">Tokelau</option>
                    <option @if(isset($engineer) && $engineer->country == 'TO') selected @endif  value="TO">Tonga</option>
                    <option @if(isset($engineer) && $engineer->country == 'TT') selected @endif value="TT">Trinidad and Tobago</option>
                    <option @if(isset($engineer) && $engineer->country == 'TN') selected @endif value="TN">Tunisia</option>
                    <option @if(isset($engineer) && $engineer->country == 'TR') selected @endif  value="TR">Turkey</option>
                    <option @if(isset($engineer) && $engineer->country == 'TM') selected @endif value="TM">Turkmenistan</option>
                    <option @if(isset($engineer) && $engineer->country == 'TC') selected @endif value="TC">Turks and Caicos Islands</option>
                    <option @if(isset($engineer) && $engineer->country == 'TV') selected @endif value="TV">Tuvalu</option>
                    <option @if(isset($engineer) && $engineer->country == 'UG') selected @endif value="UG">Uganda</option>
                    <option @if(isset($engineer) && $engineer->country == 'UA') selected @endif value="UA">Ukraine</option>
                    <option @if(isset($engineer) && $engineer->country == 'AE') selected @endif value="AE">United Arab Emirates</option>
                    <option @if(isset($engineer) && $engineer->country == 'GB') selected @endif value="GB">United Kingdom</option>
                    <option @if(isset($engineer) && $engineer->country == 'US') selected @endif value="US">United States</option>
                    <option @if(isset($engineer) && $engineer->country == 'UM') selected @endif value="UM">United States Minor Outlying Islands</option>
                    <option @if(isset($engineer) && $engineer->country == 'UY') selected @endif value="UY">Uruguay</option>
                    <option @if(isset($engineer) && $engineer->country == 'UZ') selected @endif value="UZ">Uzbekistan</option>
                    <option @if(isset($engineer) && $engineer->country == 'VU') selected @endif value="VU">Vanuatu</option>
                    <option @if(isset($engineer) && $engineer->country == 'VE') selected @endif value="VE">Venezuela</option>
                    <option @if(isset($engineer) && $engineer->country == 'VN') selected @endif value="VN">Viet Nam</option>
                    <option @if(isset($engineer) && $engineer->country == 'VG') selected @endif value="VG">Virgin Islands, British</option>
                    <option @if(isset($engineer) && $engineer->country == 'VI') selected @endif value="VI">Virgin Islands, U.s.</option>
                    <option @if(isset($engineer) && $engineer->country == 'WF') selected @endif value="WF">Wallis and Futuna</option>
                    <option @if(isset($engineer) && $engineer->country == 'EH') selected @endif value="EH">Western Sahara</option>
                    <option @if(isset($engineer) && $engineer->country == 'YE') selected @endif value="YE">Yemen</option>
                    <option @if(isset($engineer) && $engineer->country == 'ZM') selected @endif value="ZM">Zambia</option>
                    <option @if(isset($engineer) && $engineer->country == 'ZW') selected @endif value="ZW">Zimbabwe</option>
                  </select>
                </div>
                @error('country')
                  <span class="text-danger" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
                <div class="text-center m-t-40">                    
                  <button class="btn btn-success btn-cons m-b-10" type="submit"><i class="pg-plus_circle"></i> <span class="bold">@if(isset($engineer)) Update @else Add @endif</span></button>
                  @if(isset($engineer))
                    <button class="btn btn-danger btn-cons btn-delete m-b-10" data-id="{{$engineer->id}}" type="button"><i class="pg-minus_circle"></i> <span class="bold">Delete</span></button>
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

<script type="text/javascript">

      $( document ).ready(function(event) {
        
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
                        url: "/delete_engineer",
                        type: "POST",
                        data: {
                            id: $(this).data('id')
                        },
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        success: function(response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Engineer has been deleted.",
                                type: "success",
                                timer: 3000
                            });

                            window.location.href = '/engineers';
                        }
                    });            
                }
            });
        });
    });

</script>

@endsection