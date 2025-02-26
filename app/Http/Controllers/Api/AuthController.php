<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterationRequest;
use App\Models\Group;
use App\Models\NewsFeed;
use App\Models\User;
use App\Models\UserTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use PH7\Eu\Vat\Validator as VATValidator;
use PH7\Eu\Vat\Provider\Europa;

class AuthController extends Controller
{

    public function createAccount($data, $frontEndID){

        $slaveToolsFlag = 0;

        if(isset( $data['slave_tools_flag'])){
            $slaveToolsFlag = $data['slave_tools_flag'];
        }
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'language' => $data['language'],
            'address' => $data['address'],
            'zip' => $data['zip'],
            'city' => $data['city'],
            'country' => $data['country'],
            'status' => $data['status'],
            'company_name' => $data['company_name'],
            'company_id' => $data['company_id'],
            'front_end_id' => $frontEndID,
            'evc_customer_id' => $data['evc_customer_id'],
            'slave_tools_flag' => $slaveToolsFlag,
            'password' => Hash::make($data['password']),
        ]);

       

    }

    public function setFeed($frontEndID){

        $feed = NewsFeed::where('active', 1)
        ->whereNull('subdealer_group_id')
        ->where('front_end_id', $frontEndID)
        ->first();
        
        Session::put('feed', $feed);
        
    }

    /**
     * 
     * register new user
     */
    // public function registerUser(RegisterationRequest $request){
    public function registerUser(Request $request){

        // dd($request->all());

        $validationArray = [
            'front_end_id' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'language' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'company_name' => ['max:255'],
            'company_id' => ['max:255'],
            'slave_tools_flag' => ['string', 'max:255'],
            'master_tools' => [],
            'slave_tools' => [],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        $validated = $request->validate($validationArray);

        $data = $request->all();

        $frontEndID = $request->front_end_id;
        $this->setFeed($frontEndID);
        $user = $this->createAccount($data, $frontEndID);
        $this->addTools($user, $data);

        // $this->createZohoAccount($user);

        // if($frontEndID == 2){
        //     $this->createMailchimpAccount($user);
        // }

        $this->VATCheckPolicy($user);

        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $user,
        ], 201);
        
    }

    public function addTools($user, $data){

        if(isset( $data['slave_tools_flag'])){
            if(isset($data['slave_tools'])){
                $slaveTools = $data['slave_tools'];
            }
            else{
                $slaveTools = [];
            }
        }
        else{
            
            $slaveTools = [];
        }
        
        if(isset( $data['master_tools'])){
            $masterTools = $data['master_tools'];
        }
        else{
            $masterTools = [];
        }

        if(count($masterTools) > 0){
        
            foreach($masterTools as $mid){

                $record = new UserTool();
                $record->type = 'master';
                $record->user_id = $user->id;
                $record->tool_id = $mid;
                $record->save();
               
            }
        }

        if(count($slaveTools) > 0){
        
            foreach($slaveTools as $sid){

                $record = new UserTool();
                $record->type = 'slave';
                $record->user_id = $user->id;
                $record->tool_id = $sid;
                $record->save();
               
            }
        }

    }

    // public function createMailchimpAccount($user){

    //     try{
        
    //         $client = new MailchimpMarketing\ApiClient();

    //         $client->setConfig([
    //             'apiKey' => 'cdc22134ee97dfedeaa7a85838784b4c-us21',
    //             'server' => 'us21'
    //             ]);
        
    //         //id: cac79dc83a
        
    //         $member = $client->lists->addListMember("cac79dc83a", [
    //             "email_address" => $user->email,
    //             "status" => "pending",
    //             "tags" => ["portal"],
    //         ]);

    //         $user->mailchimp_id = $member->id;
    //         $user->save();
        
    //         $response = $client->lists->setListMember("cac79dc83a", $member->id, [
            
    //         "status" => "subscribed",
    //         "merge_fields" => [

    //             "FNAME" => $user->name,
                
    //            "ADDRESS" => [
    //                 "addr1" => $user->address,
    //                 "city" => $user->city,
    //                 "state" => $user->country,
    //                 "zip" => $user->zip
    //               ]
    //            ],
            
    //         ]);
    //     }

    //     catch(\Exception $e){

    //     }

    // }

    // public function createZohoAccount($user){

    //     $psr6CachePool = new ArrayCachePool();

    //     $oAuthClient = new \Weble\ZohoClient\OAuthClient('1000.4YI5VY0ZVV0RULDS2BEWFU0GGTVYBL', '51c344a63a6a5de0630f64e87ea3676ced55722589');

    //     $oAuthClient->setRefreshToken('1000.4c53b2c0d581b45ceac3f380cb37dc99.b95732ec540ced24f044dcffee32dfa7');
        
    //     $oAuthClient->setRegion('eu');
    //     $oAuthClient->useCache($psr6CachePool);

    //     // setup the zoho books client
    //     $client = new \Webleit\ZohoBooksApi\Client($oAuthClient);
    //     $client->setOrganizationId('8745725');

    //     $zohoBooks = new \Webleit\ZohoBooksApi\ZohoBooks($client);

    //     try{

    //         $sarchContact = $zohoBooks->contacts->getList(['contact_name_contains' => $user->name])->toArray();

    //         if(empty($sarchContact)){

    //             $contact = $zohoBooks->contacts->create(
        
    //                 [
    //                     "contact_name" => $user->name,
    //                     "contact_email" => $user->email,
    //                     "company_name" => $user->company_name,
    //                     "contact_type" => "customer",
    //                     "customer_sub_type" => "business",
    //                     "is_portal_enabled" => false,
    //                     "billing_address" => [
    //                         "attention" =>  "Mr. ".$user->name,
    //                         "address" => $user->address,
    //                         "city" => $user->city,
    //                         "zip" =>  $user->zip,
    //                         "country" =>  $user->country,
    //                         "phone" =>  $user->phone,
    //                     ],
    //                 ]
        
    //             );

    //             $user->zohobooks_id = $contact->contact_id;
    //             $user->save();
    //         }
    //         else{

    //             $value = reset($sarchContact);
    //             $user->zohobooks_id = $value['contact_id'];
    //             $user->save();

    //         }

            
    //     }
    //     catch(ClientException $e){
    //         Log::info($e->getMessage());
    //     }

    // }

    public function validateVAT($user){

        try{

            $oVatValidator = new VATValidator(new Europa, $user->company_id, $user->country);
            return $oVatValidator->check();
        }
        catch(\Exception $e){
            return 0;
        }
        
    }

    public function createDateRangeArray($strDateFrom,$strDateTo){
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.
    
        // could test validity of dates here but I'm already doing
        // that in the main script
    
        $aryRange = [];
    
        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));
    
        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('d/m/y', $iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date('d/m/y', $iDateFrom));
            }
        }
        return $aryRange;
    }

    public function VATCheckPolicy($user){
        
        if($user->front_end_id == 1){
            $vat0Group = Group::where('slug', 'VAT0')->first();
        }
        else if($user->front_end_id == 2){
            $vat0Group = Group::where('slug', 'VAT0-Tuningx')->first();
        }
        else if($user->front_end_id == 3){
            $vat0Group = Group::where('slug', 'VAT0-ETF')->first();
        }

        if($user->front_end_id == 1){
            $nvat0Group = Group::where('slug', 'NVAT0')->first();
        }
        else if($user->front_end_id == 2){
            $nvat0Group = Group::where('slug', 'NVAT0-Tuningx')->first();
        }
        else if($user->front_end_id == 3){
            $nvat0Group = Group::where('slug', 'NVAT0-ETF')->first();
        }

        // if(env('APP_ENV') == 'local'){
        //     $flag = false;
        // }

        // else{
                
        if($user->company_id){
    
            $flag = $this->validateVAT($user);
        }
        else{
            $flag = false;
        }

        // }

        // dd($flag);

        if($user->front_end_id == 3 and $user->country == 'RO'){
            $flag = false;
        }

        if($user->front_end_id == 2 and $user->country == 'GR'){
            $flag = false;
        }

        // dd($flag);

        if($flag){

            $user->group_id = $vat0Group->id;
        }

        else{

            if($this->countryToContinent($user->country) == 'Europe' ){

                if($user->front_end_id == 2){
                    $newGroup = Group::where('slug', 'VAT-'.$user->country)->where('front_end_id', 2)->first();
                }
                else if( $user->front_end_id == 3) {
                    if($user->country == 'GR'){
                        $newGroup = Group::where('slug', 'VAT-'.$user->country.'1')->where('front_end_id', 3)->first();
                    }
                    else{
                        $newGroup = Group::where('slug', 'VAT-'.$user->country)->where('front_end_id', 3)->first();
                    }
                }
                else if( $user->front_end_id == 1){

                    $newGroup = Group::where('slug', 'VAT-'.$user->country)->where('front_end_id', 1)->first();


                }

                if($newGroup){
                    $user->group_id = $newGroup->id;
                }
                else{
                    $user->group_id = $nvat0Group->id;
                }    
                      
            }

            else{

                $user->group_id = $nvat0Group->id;
            }

        }
        
        $user->save();

        return $user;
    }

    // public function loginRule($frontEndID, $user){

    //     $this->setFeed();

    //     if($user->front_end_id == $frontEndID && $user->role_id = 4){

    //         return true;
    //     }

       

    //     if($user->role_id == 1){
    //         return true;
    //     }

    //     return false;
    // }

    public function countryToContinent( $country ){
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

