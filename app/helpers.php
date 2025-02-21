<?php

use App\Models\File;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Tool;
use App\Models\User;
use App\Models\Vehicle;

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
        return File::where('checked_by', 'customer')->where('is_credited', 1)->count();
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
        return User::where('role_id', $head->id)->first();
    }
}

if(!function_exists('get_engineers')){

    function get_engineers(){

        $engineerRole = Role::where('name', 'engineer')->first();
        $engineers = User::where('role_id', $engineerRole->id)->get();
        return $engineers;

    }
}

if(!function_exists('get_customers')){

    function get_customers($frontendID = 0){

        $customerRole = Role::where('name', 'customer')->first();
        
        if($frontendID == 0){
            $customers = User::where('role_id', $customerRole->id)
            ->where('name' ,'!=', 'Live Chat')
            ->whereNotNull('front_end_id')
            ->get();
        }
        else{
            $customers = User::where('role_id', $customerRole->id)
            ->where('front_end_id', $frontendID)
            ->where('name' ,'!=', 'Live Chat')
            ->get();
        }
        
        return $customers;

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