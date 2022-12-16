<?php

use App\Models\File;

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


if(!function_exists('get_dropdown_image')){

    function get_dropdown_image( $str ){

        if($str == 'Abrites'){
            return 'https://www.shiftech.eu/media/olsx/tools/a78d3579ca3ba08666fe9bee07311b71.png';
        }

        if($str == 'Autotuner'){
            return 'https://www.shiftech.eu/media/olsx/tools/1a4dde08346c9b42c2a0ec4723d78167.png';
        }

        if($str == 'Bflash'){
            return 'https://www.shiftech.eu/media/olsx/tools/aa1f2ec4ef5574cec3ad964d4e609244.png';
        }

        if($str == 'BitBox'){
            return 'https://www.shiftech.eu/media/olsx/tools/eee946df549439b1d7018433ba60a945.png';
        }

        if($str == 'Bytehooter'){
            return 'https://www.shiftech.eu/media/olsx/tools/cabfbee8e94d14a9b853bb58bb3f2c55.png';
        }

        if($str == 'CMD'){
            return 'https://www.shiftech.eu/media/olsx/tools/86b526dcd28044abe85768b4281bc650.png';
        }

        if($str == 'Dataman'){
            return 'https://www.shiftech.eu/media/olsx/tools/bce697620f2519082a5e7e0829992385.png';
        }

        if($str == 'Dimsport_New_Genius'){
            return 'https://www.shiftech.eu/media/olsx/tools/727cc0178ae674fc1938265065f7f8a5.png';
        }

        if($str == 'Dimsport_Trasdata'){
            return 'https://www.shiftech.eu/media/olsx/tools/727cc0178ae674fc1938265065f7f8a5.png';
        }

        if($str == 'ECUx'){
            return 'https://www.shiftech.eu/media/olsx/tools/b6d01abc531540513e0eb517d2f54401.png';
        }

        if($str == 'Femto'){
            return 'https://www.shiftech.eu/media/olsx/tools/c1877efca50995a64dc22c76b17a2208.png';
        }

        if($str == 'Flex'){
            return 'https://www.shiftech.eu/media/olsx/tools/4ebbd61393fb57d18efc69471b790e06.png';
        }

        if($str == 'Galetto'){
            return 'https://www.shiftech.eu/media/olsx/tools/7cd8b9d37bbb5ea6ac44a6c6eb90deb9.jpg';
        }

        if($str == 'HPturners'){
            return 'https://www.shiftech.eu/media/olsx/tools/0bce1546c34b34723d92cc8823a09fb5.png';
        }

        if($str == 'IO_Terminal'){
            return 'https://www.shiftech.eu/media/olsx/tools/84373fc456714d8a46e6e29d7ce94582.png';
        }

        if($str == 'K_tag'){
            return 'https://www.shiftech.eu/media/olsx/tools/91a3d9a2b397a204bba5d68e69d604d4.png';
        }

        if($str == 'Kess_V2'){
            return 'https://www.shiftech.eu/media/olsx/tools/3e1e83cf8d1822fa03253b503673e2bd.png';
        }

        if($str == 'Kess_V3'){
            return 'https://www.shiftech.eu/media/olsx/tools/1794973301d913f0d5ac107499f08658.png';
        }

        if($str == 'Magic_MAGPro2'){
            return 'https://www.shiftech.eu/media/olsx/tools/25dda1d586a444aed5efffaae9fc922f.png';
        }

        if($str == 'MHD'){
            return 'https://www.shiftech.eu/media/olsx/tools/a3825680a204fd00c45f286dd9065772.png';
        }

        if($str == 'MMC_flasher'){
            return 'https://www.shiftech.eu/media/olsx/tools/e7ef7150fadd8a4db720de08b3f93061.png';
        }

        if($str == 'MPPS'){
            return 'https://www.shiftech.eu/media/olsx/tools/f16b03403ded5826ca8a03024c70689d.png';
        }

        if($str == 'PCM_Flash'){
            return 'https://www.shiftech.eu/media/olsx/tools/12b7a6cb5a2597a40ba57df5682fc253.png';
        }

        if($str == 'Powergate'){
            return 'https://www.shiftech.eu/media/olsx/tools/80b9d49b75a96b030fcc462800169d64.png';
        }

        if($str == 'Tactrix'){
            return 'https://www.shiftech.eu/media/olsx/tools/5c1e45d6261c39492cb74ccc20aa6fdf.png';
        }

        if($str == 'TGflash'){
            return 'https://www.shiftech.eu/media/olsx/tools/5798920d750e21f1a5caf28b6061ac51.png';
        }

        if($str == 'EVC_BDM100'){
            return 'https://www.shiftech.eu/media/olsx/tools/b026196f139e13e2535916191afe2051.png';
        }  
    }
}


?>