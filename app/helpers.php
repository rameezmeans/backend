<?php

use App\Models\File;
use App\Models\Key;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Tool;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\EngineersPermission;

use Danielebarbaro\LaravelVatEuValidator\Facades\VatValidatorFacade as VatValidator;

function fullescape($in)

{

  $out = '';

  for ($i=0;$i<strlen($in);$i++)

  {

    $hex = dechex(ord($in[$i]));

    if ($hex=='') 

       $out = $out.urlencode($in[$i]);

    else 

       $out = $out .'%'.((strlen($hex)==1) ? ('0'.strtoupper($hex)):(strtoupper($hex)));

  }

  $out = str_replace('+','%20',$out);

  $out = str_replace('_','%5F',$out);

  $out = str_replace('.','%2E',$out);

  $out = str_replace('-','%2D',$out);

  $out = str_replace('#','%23',$out);

  return $out;

 }

function encodeURIComponent($str) {
    $revert = array('%21'=>'!','%23'=>'#', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($str), $revert);
}

function decodeURIComponent($str) {
    $revert = array('#'=>'%23');
    return strtr(rawurlencode($str), $revert);
}

if(!function_exists('default_elorus_id')){
    function default_elorus_id(){
        $defaultTemplateID = Key::where('key', 'default_elorus_template_id')->first();
        return $defaultTemplateID->value;
    }
}

if(!function_exists('code_to_country')){

    function code_to_country( $code ){
    
        if($code == ''){
            return '';
        }
    
        $code = strtoupper($code);
        $countryList = array(
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas the',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island (Bouvetoya)',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
            'VG' => 'British Virgin Islands',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros the',
            'CD' => 'Congo',
            'CG' => 'Congo the',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote d\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FO' => 'Faroe Islands',
            'FK' => 'Falkland Islands (Malvinas)',
            'FJ' => 'Fiji the Fiji Islands',
            'FI' => 'Finland',
            'FR' => 'France, French Republic',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia the',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island and McDonald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KP' => 'Korea',
            'KR' => 'Korea',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyz Republic',
            'LA' => 'Lao',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'AN' => 'Netherlands Antilles',
            'NL' => 'Netherlands the',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn Islands',
            'PL' => 'Poland',
            'PT' => 'Portugal, Portuguese Republic',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre and Miquelon',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia (Slovak Republic)',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia, Somali Republic',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia and the South Sandwich Islands',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard & Jan Mayen Islands',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland, Swiss Confederation',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States of America',
            'UM' => 'United States Minor Outlying Islands',
            'VI' => 'United States Virgin Islands',
            'UY' => 'Uruguay, Eastern Republic of',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'WF' => 'Wallis and Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe'
        );

        return array_key_exists($code, $countryList) ? $countryList[$code] : $code;
    
    }
}

if(!function_exists('count_of_files')){

    function count_of_files(){
        $count =  File::where('checked_by', 'customer')->where('type', 'master')->where('is_credited', 1)->count();
        $countSubdealer =  File::where('checked_by', 'customer')->where('type', 'subdealer')->whereNotNull('assigned_from')->where('is_credited', 1)->count();
        return $count+$countSubdealer;
    }
}

function getFlags($code){
    $code = strtoupper($code);
    if($code == 'AD') return '🇦🇩';
    if($code == 'AE') return '🇦🇪';
    if($code == 'AF') return '🇦🇫';
    if($code == 'AG') return '🇦🇬';
    if($code == 'AI') return '🇦🇮';
    if($code == 'AL') return '🇦🇱';
    if($code == 'AM') return '🇦🇲';
    if($code == 'AO') return '🇦🇴';
    if($code == 'AQ') return '🇦🇶';
    if($code == 'AR') return '🇦🇷';
    if($code == 'AS') return '🇦🇸';
    if($code == 'AT') return '🇦🇹';
    if($code == 'AU') return '🇦🇺';
    if($code == 'AW') return '🇦🇼';
    if($code == 'AX') return '🇦🇽';
    if($code == 'AZ') return '🇦🇿';
    if($code == 'BA') return '🇧🇦';
    if($code == 'BB') return '🇧🇧';
    if($code == 'BD') return '🇧🇩';
    if($code == 'BE') return '🇧🇪';
    if($code == 'BF') return '🇧🇫';
    if($code == 'BG') return '🇧🇬';
    if($code == 'BH') return '🇧🇭';
    if($code == 'BI') return '🇧🇮';
    if($code == 'BJ') return '🇧🇯';
    if($code == 'BL') return '🇧🇱';
    if($code == 'BM') return '🇧🇲';
    if($code == 'BN') return '🇧🇳';
    if($code == 'BO') return '🇧🇴';
    if($code == 'BQ') return '🇧🇶';
    if($code == 'BR') return '🇧🇷';
    if($code == 'BS') return '🇧🇸';
    if($code == 'BT') return '🇧🇹';
    if($code == 'BV') return '🇧🇻';
    if($code == 'BW') return '🇧🇼';
    if($code == 'BY') return '🇧🇾';
    if($code == 'BZ') return '🇧🇿';
    if($code == 'CA') return '🇨🇦';
    if($code == 'CC') return '🇨🇨';
    if($code == 'CD') return '🇨🇩';
    if($code == 'CF') return '🇨🇫';
    if($code == 'CG') return '🇨🇬';
    if($code == 'CH') return '🇨🇭';
    if($code == 'CI') return '🇨🇮';
    if($code == 'CK') return '🇨🇰';
    if($code == 'CL') return '🇨🇱';
    if($code == 'CM') return '🇨🇲';
    if($code == 'CN') return '🇨🇳';
    if($code == 'CO') return '🇨🇴';
    if($code == 'CR') return '🇨🇷';
    if($code == 'CU') return '🇨🇺';
    if($code == 'CV') return '🇨🇻';
    if($code == 'CW') return '🇨🇼';
    if($code == 'CX') return '🇨🇽';
    if($code == 'CY') return '🇨🇾';
    if($code == 'CZ') return '🇨🇿';
    if($code == 'DE') return '🇩🇪';
    if($code == 'DJ') return '🇩🇯';
    if($code == 'DK') return '🇩🇰';
    if($code == 'DM') return '🇩🇲';
    if($code == 'DO') return '🇩🇴';
    if($code == 'DZ') return '🇩🇿';
    if($code == 'EC') return '🇪🇨';
    if($code == 'EE') return '🇪🇪';
    if($code == 'EG') return '🇪🇬';
    if($code == 'EH') return '🇪🇭';
    if($code == 'ER') return '🇪🇷';
    if($code == 'ES') return '🇪🇸';
    if($code == 'ET') return '🇪🇹';
    if($code == 'FI') return '🇫🇮';
    if($code == 'FJ') return '🇫🇯';
    if($code == 'FK') return '🇫🇰';
    if($code == 'FM') return '🇫🇲';
    if($code == 'FO') return '🇫🇴';
    if($code == 'FR') return '🇫🇷';
    if($code == 'GA') return '🇬🇦';
    if($code == 'GB') return '🇬🇧';
    if($code == 'GD') return '🇬🇩';
    if($code == 'GE') return '🇬🇪';
    if($code == 'GF') return '🇬🇫';
    if($code == 'GG') return '🇬🇬';
    if($code == 'GH') return '🇬🇭';
    if($code == 'GI') return '🇬🇮';
    if($code == 'GL') return '🇬🇱';
    if($code == 'GM') return '🇬🇲';
    if($code == 'GN') return '🇬🇳';
    if($code == 'GP') return '🇬🇵';
    if($code == 'GQ') return '🇬🇶';
    if($code == 'GR') return '🇬🇷';
    if($code == 'GS') return '🇬🇸';
    if($code == 'GT') return '🇬🇹';
    if($code == 'GU') return '🇬🇺';
    if($code == 'GW') return '🇬🇼';
    if($code == 'GY') return '🇬🇾';
    if($code == 'HK') return '🇭🇰';
    if($code == 'HM') return '🇭🇲';
    if($code == 'HN') return '🇭🇳';
    if($code == 'HR') return '🇭🇷';
    if($code == 'HT') return '🇭🇹';
    if($code == 'HU') return '🇭🇺';
    if($code == 'ID') return '🇮🇩';
    if($code == 'IE') return '🇮🇪';
    if($code == 'IL') return '🇮🇱';
    if($code == 'IM') return '🇮🇲';
    if($code == 'IN') return '🇮🇳';
    if($code == 'IO') return '🇮🇴';
    if($code == 'IQ') return '🇮🇶';
    if($code == 'IR') return '🇮🇷';
    if($code == 'IS') return '🇮🇸';
    if($code == 'IT') return '🇮🇹';
    if($code == 'JE') return '🇯🇪';
    if($code == 'JM') return '🇯🇲';
    if($code == 'JO') return '🇯🇴';
    if($code == 'JP') return '🇯🇵';
    if($code == 'KE') return '🇰🇪';
    if($code == 'KG') return '🇰🇬';
    if($code == 'KH') return '🇰🇭';
    if($code == 'KI') return '🇰🇮';
    if($code == 'KM') return '🇰🇲';
    if($code == 'KN') return '🇰🇳';
    if($code == 'KP') return '🇰🇵';
    if($code == 'KR') return '🇰🇷';
    if($code == 'KW') return '🇰🇼';
    if($code == 'KY') return '🇰🇾';
    if($code == 'KZ') return '🇰🇿';
    if($code == 'LA') return '🇱🇦';
    if($code == 'LB') return '🇱🇧';
    if($code == 'LC') return '🇱🇨';
    if($code == 'LI') return '🇱🇮';
    if($code == 'LK') return '🇱🇰';
    if($code == 'LR') return '🇱🇷';
    if($code == 'LS') return '🇱🇸';
    if($code == 'LT') return '🇱🇹';
    if($code == 'LU') return '🇱🇺';
    if($code == 'LV') return '🇱🇻';
    if($code == 'LY') return '🇱🇾';
    if($code == 'MA') return '🇲🇦';
    if($code == 'MC') return '🇲🇨';
    if($code == 'MD') return '🇲🇩';
    if($code == 'ME') return '🇲🇪';
    if($code == 'MF') return '🇲🇫';
    if($code == 'MG') return '🇲🇬';
    if($code == 'MH') return '🇲🇭';
    if($code == 'MK') return '🇲🇰';
    if($code == 'ML') return '🇲🇱';
    if($code == 'MM') return '🇲🇲';
    if($code == 'MN') return '🇲🇳';
    if($code == 'MO') return '🇲🇴';
    if($code == 'MP') return '🇲🇵';
    if($code == 'MQ') return '🇲🇶';
    if($code == 'MR') return '🇲🇷';
    if($code == 'MS') return '🇲🇸';
    if($code == 'MT') return '🇲🇹';
    if($code == 'MU') return '🇲🇺';
    if($code == 'MV') return '🇲🇻';
    if($code == 'MW') return '🇲🇼';
    if($code == 'MX') return '🇲🇽';
    if($code == 'MY') return '🇲🇾';
    if($code == 'MZ') return '🇲🇿';
    if($code == 'NA') return '🇳🇦';
    if($code == 'NC') return '🇳🇨';
    if($code == 'NE') return '🇳🇪';
    if($code == 'NF') return '🇳🇫';
    if($code == 'NG') return '🇳🇬';
    if($code == 'NI') return '🇳🇮';
    if($code == 'NL') return '🇳🇱';
    if($code == 'NO') return '🇳🇴';
    if($code == 'NP') return '🇳🇵';
    if($code == 'NR') return '🇳🇷';
    if($code == 'NU') return '🇳🇺';
    if($code == 'NZ') return '🇳🇿';
    if($code == 'OM') return '🇴🇲';
    if($code == 'PA') return '🇵🇦';
    if($code == 'PE') return '🇵🇪';
    if($code == 'PF') return '🇵🇫';
    if($code == 'PG') return '🇵🇬';
    if($code == 'PH') return '🇵🇭';
    if($code == 'PK') return '🇵🇰';
    if($code == 'PL') return '🇵🇱';
    if($code == 'PM') return '🇵🇲';
    if($code == 'PN') return '🇵🇳';
    if($code == 'PR') return '🇵🇷';
    if($code == 'PS') return '🇵🇸';
    if($code == 'PT') return '🇵🇹';
    if($code == 'PW') return '🇵🇼';
    if($code == 'PY') return '🇵🇾';
    if($code == 'QA') return '🇶🇦';
    if($code == 'RE') return '🇷🇪';
    if($code == 'RO') return '🇷🇴';
    if($code == 'RS') return '🇷🇸';
    if($code == 'RU') return '🇷🇺';
    if($code == 'RW') return '🇷🇼';
    if($code == 'SA') return '🇸🇦';
    if($code == 'SB') return '🇸🇧';
    if($code == 'SC') return '🇸🇨';
    if($code == 'SD') return '🇸🇩';
    if($code == 'SE') return '🇸🇪';
    if($code == 'SG') return '🇸🇬';
    if($code == 'SH') return '🇸🇭';
    if($code == 'SI') return '🇸🇮';
    if($code == 'SJ') return '🇸🇯';
    if($code == 'SK') return '🇸🇰';
    if($code == 'SL') return '🇸🇱';
    if($code == 'SM') return '🇸🇲';
    if($code == 'SN') return '🇸🇳';
    if($code == 'SO') return '🇸🇴';
    if($code == 'SR') return '🇸🇷';
    if($code == 'SS') return '🇸🇸';
    if($code == 'ST') return '🇸🇹';
    if($code == 'SV') return '🇸🇻';
    if($code == 'SX') return '🇸🇽';
    if($code == 'SY') return '🇸🇾';
    if($code == 'SZ') return '🇸🇿';
    if($code == 'TC') return '🇹🇨';
    if($code == 'TD') return '🇹🇩';
    if($code == 'TF') return '🇹🇫';
    if($code == 'TG') return '🇹🇬';
    if($code == 'TH') return '🇹🇭';
    if($code == 'TJ') return '🇹🇯';
    if($code == 'TK') return '🇹🇰';
    if($code == 'TL') return '🇹🇱';
    if($code == 'TM') return '🇹🇲';
    if($code == 'TN') return '🇹🇳';
    if($code == 'TO') return '🇹🇴';
    if($code == 'TR') return '🇹🇷';
    if($code == 'TT') return '🇹🇹';
    if($code == 'TV') return '🇹🇻';
    if($code == 'TW') return '🇹🇼';
    if($code == 'TZ') return '🇹🇿';
    if($code == 'UA') return '🇺🇦';
    if($code == 'UG') return '🇺🇬';
    if($code == 'UM') return '🇺🇲';
    if($code == 'US') return '🇺🇸';
    if($code == 'UY') return '🇺🇾';
    if($code == 'UZ') return '🇺🇿';
    if($code == 'VA') return '🇻🇦';
    if($code == 'VC') return '🇻🇨';
    if($code == 'VE') return '🇻🇪';
    if($code == 'VG') return '🇻🇬';
    if($code == 'VI') return '🇻🇮';
    if($code == 'VN') return '🇻🇳';
    if($code == 'VU') return '🇻🇺';
    if($code == 'WF') return '🇼🇫';
    if($code == 'WS') return '🇼🇸';
    if($code == 'XK') return '🇽🇰';
    if($code == 'YE') return '🇾🇪';
    if($code == 'YT') return '🇾🇹';
    if($code == 'ZA') return '🇿🇦';
    if($code == 'ZM') return '🇿🇲';
    return '🏳';
}

if(!function_exists('get_admin')){

    function get_admin(){
        $admin = Role::where('name', 'admin')->first();
        return User::where('role_id', $admin->id)->first();
    }
}

if(!function_exists('get_head')){

    function get_head(){
        $head = Role::where('name', 'head')->first();
        
        return User::where('role_id', $head->id)
        ->whereNull('subdealer_group_id')
        ->first();
    }
}

if(!function_exists('get_engineers')){

    function get_engineers(){

        $engineerRole = Role::where('name', 'engineer')->first();
        $engineers = User::where('role_id', $engineerRole->id)
        ->whereNull('subdealer_group_id')
        ->orWhere('role_id', 2)->get()->whereNull('subdealer_group_id');
        return $engineers;

    }
}

if(!function_exists('validate_VAT')){

    function validate_VAT($vat){

        $temp = 0;

        do{

            try{
                $flag = VatValidator::validate($vat);
            }catch(Danielebarbaro\LaravelVatEuValidator\Vies\ViesException $e){
                $temp = 0;
                continue;
            }
            $temp = 1;
        }while($temp > 0);

        return $flag;

    }
}

if(!function_exists('country_to_continent')){

    function country_to_continent( $country ){
            $continent = '';
            if( $country == 'AF' ) $continent = 'Asia';
            if( $country == 'AX' ) $continent = 'Europe';
            if( $country == 'AL' ) $continent = 'Europe';
            if( $country == 'DZ' ) $continent = 'Africa';
            if( $country == 'AS' ) $continent = 'Oceania';
            if( $country == 'AD' ) $continent = 'Europe';
            if( $country == 'AO' ) $continent = 'Africa';
            if( $country == 'AI' ) $continent = 'North America';
            if( $country == 'AQ' ) $continent = 'Antarctica';
            if( $country == 'AG' ) $continent = 'North America';
            if( $country == 'AR' ) $continent = 'South America';
            if( $country == 'AM' ) $continent = 'Asia';
            if( $country == 'AW' ) $continent = 'North America';
            if( $country == 'AU' ) $continent = 'Oceania';
            if( $country == 'AT' ) $continent = 'Europe';
            if( $country == 'AZ' ) $continent = 'Asia';
            if( $country == 'BS' ) $continent = 'North America';
            if( $country == 'BH' ) $continent = 'Asia';
            if( $country == 'BD' ) $continent = 'Asia';
            if( $country == 'BB' ) $continent = 'North America';
            if( $country == 'BY' ) $continent = 'Europe';
            if( $country == 'BE' ) $continent = 'Europe';
            if( $country == 'BZ' ) $continent = 'North America';
            if( $country == 'BJ' ) $continent = 'Africa';
            if( $country == 'BM' ) $continent = 'North America';
            if( $country == 'BT' ) $continent = 'Asia';
            if( $country == 'BO' ) $continent = 'South America';
            if( $country == 'BA' ) $continent = 'Europe';
            if( $country == 'BW' ) $continent = 'Africa';
            if( $country == 'BV' ) $continent = 'Antarctica';
            if( $country == 'BR' ) $continent = 'South America';
            if( $country == 'IO' ) $continent = 'Asia';
            if( $country == 'VG' ) $continent = 'North America';
            if( $country == 'BN' ) $continent = 'Asia';
            if( $country == 'BG' ) $continent = 'Europe';
            if( $country == 'BF' ) $continent = 'Africa';
            if( $country == 'BI' ) $continent = 'Africa';
            if( $country == 'KH' ) $continent = 'Asia';
            if( $country == 'CM' ) $continent = 'Africa';
            if( $country == 'CA' ) $continent = 'North America';
            if( $country == 'CV' ) $continent = 'Africa';
            if( $country == 'KY' ) $continent = 'North America';
            if( $country == 'CF' ) $continent = 'Africa';
            if( $country == 'TD' ) $continent = 'Africa';
            if( $country == 'CL' ) $continent = 'South America';
            if( $country == 'CN' ) $continent = 'Asia';
            if( $country == 'CX' ) $continent = 'Asia';
            if( $country == 'CC' ) $continent = 'Asia';
            if( $country == 'CO' ) $continent = 'South America';
            if( $country == 'KM' ) $continent = 'Africa';
            if( $country == 'CD' ) $continent = 'Africa';
            if( $country == 'CG' ) $continent = 'Africa';
            if( $country == 'CK' ) $continent = 'Oceania';
            if( $country == 'CR' ) $continent = 'North America';
            if( $country == 'CI' ) $continent = 'Africa';
            if( $country == 'HR' ) $continent = 'Europe';
            if( $country == 'CU' ) $continent = 'North America';
            if( $country == 'CY' ) $continent = 'Asia';
            if( $country == 'CZ' ) $continent = 'Europe';
            if( $country == 'DK' ) $continent = 'Europe';
            if( $country == 'DJ' ) $continent = 'Africa';
            if( $country == 'DM' ) $continent = 'North America';
            if( $country == 'DO' ) $continent = 'North America';
            if( $country == 'EC' ) $continent = 'South America';
            if( $country == 'EG' ) $continent = 'Africa';
            if( $country == 'SV' ) $continent = 'North America';
            if( $country == 'GQ' ) $continent = 'Africa';
            if( $country == 'ER' ) $continent = 'Africa';
            if( $country == 'EE' ) $continent = 'Europe';
            if( $country == 'ET' ) $continent = 'Africa';
            if( $country == 'FO' ) $continent = 'Europe';
            if( $country == 'FK' ) $continent = 'South America';
            if( $country == 'FJ' ) $continent = 'Oceania';
            if( $country == 'FI' ) $continent = 'Europe';
            if( $country == 'FR' ) $continent = 'Europe';
            if( $country == 'GF' ) $continent = 'South America';
            if( $country == 'PF' ) $continent = 'Oceania';
            if( $country == 'TF' ) $continent = 'Antarctica';
            if( $country == 'GA' ) $continent = 'Africa';
            if( $country == 'GM' ) $continent = 'Africa';
            if( $country == 'GE' ) $continent = 'Asia';
            if( $country == 'DE' ) $continent = 'Europe';
            if( $country == 'GH' ) $continent = 'Africa';
            if( $country == 'GI' ) $continent = 'Europe';
            if( $country == 'GR' ) $continent = 'Europe';
            if( $country == 'GL' ) $continent = 'North America';
            if( $country == 'GD' ) $continent = 'North America';
            if( $country == 'GP' ) $continent = 'North America';
            if( $country == 'GU' ) $continent = 'Oceania';
            if( $country == 'GT' ) $continent = 'North America';
            if( $country == 'GG' ) $continent = 'Europe';
            if( $country == 'GN' ) $continent = 'Africa';
            if( $country == 'GW' ) $continent = 'Africa';
            if( $country == 'GY' ) $continent = 'South America';
            if( $country == 'HT' ) $continent = 'North America';
            if( $country == 'HM' ) $continent = 'Antarctica';
            if( $country == 'VA' ) $continent = 'Europe';
            if( $country == 'HN' ) $continent = 'North America';
            if( $country == 'HK' ) $continent = 'Asia';
            if( $country == 'HU' ) $continent = 'Europe';
            if( $country == 'IS' ) $continent = 'Europe';
            if( $country == 'IN' ) $continent = 'Asia';
            if( $country == 'ID' ) $continent = 'Asia';
            if( $country == 'IR' ) $continent = 'Asia';
            if( $country == 'IQ' ) $continent = 'Asia';
            if( $country == 'IE' ) $continent = 'Europe';
            if( $country == 'IM' ) $continent = 'Europe';
            if( $country == 'IL' ) $continent = 'Asia';
            if( $country == 'IT' ) $continent = 'Europe';
            if( $country == 'JM' ) $continent = 'North America';
            if( $country == 'JP' ) $continent = 'Asia';
            if( $country == 'JE' ) $continent = 'Europe';
            if( $country == 'JO' ) $continent = 'Asia';
            if( $country == 'KZ' ) $continent = 'Asia';
            if( $country == 'KE' ) $continent = 'Africa';
            if( $country == 'KI' ) $continent = 'Oceania';
            if( $country == 'KP' ) $continent = 'Asia';
            if( $country == 'KR' ) $continent = 'Asia';
            if( $country == 'KW' ) $continent = 'Asia';
            if( $country == 'KG' ) $continent = 'Asia';
            if( $country == 'LA' ) $continent = 'Asia';
            if( $country == 'LV' ) $continent = 'Europe';
            if( $country == 'LB' ) $continent = 'Asia';
            if( $country == 'LS' ) $continent = 'Africa';
            if( $country == 'LR' ) $continent = 'Africa';
            if( $country == 'LY' ) $continent = 'Africa';
            if( $country == 'LI' ) $continent = 'Europe';
            if( $country == 'LT' ) $continent = 'Europe';
            if( $country == 'LU' ) $continent = 'Europe';
            if( $country == 'MO' ) $continent = 'Asia';
            if( $country == 'MK' ) $continent = 'Europe';
            if( $country == 'MG' ) $continent = 'Africa';
            if( $country == 'MW' ) $continent = 'Africa';
            if( $country == 'MY' ) $continent = 'Asia';
            if( $country == 'MV' ) $continent = 'Asia';
            if( $country == 'ML' ) $continent = 'Africa';
            if( $country == 'MT' ) $continent = 'Europe';
            if( $country == 'MH' ) $continent = 'Oceania';
            if( $country == 'MQ' ) $continent = 'North America';
            if( $country == 'MR' ) $continent = 'Africa';
            if( $country == 'MU' ) $continent = 'Africa';
            if( $country == 'YT' ) $continent = 'Africa';
            if( $country == 'MX' ) $continent = 'North America';
            if( $country == 'FM' ) $continent = 'Oceania';
            if( $country == 'MD' ) $continent = 'Europe';
            if( $country == 'MC' ) $continent = 'Europe';
            if( $country == 'MN' ) $continent = 'Asia';
            if( $country == 'ME' ) $continent = 'Europe';
            if( $country == 'MS' ) $continent = 'North America';
            if( $country == 'MA' ) $continent = 'Africa';
            if( $country == 'MZ' ) $continent = 'Africa';
            if( $country == 'MM' ) $continent = 'Asia';
            if( $country == 'NA' ) $continent = 'Africa';
            if( $country == 'NR' ) $continent = 'Oceania';
            if( $country == 'NP' ) $continent = 'Asia';
            if( $country == 'AN' ) $continent = 'North America';
            if( $country == 'NL' ) $continent = 'Europe';
            if( $country == 'NC' ) $continent = 'Oceania';
            if( $country == 'NZ' ) $continent = 'Oceania';
            if( $country == 'NI' ) $continent = 'North America';
            if( $country == 'NE' ) $continent = 'Africa';
            if( $country == 'NG' ) $continent = 'Africa';
            if( $country == 'NU' ) $continent = 'Oceania';
            if( $country == 'NF' ) $continent = 'Oceania';
            if( $country == 'MP' ) $continent = 'Oceania';
            if( $country == 'NO' ) $continent = 'Europe';
            if( $country == 'OM' ) $continent = 'Asia';
            if( $country == 'PK' ) $continent = 'Asia';
            if( $country == 'PW' ) $continent = 'Oceania';
            if( $country == 'PS' ) $continent = 'Asia';
            if( $country == 'PA' ) $continent = 'North America';
            if( $country == 'PG' ) $continent = 'Oceania';
            if( $country == 'PY' ) $continent = 'South America';
            if( $country == 'PE' ) $continent = 'South America';
            if( $country == 'PH' ) $continent = 'Asia';
            if( $country == 'PN' ) $continent = 'Oceania';
            if( $country == 'PL' ) $continent = 'Europe';
            if( $country == 'PT' ) $continent = 'Europe';
            if( $country == 'PR' ) $continent = 'North America';
            if( $country == 'QA' ) $continent = 'Asia';
            if( $country == 'RE' ) $continent = 'Africa';
            if( $country == 'RO' ) $continent = 'Europe';
            if( $country == 'RU' ) $continent = 'Europe';
            if( $country == 'RW' ) $continent = 'Africa';
            if( $country == 'BL' ) $continent = 'North America';
            if( $country == 'SH' ) $continent = 'Africa';
            if( $country == 'KN' ) $continent = 'North America';
            if( $country == 'LC' ) $continent = 'North America';
            if( $country == 'MF' ) $continent = 'North America';
            if( $country == 'PM' ) $continent = 'North America';
            if( $country == 'VC' ) $continent = 'North America';
            if( $country == 'WS' ) $continent = 'Oceania';
            if( $country == 'SM' ) $continent = 'Europe';
            if( $country == 'ST' ) $continent = 'Africa';
            if( $country == 'SA' ) $continent = 'Asia';
            if( $country == 'SN' ) $continent = 'Africa';
            if( $country == 'RS' ) $continent = 'Europe';
            if( $country == 'SC' ) $continent = 'Africa';
            if( $country == 'SL' ) $continent = 'Africa';
            if( $country == 'SG' ) $continent = 'Asia';
            if( $country == 'SK' ) $continent = 'Europe';
            if( $country == 'SI' ) $continent = 'Europe';
            if( $country == 'SB' ) $continent = 'Oceania';
            if( $country == 'SO' ) $continent = 'Africa';
            if( $country == 'ZA' ) $continent = 'Africa';
            if( $country == 'GS' ) $continent = 'Antarctica';
            if( $country == 'ES' ) $continent = 'Europe';
            if( $country == 'LK' ) $continent = 'Asia';
            if( $country == 'SD' ) $continent = 'Africa';
            if( $country == 'SR' ) $continent = 'South America';
            if( $country == 'SJ' ) $continent = 'Europe';
            if( $country == 'SZ' ) $continent = 'Africa';
            if( $country == 'SE' ) $continent = 'Europe';
            if( $country == 'CH' ) $continent = 'Europe';
            if( $country == 'SY' ) $continent = 'Asia';
            if( $country == 'TW' ) $continent = 'Asia';
            if( $country == 'TJ' ) $continent = 'Asia';
            if( $country == 'TZ' ) $continent = 'Africa';
            if( $country == 'TH' ) $continent = 'Asia';
            if( $country == 'TL' ) $continent = 'Asia';
            if( $country == 'TG' ) $continent = 'Africa';
            if( $country == 'TK' ) $continent = 'Oceania';
            if( $country == 'TO' ) $continent = 'Oceania';
            if( $country == 'TT' ) $continent = 'North America';
            if( $country == 'TN' ) $continent = 'Africa';
            if( $country == 'TR' ) $continent = 'Asia';
            if( $country == 'TM' ) $continent = 'Asia';
            if( $country == 'TC' ) $continent = 'North America';
            if( $country == 'TV' ) $continent = 'Oceania';
            if( $country == 'UG' ) $continent = 'Africa';
            if( $country == 'UA' ) $continent = 'Europe';
            if( $country == 'AE' ) $continent = 'Asia';
            if( $country == 'GB' ) $continent = 'Europe';
            if( $country == 'US' ) $continent = 'North America';
            if( $country == 'UM' ) $continent = 'Oceania';
            if( $country == 'VI' ) $continent = 'North America';
            if( $country == 'UY' ) $continent = 'South America';
            if( $country == 'UZ' ) $continent = 'Asia';
            if( $country == 'VU' ) $continent = 'Oceania';
            if( $country == 'VE' ) $continent = 'South America';
            if( $country == 'VN' ) $continent = 'Asia';
            if( $country == 'WF' ) $continent = 'Oceania';
            if( $country == 'EH' ) $continent = 'Africa';
            if( $country == 'YE' ) $continent = 'Asia';
            if( $country == 'ZM' ) $continent = 'Africa';
            if( $country == 'ZW' ) $continent = 'Africa';
            return $continent;
        }
    }

if(!function_exists('get_subdealer_user')){

    function get_subdealer_user($subdealer_group_id){
        $role = Role::where('name', 'subdealer')->first();
        $subdealer = User::where('subdealer_group_id', $subdealer_group_id)
        ->where('role_id', $role->id)->first();

        return $subdealer;
    }
}

if(!function_exists('send_error_email')){

    function send_error_email($paymentID, $message, $frontendID){
        \Mail::to('xrkalix@gmail.com')->send(new \App\Mail\AllMails(['front_end_id' => $frontendID, 'html' => $message." payment id:".$paymentID, 'subject' => "Invoice Failed for Payemnt ID:".$paymentID])); 
        \Mail::to('tsichlakidis@ecutech.gr')->send(new \App\Mail\AllMails([ 'front_end_id' => $frontendID, 'html' => $message.". payment id:".$paymentID, 'subject' => "Invoice Failed for Payemnt ID:".$paymentID])); 
        return 1;
    }
}

if(!function_exists('all_files_uploaded')){

    function all_files_uploaded($ecu, $brand, $serviceID){
        
        $files = File::where('files.ecu', $ecu)->where('files.brand', $brand)
        ->join('file_services', 'file_services.file_id', '=', 'files.id')
        ->where('file_services.service_id', $serviceID)
        ->select('*', 'files.id AS file_id')
        ->get();

        $totalRevisions = 0;
        foreach($files as $row){
            $file = File::findOrFail($row->file_id);
            $totalRevisions += $file->files->count();
        }

        return $totalRevisions;
    }
}
if(!function_exists('all_files_with_this_ecu_brand_and_service')){

    function all_files_with_this_ecu_brand_and_service($ecu, $brand, $serviceID, $softwareID){

        $fileProcessedWithSoftware = File::where('files.ecu', $ecu)->where('files.brand', $brand)
        ->join('file_reply_software_service', 'file_reply_software_service.file_id', '=', 'files.id')
        ->where('file_reply_software_service.service_id', $serviceID)
        ->where('file_reply_software_service.software_id', $softwareID)
        ->select('*', 'files.id AS file_id')
        ->distinct()->count('files.id');

        return $fileProcessedWithSoftware;

        // $filesCount = File::where('files.ecu', $ecu)->where('files.brand', $brand)
        // ->join('file_services', 'file_services.file_id', '=', 'files.id')
        // ->where('file_services.service_id', $serviceID)
        // ->select('*', 'files.id AS file_id')
        // ->count();

        // return $filesCount;
    
        // $fileProcessed = File::where('files.ecu', $ecu)->where('files.brand', $brand)
        // ->join('file_reply_software_service', 'file_reply_software_service.file_id', '=', 'files.id')
        // ->where('file_reply_software_service.service_id', $serviceID)
        
        // ->select('*', 'files.id AS file_id')
        // ->distinct()->count('file_reply_software_service.reply_id');

        // return $fileProcessed;
    }
}

if(!function_exists('all_files_with_this_ecu_brand_and_service_and_software')){

    function all_files_with_this_ecu_brand_and_service_and_software($ecu, $brand, $serviceID, $softwareID){
        $fileProcessedWithSoftware = File::where('files.ecu', $ecu)->where('files.brand', $brand)
        ->join('file_reply_software_service', 'file_reply_software_service.file_id', '=', 'files.id')
        ->where('file_reply_software_service.service_id', $serviceID)
        ->where('file_reply_software_service.software_id', $softwareID)
        ->select('file_reply_software_service.reply_id')
        ->distinct()->count('file_reply_software_service.reply_id');

        return $fileProcessedWithSoftware;
    }
}

if(!function_exists('get_customers')){

    function get_customers($frontendID = 0){

        $customerRole = Role::where('name', 'customer')->first();
        
        if($frontendID == 0){
            $customers = User::orderBy('created_at', 'desc')
            ->where('role_id', $customerRole->id)
            ->where('test', 0)
            ->where('name' ,'!=', 'Live Chat')
            ->where('name' ,'!=', 'Live Chat Sub')
            ->whereNotNull('front_end_id')
            ->get();
        }
        else{
            $customers = User::orderBy('created_at', 'desc')
            ->where('role_id', $customerRole->id)
            ->where('front_end_id', $frontendID)
            ->where('test', 0)
            ->where('name' ,'!=', 'Live Chat')
            ->where('name' ,'!=', 'Live Chat Sub')
            ->get();
        }
        
        return $customers;

    }
}

if(!function_exists('heads_count')){

    function heads_count(){
        
        return User::where('role_id', 2)->whereNULl('subdealer_group_id')->count();
    }
}

if(!function_exists('get_permission')){

    function get_permission($subdealerID, $permission){
        $permissionObj = Permission::where('permission', $permission)
            ->where('subdealer_group_id', $subdealerID)->first();

        if($permissionObj){
            return true;
        }

        return false;
    }
}

if(!function_exists('get_engineers_permission')){

    function get_engineers_permission($engineerID, $permission){

        $engineer = User::findOrFail($engineerID);

        if($permission == 'head'){

            if($engineer->role_id == 2){

                return true;
            }

            return false;
            
        }

        $permissionObj = EngineersPermission::where('permission', $permission)
        ->where('engineer_id', $engineerID)->first();

        if($permissionObj){

            return true;
        }

        return false;
    }
}

if(!function_exists('get_dropdown_image')){

    function get_dropdown_image( $id ){

        $tool = Tool::findOrFail($id);
        if($tool){
            return "http://backend.ecutech.gr/icons/".$tool->icon;
        }
    }
}

if(!function_exists('get_image_from_brand')){

    function get_image_from_brand( $brand ){
        if(Vehicle::where('make', '=', $brand)->whereNotNull('Brand_image_URL')->first()){
            return Vehicle::where('make', '=', $brand)->whereNotNull('Brand_image_URL')->first()->Brand_image_URL;
        }
        else {
            return url('').'/icons/logos/logo_white.png';
        }
    }
}


?>