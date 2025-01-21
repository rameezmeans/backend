<?php

namespace App\Http\Controllers;

use App\Models\Key;
use GuzzleHttp\Exception\ClientException;
use App\Models\Log as ModelsLog;
use App\Models\Package;
use App\Models\PaymentLog;
use App\Models\Price;
use App\Models\ZohoRecord;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;


class ZohoController extends Controller
{   

    public function makeZohoLogEntry( $creditID, $type, $message, $call, $response ){

        $log = new ModelsLog();
        $log->type = $type;
        $log->request_type = 'zoho';
        $log->message = $message;
        $log->credit_id = $creditID;

        if(is_array($call) || is_object($call)){
            $log->call = json_encode($call);
        }
        else if(is_string($call)){
            $log->call = $call;
        }
        if(is_array($response) || is_object($response)){
            $log->response = json_encode($response);
        }
        else if(is_string($response)){
            $log->response = $response;
        }

        $log->save();

    }

    // public function createZohoAccount($user, $creditID = 0){

    //     $psr6CachePool = new ArrayCachePool();

    //     $oAuthClient = new \Weble\ZohoClient\OAuthClient('1000.ZFAE3XTRYBAG8M29H586XC97JPEUBW', 'c5b21851c6819ccfabc80f43a8cddec58878bf274a');

    //     $oAuthClient->setRefreshToken('1000.ae786e08a1946d543f675705cbb22ca8.0886b117a3c1d83a48ff7ceb50877fbc');
        
    //     $oAuthClient->setRegion('eu');
    //     $oAuthClient->useCache($psr6CachePool);

    //     // setup the zoho books client
    //     $client = new \Webleit\ZohoBooksApi\Client($oAuthClient);
    //     $client->setOrganizationId('8745725');

    //     $zohoBooks = new \Webleit\ZohoBooksApi\ZohoBooks($client);

    //     $contactDetails = [
    //         "contact_name" => $user->name,
    //         "contact_email" => $user->email,
    //         "company_name" => $user->company_name,
    //         "contact_type" => "customer",
    //         "customer_sub_type" => "business",
    //         "is_portal_enabled" => false,
    //         "billing_address" => [
    //             "attention" =>  "Mr. ".$user->name,
    //             "address" => $user->address,
    //             "city" => $user->city,
    //             "zip" =>  $user->zip,
    //             "country" =>  $user->country,
    //             "phone" =>  $user->phone,
    //         ],
    //     ];

    //     try{
    //         $searchParams = ['contact_name_contains' => $user->name];
    //         $sarchContact = $zohoBooks->contacts->getList($searchParams)->toArray();

    //         if(empty($sarchContact)){
                
    //             $contact = $zohoBooks->contacts->create($contactDetails);

    //             $this->makeZohoLogEntry($creditID, 'success', 'zoho contact creation succeeded.', $contactDetails, $contact);

    //             $user->zohobooks_id = $contact->contact_id;
    //             $user->save();
    //         }
    //         else{

    //             $this->makeZohoLogEntry($creditID, 'success', 'zoho contact search succeeded.', $searchParams, $sarchContact);

    //             $value = reset($sarchContact);
    //             $user->zohobooks_id = $value['contact_id'];
    //             $user->save();

    //         }

            
    //     }
    //     catch(ServerException | ClientException $e ){
    //         $this->makeZohoLogEntry($creditID, 'error', 'zoho contact creation failed.', $contactDetails, $e->getMessage());
    //     }

    // }

    public function getAndSaveAccessToken(){

        $client = new Client();
        $headers = [
            'Cookie' => 'JSESSIONID=F2B5036D3E84A4B9FE5DFE2FA111AB0C; _zcsr_tmp=e8206f8c-210c-45fb-9674-8a80e50e8deb; iamcsr=e8206f8c-210c-45fb-9674-8a80e50e8deb; zalb_b266a5bf57=9f371135a524e2d1f51eb9f41fa26c60'
        ];
        $request = new Request('POST', 'https://accounts.zoho.com/oauth/v2/token?refresh_token=1000.ae786e08a1946d543f675705cbb22ca8.0886b117a3c1d83a48ff7ceb50877fbc&client_id=1000.ZFAE3XTRYBAG8M29H586XC97JPEUBW&client_secret=c5b21851c6819ccfabc80f43a8cddec58878bf274a&redirect_uri=http://www.zoho.com/books&grant_type=refresh_token', $headers);
        $res = $client->sendAsync($request)->wait();
        
        
        if( $res->getStatusCode() == 200 ){
                $accessToken = Key::where('key','zoho_access_token')->first();
                $accessToken->value =  json_decode($res->getBody()->getContents())->access_token;
                $accessToken->save();
        }
    }

    public function makeNewAccessToken(){

        $accessTokenRecord = Key::where('key','zoho_access_token')->first();

        $diff = $accessTokenRecord->updated_at->diffInHours(\Carbon\Carbon::now(), false);
        
        if($diff > 0){
            $this->getAndSaveAccessToken();
        }
    }

    public function searchTestZohoCustomer($user){

        $this->makeNewAccessToken();

        $refreshToken = Key::where('key','zoho_access_token')->first()->value;

        $client = new Client();
        $headers = [
            'Authorization' => 'Zoho-oauthtoken '.$refreshToken,
            'Cookie' => 'JSESSIONID=2F85EA50F6F959734BB2FBC6C961BC67; _zcsr_tmp=845e6c59-4972-49d9-8e71-3fe6ef6371eb; zalb_ba05f91d88=464e4eb91588e0e50558f39420eb27f6; zbcscook=845e6c59-4972-49d9-8e71-3fe6ef6371eb'
        ];

        $searchParams = "email_contains=$user->email";
        $request = new Request('GET', 'https://www.zohoapis.com/books/v3/contacts?organization_id=8745725&'.$searchParams, $headers);

        $response = $client->sendAsync($request)->wait();

        dd($response->getBody()->getContents());

    }

    public function createNewZohoCustomer($user){

        $this->makeNewAccessToken();

        $refreshToken = Key::where('key','zoho_access_token')->first()->value;
        
        $url = 'https://www.zohoapis.com/books/v3/contacts?organization_id=8745725';
                
        $contactDetails = [
            "contact_name" => $user->name,
            "contact_email" => $user->email,
            "company_name" => $user->company_name,
            "contact_type" => "customer",
            "customer_sub_type" => "business",
            "is_portal_enabled" => false,
            "billing_address" => [
                "attention" =>  "Mr. ".$user->name,
                "address" => $user->address,
                "city" => $user->city,
                "zip" =>  $user->zip,
                "country" =>  $user->country,
                "phone" =>  $user->phone,
            ],
        ];

        $jsonData = json_encode($contactDetails);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Authorization: Zoho-oauthtoken ".$refreshToken));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "&JSONString=".urlencode($jsonData));
        $result = curl_exec($ch);

        $responseContact = json_decode($result);
        
        curl_close($ch);

        $contact = $responseContact;

        if($contact->code == 0){

            $user->zohobooks_id = $contact->contact->contact_id;
            $user->save();
            $this->makeZohoLogEntry(0, 'success', 'zoho contact created successfully.', $contactDetails, $contact->message);
        }
        else{
            $this->makeZohoLogEntry(0, 'error', 'zoho contact not created.', $contactDetails, $contact->message);
        }
    }

    public function createTestZohoCustomer($user, $creditID = 0){

        $this->makeNewAccessToken();

        $refreshToken = Key::where('key','zoho_access_token')->first()->value;

        $client = new Client();
        $headers = [
            'Authorization' => 'Zoho-oauthtoken '.$refreshToken,
            'Cookie' => 'JSESSIONID=2F85EA50F6F959734BB2FBC6C961BC67; _zcsr_tmp=845e6c59-4972-49d9-8e71-3fe6ef6371eb; zalb_ba05f91d88=464e4eb91588e0e50558f39420eb27f6; zbcscook=845e6c59-4972-49d9-8e71-3fe6ef6371eb'
        ];

        $searchParams = "contact_name_contains=$user->name";
        $request = new Request('GET', 'https://www.zohoapis.com/books/v3/contacts?organization_id=8745725&'.$searchParams, $headers);
        
        try{

            $response = $client->sendAsync($request)->wait();

            if($response->getStatusCode() == 200){

                $contacts = json_decode($response->getBody()->getContents())->contacts;

                if( $contacts == NULL){
                    
                $url = 'https://www.zohoapis.com/books/v3/contacts?organization_id=8745725';
                
                $contactDetails = [
                    "contact_name" => $user->name,
                    "contact_email" => $user->email,
                    "company_name" => $user->company_name,
                    "contact_type" => "customer",
                    "customer_sub_type" => "business",
                    "is_portal_enabled" => false,
                    "billing_address" => [
                        "attention" =>  "Mr. ".$user->name,
                        "address" => $user->address,
                        "city" => $user->city,
                        "zip" =>  $user->zip,
                        "country" =>  $user->country,
                        "phone" =>  $user->phone,
                    ],
                ];

                $jsonData = json_encode($contactDetails);

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_VERBOSE, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Authorization: Zoho-oauthtoken ".$refreshToken));
                curl_setopt($ch, CURLOPT_POSTFIELDS, "&JSONString=".urlencode($jsonData));
                $result = curl_exec($ch);

                $responseContact = json_decode($result);
                
                curl_close($ch);

                $contact = $responseContact;

                    if($contact->code == 0){

                        $user->zohobooks_id = $contact->contact->contact_id;
                        $user->save();
                        $this->makeZohoLogEntry($creditID, 'success', 'zoho contact created successfully.', $contactDetails, $contact->message);
                    }
                    else{
                        $this->makeZohoLogEntry($creditID, 'error', 'zoho contact not created.', $contactDetails, $contact->message);
                    }

                }

                else{

                    $contact = $contacts[0];
                    $user->zohobooks_id = $contact->contact_id;
                    $user->save();

                    $this->makeZohoLogEntry($creditID, 'success', 'zoho contact search succeeded.', $searchParams, $contact);
                }
            }

        }
        catch(ServerException | ClientException $e ){

            $this->makeZohoLogEntry($creditID, 'error', 'zoho contact not created.', $searchParams, $e->getMessage());
            
        }
    }

    public function createTestZohoInvoiceAndPayment($user, $invoice, $package = false, $type = 'stripe', $packageID){
        
        $this->makeNewAccessToken();

        if( $user->zohobooks_id == NULL ){
            $this->createTestZohoCustomer($user, $invoice->id);
        }

        $refreshToken = Key::where('key','zoho_access_token')->first()->value;

        $url = 'https://www.zohoapis.com/books/v3/invoices?organization_id=8745725';
            
        $logInstance = new PaymentLog();
        $logInstance->payment_id = $invoice->id;
        $logInstance->user_id = $invoice->user_id;

        if($invoice->type == 'stripe'){
            $account = $user->stripe_payment_account();
            $accountID = $account->zohobooks_account_id;
            
        }
        else if($type == 'viva'){
            $account = $user->viva_payment_account();
            $accountID = $account->zohobooks_account_id;
        }
        else{
            $account = $user->paypal_payment_account();
            $accountID = $account->zohobooks_account_id;
        }
        
        if($package == true){
        
            $actualPackage = Package::findOrFail($packageID);
            $itemID = $actualPackage->zohobook_item_id;
            $items = 1;
            
        }
        else{
            // if($user->front_end_id == 3){
                
                $creditPrice = Price::where('label', 'credit_price')
                ->whereNull('subdealer_group_id')
                ->where('front_end_id', $user->front_end_id)
                ->first();

                // dd($creditPrice);

                $itemID = $creditPrice->zoho_item_id;

                // $itemID = '261618000009378031';
            // }
            // else{
            //     $itemID = '261618000006280029';
            // }
            $items = $invoice->credits;
        }

        if($user->group->zohobooks_tax_id){
            $taxID = $user->group->zohobooks_tax_id;
            $tax = (float) $user->group->tax;
            $taxName = $user->group->name;
        }
        else{
            $taxID = '261618000002962001';
            $tax = 0;
            $taxName = $user->group->name;
        }

        $invoiceDetails = [
            'customer_id' => $user->zohobooks_id,
            "current_sub_status" => "due",
            "currency_id" => "261618000000008020",
            "currency_code" => "EUR",
            "currency_symbol" => "€",
            "template_type" => "standard",
            "no_of_copies" => 1,
            "date" => $invoice->created_at->format('Y-m-d'),
            "due_date" => $invoice->created_at->format('Y-m-d'),
            "show_no_of_copies" => true,
            "transaction_type" => "renewal",
            "template_id" => "261618000000308053",
            "line_items" => [

                [
                    "item_id" => $itemID,
                    "quantity" => $items,
                    "tax_id" => $taxID,
                    "tax_name" => $taxName,
                    "tax_type" => "tax",
                    "tax_percentage" => $tax,
                ]

            ]

        ];

        $jsonData = json_encode($invoiceDetails);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Authorization: Zoho-oauthtoken ".$refreshToken));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "&JSONString=".urlencode($jsonData));
        $result = curl_exec($ch);

        $response = json_decode($result);
        
        curl_close($ch);

        $zohoInvoice = $response;

        if($zohoInvoice->code == 0){

            $invoice->zohobooks_id = $zohoInvoice->invoice->invoice_id;
            $invoice->save();

            $logInstance->zohobooks_invoice_number = $zohoInvoice->invoice->invoice_number;
            $logInstance->save();

            $zohoRecord = new ZohoRecord();
            $zohoRecord->zoho_id = $zohoInvoice->invoice->invoice_id;
            $zohoRecord->amount = $zohoInvoice->invoice->sub_total;
            $zohoRecord->tax = $zohoInvoice->invoice->tax_total;
            $zohoRecord->credit_id = $invoice->id;
            $zohoRecord->desc =  $zohoInvoice->invoice->invoice_number;
            $zohoRecord->save();

            $this->makeZohoLogEntry($invoice->id, 'success', 'zoho invoice created successfully.', $invoiceDetails, $zohoInvoice->message);


            // mark invoice sent code starts here ///

            $url = 'https://www.zohoapis.com/books/v3/invoices/'.$zohoInvoice->invoice->invoice_id.'/status/sent?organization_id=8745725';
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Authorization: Zoho-oauthtoken ".$refreshToken));
            // curl_setopt($ch, CURLOPT_POSTFIELDS, "&JSONString=".urlencode($paymentJsonData));
            $sentResult = curl_exec($ch);

            $sentResponse = json_decode($sentResult);
            
            curl_close($ch);

            //mark invoice sent code ends here ///


            /// payment code starts ///

            $url = 'https://www.zohoapis.com/books/v3/customerpayments?organization_id=8745725';
            
            $paymentDetails = [

                "payment_mode" => $type,
                "amount" =>$zohoInvoice->invoice->total,
                // "bank_charges" => 0,
                "date" => $zohoInvoice->invoice->date,
                // "status" => "success",
                "reference_number" => $zohoInvoice->invoice->invoice_number,
                "description" => "Payment has been added to ".$zohoInvoice->invoice->invoice_number,
                "customer_id" => $zohoInvoice->invoice->customer_id,
                // "customer_name" =>$zohoInvoice->customer_name,
                // "email" => $user->email,
                "invoices" => [
                    [
                        "invoice_id" => $zohoInvoice->invoice->invoice_id,
                        // "invoice_number" => $zohoInvoice->invoice_number,
                        "date" => $zohoInvoice->invoice->date,
                        // "invoice_amount" => $zohoInvoice->total,
                        "amount_applied" => $zohoInvoice->invoice->total,
                        // "balance_amount" => 0
                    ]
                ],
                // "currency_code" => $zohoInvoice->currency_code,
                // "invoice_id" => $zohoInvoice->invoice_id,
                "account_id" => $accountID,
            ];

            $paymentJsonData = json_encode($paymentDetails);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Authorization: Zoho-oauthtoken ".$refreshToken));
            curl_setopt($ch, CURLOPT_POSTFIELDS, "&JSONString=".urlencode($paymentJsonData));
            $result = curl_exec($ch);

            $paymentResponse = json_decode($result);
            
            curl_close($ch);

            $payment = $paymentResponse;

            if($payment->code == 0){
                $this->makeZohoLogEntry($invoice->id, 'success', 'zoho payment created.', $paymentDetails, $payment->message);
            }
            else{
                $this->makeZohoLogEntry($invoice->id, 'error', 'zoho payment not created.', $paymentDetails, $payment->message);
            }
            
            /// payment code ends ///

        }

        else{

            $this->makeZohoLogEntry($invoice->id, 'error', 'zoho invoice not created.', $invoiceDetails, $zohoInvoice->message);
        }

        dd('test invoice created');

    }

    // public function createTestZohoCustomerOld($user, $creditID = 0){

    //     $psr6CachePool = new ArrayCachePool();

    //     $oAuthClient = new \Weble\ZohoClient\OAuthClient('1000.ZFAE3XTRYBAG8M29H586XC97JPEUBW', 'c5b21851c6819ccfabc80f43a8cddec58878bf274a');

    //     $oAuthClient->setRefreshToken('1000.ae786e08a1946d543f675705cbb22ca8.0886b117a3c1d83a48ff7ceb50877fbc');
        
    //     $oAuthClient->setRegion('eu');
        
    //     $oAuthClient->useCache($psr6CachePool);

    //     $accessToken = $oAuthClient->getAccessToken();

    //     // dd($accessToken);

    //     // $isExpired = $oAuthClient->accessTokenExpired();

    //     // dd($isExpired);
    //     // dd($accessToken);

    //     // setup the zoho books client
    //     $client = new \Webleit\ZohoBooksApi\Client($oAuthClient);
    //     $client->setOrganizationId('8745725');
        
    //     // dd($client);

    //     $zohoBooks = new \Webleit\ZohoBooksApi\ZohoBooks($client);

    //     $invoices = $zohoBooks->invoices->getList();

    //     dd($invoices);

    //     // $searchParams = ['contact_name_contains' => $user->name];
    //     // $sarchContact = $zohoBooks->contacts->getList($searchParams)->toArray();

    //     // dd($searchParams);

    //     // dd($zohoBooks->getAvailableModules());

    //     $contactDetails = [
    //         "contact_name" => $user->name,
    //         "contact_email" => $user->email,
    //         "company_name" => $user->company_name,
    //         "contact_type" => "customer",
    //         "customer_sub_type" => "business",
    //         "is_portal_enabled" => false,
    //         "billing_address" => [
    //             "attention" =>  "Mr. ".$user->name,
    //             "address" => $user->address,
    //             "city" => $user->city,
    //             "zip" =>  $user->zip,
    //             "country" =>  $user->country,
    //             "phone" =>  $user->phone,
    //         ],
    //     ];

    //     try{
    //         $searchParams = ['contact_name_contains' => $user->name];
    //         $sarchContact = $zohoBooks->contacts->getList($searchParams)->toArray();

    //         dd($sarchContact);

    //         if(empty($sarchContact)){
                
    //             $contact = $zohoBooks->contacts->create($contactDetails);

    //             $this->makeZohoLogEntry($creditID, 'success', 'zoho contact creation succeeded.', $contactDetails, $contact);

    //             $user->zohobooks_id = $contact->contact_id;
    //             $user->save();
    //         }
    //         else{

    //             $this->makeZohoLogEntry($creditID, 'success', 'zoho contact search succeeded.', $searchParams, $sarchContact);

    //             $value = reset($sarchContact);
    //             $user->zohobooks_id = $value['contact_id'];
    //             $user->save();

    //         }

            
    //     }
    //     catch(ServerException | ClientException $e ){

    //         dd($e->getMessage());

    //         $this->makeZohoLogEntry($creditID, 'error', 'zoho contact creation failed.', $contactDetails, $e->getMessage());
    //     }

    // }

    // public function createZohobooksInvoiceOLD($user, $invoice, $package = false, $type = 'stripe', $packageID){

    //     $logInstance = new PaymentLog();
    //     $logInstance->payment_id = $invoice->id;
    //     $logInstance->user_id = $invoice->user_id;

    //     if($invoice->type == 'stripe'){
    //         $account = $user->stripe_payment_account();
    //         $accountID = $account->zohobooks_account_id;
            
    //     }
    //     else if($type == 'viva'){
    //         $account = $user->viva_payment_account();
    //         $accountID = $account->zohobooks_account_id;
    //     }
    //     else{
    //         $account = $user->paypal_payment_account();
    //         $accountID = $account->zohobooks_account_id;
    //     }
        
    //     if($package == true){
        
    //         $actualPackage = Package::findOrFail($packageID);
    //         $itemID = $actualPackage->zohobook_item_id;
    //         $items = 1;
            
    //     }
    //     else{
    //         // if($user->front_end_id == 3){
                
    //             $creditPrice = Price::where('label', 'credit_price')
    //             ->whereNull('subdealer_group_id')
    //             ->where('front_end_id', $user->front_end_id)
    //             ->first();

    //             $itemID = $creditPrice->zoho_item_id;

    //             // $itemID = '261618000009378031';
    //         // }
    //         // else{
    //         //     $itemID = '261618000006280029';
    //         // }
    //         $items = $invoice->credits;
    //     }

    //     if($user->group->zohobooks_tax_id){
    //         $taxID = $user->group->zohobooks_tax_id;
    //         $tax = (float) $user->group->tax;
    //         $taxName = $user->group->name;
    //     }
    //     else{
    //         $taxID = '261618000002962001';
    //         $tax = 0;
    //         $taxName = $user->group->name;
    //     }
        
    //     $psr6CachePool = new ArrayCachePool();

    //     $oAuthClient = new \Weble\ZohoClient\OAuthClient('1000.ZFAE3XTRYBAG8M29H586XC97JPEUBW', 'c5b21851c6819ccfabc80f43a8cddec58878bf274a');

    //     $oAuthClient->setRefreshToken('1000.ae786e08a1946d543f675705cbb22ca8.0886b117a3c1d83a48ff7ceb50877fbc');
        
    //     $oAuthClient->setRegion('eu');
    //     $oAuthClient->useCache($psr6CachePool);

    //     $accessToken = $oAuthClient->getAccessToken();

    //     // setup the zoho books client
    //     $client = new \Webleit\ZohoBooksApi\Client($oAuthClient);
    //     $client->setOrganizationId('8745725');

    //     $zohoBooks = new \Webleit\ZohoBooksApi\ZohoBooks($client);

    //     $zohoInvoice = NULL;

    //     $invoiceDetails = [
    //         'customer_id' => $user->zohobooks_id,
    //         "current_sub_status" => "due",
    //         "currency_id" => "261618000000008020",
    //         "currency_code" => "EUR",
    //         "currency_symbol" => "€",
    //         "template_type" => "standard",
    //         "no_of_copies" => 1,
    //         "show_no_of_copies" => true,
    //         "transaction_type" => "renewal",
    //         "template_id" => "261618000000308053",
    //         "line_items" => [

    //             [
    //                 "item_id" => $itemID,
    //                 "quantity" => $items,
    //                 "tax_id" => $taxID,
    //                 "tax_name" => $taxName,
    //                 "tax_type" => "tax",
    //                 "tax_percentage" => $tax,
    //             ]

    //         ]

    //     ];

    //     $zohoInvoice = NULL;

    //     try{

    //         $zohoInvoice = $zohoBooks->invoices->create(

    //             $invoiceDetails
        
    //         );

    //         $this->makeZohoLogEntry($invoice->id, 'success', 'zoho invoice addded.', $invoiceDetails, $zohoInvoice);

    //     }
    //     catch(ServerException | ClientException $e ){
    //         // dd($e->getMessage());
    //         $this->makeZohoLogEntry($invoice->id, 'error', 'zoho invoice failed.', $invoiceDetails, $e->getMessage());
    //     }

    //     if(isset($zohoInvoice)){

    //         $zohoBooks->invoices->markAs($zohoInvoice->invoice_id, 'sent');

    //         $paymentDetails = [

    //             "payment_mode" => $type,
    //             "amount" =>$zohoInvoice->total,
    //             // "bank_charges" => 0,
    //             "date" => $zohoInvoice->date,
    //             // "status" => "success",
    //             "reference_number" => $zohoInvoice->invoice_number,
    //             "description" => "Payment has been added to ".$invoice->invoice_number,
    //             "customer_id" => $zohoInvoice->customer_id,
    //             // "customer_name" =>$zohoInvoice->customer_name,
    //             // "email" => $user->email,
    //             "invoices" => [
    //                 [
    //                     "invoice_id" => $zohoInvoice->invoice_id,
    //                     // "invoice_number" => $zohoInvoice->invoice_number,
    //                     "date" => $zohoInvoice->date,
    //                     // "invoice_amount" => $zohoInvoice->total,
    //                     "amount_applied" => $zohoInvoice->total,
    //                     // "balance_amount" => 0
    //                 ]
    //             ],
    //             // "currency_code" => $zohoInvoice->currency_code,
    //             // "invoice_id" => $zohoInvoice->invoice_id,
    //             "account_id" => $accountID,
    //         ];

    //         try{

    //             $payment = $zohoBooks->customerpayments->create($paymentDetails);  

    //             $this->makeZohoLogEntry($invoice->id, 'success', 'zoho payment addded.', $paymentDetails, $payment);
                
    //         }
    //         catch(ServerException | ClientException $e ){
                
    //             $this->makeZohoLogEntry($invoice->id, 'error', 'zoho payment failed.', $paymentDetails, $e->getMessage());
    //         }
            
    //     } 
        
    //     if(isset($zohoInvoice)){
            
    //         $invoice->zohobooks_id = $zohoInvoice->invoice_id;
    //         $invoice->save();

    //         $logInstance->zohobooks_invoice_number = $zohoInvoice->invoice_number;
    //         $logInstance->save();

    //         $zohoRecord = new ZohoRecord();
    //         $zohoRecord->zoho_id = $zohoInvoice->invoice_id;
    //         $zohoRecord->amount = $zohoInvoice->sub_total;
    //         $zohoRecord->tax = $zohoInvoice->tax_total;
    //         $zohoRecord->credit_id = $invoice->id;
    //         $zohoRecord->desc =  $zohoInvoice->invoice_number;
    //         $zohoRecord->save();

    //     }

    // }


    public function createZohobooksInvoice($user, $invoice, $package = false, $type = 'stripe', $packageID){

        $this->makeNewAccessToken();

        $refreshToken = Key::where('key','zoho_access_token')->first()->value;

        $url = 'https://www.zohoapis.com/books/v3/invoices?organization_id=8745725';
            
        $logInstance = new PaymentLog();
        $logInstance->payment_id = $invoice->id;
        $logInstance->user_id = $invoice->user_id;

        if($invoice->type == 'stripe'){
            $account = $user->stripe_payment_account();
            $accountID = $account->zohobooks_account_id;
            
        }
        else if($type == 'viva'){
            $account = $user->viva_payment_account();
            $accountID = $account->zohobooks_account_id;
        }
        else{
            $account = $user->paypal_payment_account();
            $accountID = $account->zohobooks_account_id;
        }
        
        if($package == true){
        
            $actualPackage = Package::findOrFail($packageID);
            $itemID = $actualPackage->zohobook_item_id;
            $items = 1;
            
        }
        else{
            // if($user->front_end_id == 3){
                
                $creditPrice = Price::where('label', 'credit_price')
                ->whereNull('subdealer_group_id')
                ->where('front_end_id', $user->front_end_id)
                ->first();

                // dd($creditPrice);

                $itemID = $creditPrice->zoho_item_id;

                // $itemID = '261618000009378031';
            // }
            // else{
            //     $itemID = '261618000006280029';
            // }
            $items = $invoice->credits;
        }

        if($user->group->zohobooks_tax_id){
            $taxID = $user->group->zohobooks_tax_id;
            $tax = (float) $user->group->tax;
            $taxName = $user->group->name;
        }
        else{
            $taxID = '261618000002962001';
            $tax = 0;
            $taxName = $user->group->name;
        }

        $invoiceDetails = [
            'customer_id' => $user->zohobooks_id,
            "current_sub_status" => "due",
            "currency_id" => "261618000000008020",
            "currency_code" => "EUR",
            "currency_symbol" => "€",
            "template_type" => "standard",
            "no_of_copies" => 1,
            "show_no_of_copies" => true,
            "transaction_type" => "renewal",
            "template_id" => "261618000000308053",
            "line_items" => [

                [
                    "item_id" => $itemID,
                    "quantity" => $items,
                    "tax_id" => $taxID,
                    "tax_name" => $taxName,
                    "tax_type" => "tax",
                    "tax_percentage" => $tax,
                ]

            ]

        ];

        $jsonData = json_encode($invoiceDetails);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Authorization: Zoho-oauthtoken ".$refreshToken));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "&JSONString=".urlencode($jsonData));
        $result = curl_exec($ch);

        $response = json_decode($result);
        
        curl_close($ch);

        $zohoInvoice = $response;

        if($zohoInvoice->code == 0){

            $invoice->zohobooks_id = $zohoInvoice->invoice->invoice_id;
            $invoice->save();

            $logInstance->zohobooks_invoice_number = $zohoInvoice->invoice->invoice_number;
            $logInstance->save();

            $zohoRecord = new ZohoRecord();
            $zohoRecord->zoho_id = $zohoInvoice->invoice->invoice_id;
            $zohoRecord->amount = $zohoInvoice->invoice->sub_total;
            $zohoRecord->tax = $zohoInvoice->invoice->tax_total;
            $zohoRecord->credit_id = $invoice->id;
            $zohoRecord->desc =  $zohoInvoice->invoice->invoice_number;
            $zohoRecord->save();

            $this->makeZohoLogEntry($invoice->id, 'success', 'zoho invoice created successfully.', $invoiceDetails, $zohoInvoice->message);


            // mark invoice sent code starts here ///

            $url = 'https://www.zohoapis.com/books/v3/invoices/'.$zohoInvoice->invoice->invoice_id.'/status/sent?organization_id=8745725';
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Authorization: Zoho-oauthtoken ".$refreshToken));
            // curl_setopt($ch, CURLOPT_POSTFIELDS, "&JSONString=".urlencode($paymentJsonData));
            $sentResult = curl_exec($ch);

            $sentResponse = json_decode($sentResult);
            
            curl_close($ch);

            //mark invoice sent code ends here ///


            /// payment code starts ///

            $url = 'https://www.zohoapis.com/books/v3/customerpayments?organization_id=8745725';
            
            $paymentDetails = [

                "payment_mode" => $type,
                "amount" =>$zohoInvoice->invoice->total,
                // "bank_charges" => 0,
                "date" => $zohoInvoice->invoice->date,
                // "status" => "success",
                "reference_number" => $zohoInvoice->invoice->invoice_number,
                "description" => "Payment has been added to ".$zohoInvoice->invoice->invoice_number,
                "customer_id" => $zohoInvoice->invoice->customer_id,
                // "customer_name" =>$zohoInvoice->customer_name,
                // "email" => $user->email,
                "invoices" => [
                    [
                        "invoice_id" => $zohoInvoice->invoice->invoice_id,
                        // "invoice_number" => $zohoInvoice->invoice_number,
                        "date" => $zohoInvoice->invoice->date,
                        // "invoice_amount" => $zohoInvoice->total,
                        "amount_applied" => $zohoInvoice->invoice->total,
                        // "balance_amount" => 0
                    ]
                ],
                // "currency_code" => $zohoInvoice->currency_code,
                // "invoice_id" => $zohoInvoice->invoice_id,
                "account_id" => $accountID,
            ];

            $paymentJsonData = json_encode($paymentDetails);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Authorization: Zoho-oauthtoken ".$refreshToken));
            curl_setopt($ch, CURLOPT_POSTFIELDS, "&JSONString=".urlencode($paymentJsonData));
            $result = curl_exec($ch);

            $paymentResponse = json_decode($result);
            
            curl_close($ch);

            $payment = $paymentResponse;

            if($payment->code == 0){
                $this->makeZohoLogEntry($invoice->id, 'success', 'zoho payment created.', $paymentDetails, $payment->message);
            }
            else{
                $this->makeZohoLogEntry($invoice->id, 'error', 'zoho payment not created.', $paymentDetails, $payment->message);
            }
            
            /// payment code ends ///

        }

        else{

            $this->makeZohoLogEntry($invoice->id, 'error', 'zoho invoice not created.', $invoiceDetails, $zohoInvoice->message);
        }

    }

    public function createZohoAccount($user, $creditID = 0){

        $this->makeNewAccessToken();

        $refreshToken = Key::where('key','zoho_access_token')->first()->value;

        $client = new Client();
        $headers = [
            'Authorization' => 'Zoho-oauthtoken '.$refreshToken,
            'Cookie' => 'JSESSIONID=2F85EA50F6F959734BB2FBC6C961BC67; _zcsr_tmp=845e6c59-4972-49d9-8e71-3fe6ef6371eb; zalb_ba05f91d88=464e4eb91588e0e50558f39420eb27f6; zbcscook=845e6c59-4972-49d9-8e71-3fe6ef6371eb'
        ];

        $searchParams = "contact_name_contains=$user->name";
        $request = new Request('GET', 'https://www.zohoapis.com/books/v3/contacts?organization_id=8745725&'.$searchParams, $headers);
        
        try{

            $response = $client->sendAsync($request)->wait();

            if($response->getStatusCode() == 200){

                $contacts = json_decode($response->getBody()->getContents())->contacts;

                if( $contacts == NULL){
                    
                $url = 'https://www.zohoapis.com/books/v3/contacts?organization_id=8745725';
                
                $contactDetails = [
                    "contact_name" => $user->name,
                    "contact_email" => $user->email,
                    "company_name" => $user->company_name,
                    "contact_type" => "customer",
                    "customer_sub_type" => "business",
                    "is_portal_enabled" => false,
                    "billing_address" => [
                        "attention" =>  "Mr. ".$user->name,
                        "address" => $user->address,
                        "city" => $user->city,
                        "zip" =>  $user->zip,
                        "country" =>  $user->country,
                        "phone" =>  $user->phone,
                    ],
                ];

                $jsonData = json_encode($contactDetails);

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_VERBOSE, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Authorization: Zoho-oauthtoken ".$refreshToken));
                curl_setopt($ch, CURLOPT_POSTFIELDS, "&JSONString=".urlencode($jsonData));
                $result = curl_exec($ch);

                $response = json_decode($result);
                
                curl_close($ch);

                $contact = $response;

                    if($contact->code == 0){

                        $user->zohobooks_id = $contact->contact->contact_id;
                        $user->save();
                        $this->makeZohoLogEntry($creditID, 'success', 'zoho contact created successfully.', $contactDetails, $contact->message);
                    }
                    else{
                        $this->makeZohoLogEntry($creditID, 'error', 'zoho contact not created.', $contactDetails, $contact->message);
                    }

                }

                else{

                    $contact = $contacts[0];
                    $user->zohobooks_id = $contact->contact_id;
                    $user->save();

                    $this->makeZohoLogEntry($creditID, 'success', 'zoho contact search succeeded.', $searchParams, $contact);
                }
            }

        }
        catch(ServerException | ClientException $e ){

            $this->makeZohoLogEntry($creditID, 'error', 'zoho contact not created.', $searchParams, $e->getMessage());
            
        }
        
    }

}

?>