<?php

namespace App\Http\Controllers\Api;

use App\Models\Credit;
use App\Models\IntegerMeta;
use App\Models\Log as ModelsLog;
use App\Models\Package;
use App\Models\Price;
use App\Models\User;
use App\Models\PaypalRecord;
use App\Models\StripeRecord;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\InvalidRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Omnipay\Omnipay;
use Srmklive\PayPal\Facades\PayPal;

class PaymentAPIController{

    // 02/09/24 14:21
    // credits undefined problem solved

    private $gateway;
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');   
    }

    public function makePaymentLogEntry( $creditID, $requestType, $type, $message, $call, $response ){

        $log = new ModelsLog();
        $log->type = $type;
        $log->request_type = $requestType;
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

    public function redirectPaypalOffer($user, $unitPrice, $creditsToBuy, $creditsForFile, $fileID){

        $this->gateway->setClientId($user->paypal_payment_account()->key);
        $this->gateway->setSecret($user->paypal_payment_account()->secret);
        
        if($user->test == 1){
            $this->gateway->setTestMode(true);
        }
        else{
            $this->gateway->setTestMode(false);
        }
        
        // $configArr = config('paypal');

        // $configArr['live']['client_id'] = $user->paypal_payment_account()->key;
        // $configArr['live']['client_secret'] = $user->paypal_payment_account()->secret;

        // PayPal::setProvider();
        // $paypalProvider = PayPal::getProvider();
        // $paypalProvider->setApiCredentials($configArr);
        // $paypalProvider->setAccessToken($paypalProvider->getAccessToken());

        $taxPer = 0;

        if($user->group->tax > 0){
            $taxPer = (float) $user->group->tax;
        }

        $tax = ($taxPer * $unitPrice)/100;

        $unitPrice = $unitPrice + $tax;

        $price = $unitPrice*$creditsToBuy;

        // $data = [
        //     "intent"              => "CAPTURE",
        //     "purchase_units"      => [
        //         [
        //             "amount" => [
        //                 "value"         => number_format((float)$price, 2, '.', ''),
        //                 "currency_code" => 'EUR',
                        
        //             ],
        //             "category" => 'DIGITAL_GOODS',
        //         ],
        //     ],
        //     "application_context" => [
        //         "cancel_url" => route('checkout.cancel'),
        //         "return_url" => url("success?type=paypal&purpose=offer&creditsForFile=".$creditsForFile."&file_id=".$fileID."&creditsToBuy=".$creditsToBuy),
        //     ],
        // ];

        // $order = $paypalProvider->createOrder($data);
        // if (isset($order['links'])){
        //     return redirect($order['links'][1]['href']);
        // }
        // else{
        //     return redirect()->route('shop-product')->with( 'success', 'sorry, something went wrong.');
        // }

        try {

            $response = $this->gateway->purchase(array(
                'amount' => $unitPrice*$creditsToBuy,
                'credits' => $creditsToBuy,
                'currency' => 'eur',
                'returnUrl' => url("success?type=paypal&purpose=offer&creditsForFile=".$creditsForFile."&file_id=".$fileID."&creditsToBuy=".$creditsToBuy),
                'cancelUrl' => route('checkout.evc.cancel', [], true)
            ))->send();
                
            if ($response->isRedirect()) {
                $response->redirect();
            }
            else{
                dd($response);
                return redirect()->route('evc-credits-shop')->with( 'danger', $response->getMessage());

            }

        } catch (\Throwable $th) {
            return $th->getMessage();
        }

    }

    public function redirectStripeOffer($user, $unitPrice, $creditsToBuy, $creditsForFile, $fileID){

        \Stripe\Stripe::setApiKey($user->stripe_payment_account()->secret);
        
        // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $taxPer = 0;

        if($user->group->tax > 0){
            $taxPer = (float) $user->group->tax;
        }

        $tax = ($taxPer * $unitPrice)/100;

        $unitPrice = $unitPrice + $tax;

        $lineItems = [];
        
        $lineItems[] = [
          'price_data' => [
              'currency' => 'eur',
              'product_data' => [
                'name' => "Tuning Credit(s)"
            ],
              'unit_amount' => $unitPrice * 100,
          ],
          'quantity' => $creditsToBuy,
        ];

        $uniqueKey=strtoupper(substr(sha1(microtime()), rand(0, 5), 20));  
        $uniqueKey  = implode("", str_split($uniqueKey, 5));

        $session = \Stripe\Checkout\Session::create([
            'client_reference_id' => ''.$uniqueKey.'',
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}&purpose=offer&type=stripe&creditsForFile=".$creditsForFile."&file_id=".$fileID."&creditsToBuy=".$creditsToBuy,
            'cancel_url' => route('checkout.cancel', [], true),
        ]);

        return redirect($session->url);

    }

    public function redirectStripeFile($user, $unitPrice, $creditsToBuy, $creditsForFile, $fileID){
        
        \Stripe\Stripe::setApiKey($user->stripe_payment_account()->secret);
        
        // \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $taxPer = 0;

        if($user->group->tax > 0){
            $taxPer = (float) $user->group->tax;
        }

        $tax = ($taxPer * $unitPrice)/100;

        $unitPrice = $unitPrice + $tax;

        $lineItems = [];
        
        $lineItems[] = [
          'price_data' => [
              'currency' => 'eur',
              'product_data' => [
                'name' => "Tuning Credit(s)"
            ],
              'unit_amount' => $unitPrice * 100,
          ],
          'quantity' => $creditsToBuy,
        ];

        $uniqueKey=strtoupper(substr(sha1(microtime()), rand(0, 5), 20));  
        $uniqueKey  = implode("", str_split($uniqueKey, 5));

        $session = \Stripe\Checkout\Session::create([
            'client_reference_id' => ''.$uniqueKey.'',
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}&type=stripe&creditsForFile=".$creditsForFile."&file_id=".$fileID."&creditsToBuy=".$creditsToBuy,
            'cancel_url' => route('checkout.cancel', [], true),
        ]);

        return redirect($session->url);
    }

    public function redirectPaypalFile($user, $unitPrice, $creditsToBuy, $creditsForFile, $fileID){

        $this->gateway->setClientId($user->paypal_payment_account()->key);
        $this->gateway->setSecret($user->paypal_payment_account()->secret);

        if($user->test == 1){
            $this->gateway->setTestMode(true);
        }
        else{
            $this->gateway->setTestMode(false);
        }

        $tax = 0;

        if($user->group->tax > 0){
            $tax = (float) $user->group->tax;
        }

        $amount = $unitPrice*$creditsToBuy;

        $amount = $amount + ($tax * $amount)/100;

        // $configArr = config('paypal');

        // $configArr['live']['client_id'] = $user->paypal_payment_account()->key;
        // $configArr['live']['client_secret'] = $user->paypal_payment_account()->secret;

        // PayPal::setProvider();
        // $paypalProvider = PayPal::getProvider();
        // $paypalProvider->setApiCredentials($configArr);
        // $paypalProvider->setAccessToken($paypalProvider->getAccessToken());

        // $taxPer = 0;

        // if($user->group->tax > 0){
        //     $taxPer = (float) $user->group->tax;
        // }

        // $tax = ($taxPer * $unitPrice)/100;

        // $unitPrice = $unitPrice + $tax;

        // $price = $unitPrice*$creditsToBuy;

        // $data = [
        //     "intent"              => "CAPTURE",
        //     "purchase_units"      => [
        //         [
        //             "amount" => [
        //                 "value"         => number_format((float)$price, 2, '.', ''),
        //                 "currency_code" => 'EUR',
                        
        //             ],
        //             "category" => 'DIGITAL_GOODS',
        //         ],
        //     ],
        //     "application_context" => [
        //         "cancel_url" => route('checkout.cancel'),
        //         "return_url" => url("success?type=paypal&creditsForFile=".$creditsForFile."&file_id=".$fileID."&creditsToBuy=".$creditsToBuy),
        //     ],
        // ];

        // $order = $paypalProvider->createOrder($data);
        // if (isset($order['links'])){
        //     return redirect($order['links'][1]['href']);
        // }
        // else{
        //     return redirect()->route('shop-product')->with( 'success', 'sorry, something went wrong.');
        // }

        try {

            $response = $this->gateway->purchase(array(
                'amount' => $amount,
                'credits' => $creditsToBuy,
                'currency' => 'eur',
                'returnUrl' => url("success?type=paypal&creditsForFile=".$creditsForFile."&file_id=".$fileID."&creditsToBuy=".$creditsToBuy),
                'cancelUrl' => route('checkout.evc.cancel', [], true)
            ))->send();
                
            if ($response->isRedirect()) {
                $response->redirect();
            }
            else{
                dd($response);
                return redirect()->route('evc-credits-shop')->with( 'danger', $response->getMessage());

            }

        } catch (\Throwable $th) {
            return $th->getMessage();
        }

    }

    public function addCreditsEVC($user, $sessionID, $package, $type, $frontendID){

        $addCredits = false;

        if($type == 'stripe'){

            $account = $user->stripe_payment_account();
            
            \Stripe\Stripe::setApiKey($user->stripe_payment_account()->secret);

            try{

                $session = \Stripe\Checkout\Session::retrieve($sessionID);

                $stripeRecrod = new StripeRecord();
                $stripeRecrod->stripe_id = $session->id;
                $stripeRecrod->amount = $session->amount_total/100;
                $stripeRecrod->tax = 0;
                $stripeRecrod->desc = $session->id;
                // $stripeRecrod->credit_id = $record->id;
                $stripeRecrod->save();
                $addCredits = true;

                if (!$session) {
                    throw new NotFoundHttpException();
                }
            }

            catch(Exception $e){
                abort(404);
            }
            
            $customerStripe = \Stripe\Customer::retrieve($session->customer);

            $alreadyAdded = Credit::where('stripe_id', $session->id)->first();

        }
        else if($type == 'paypal'){

            if ($sessionID['paymentId'] && $sessionID['PayerID']) {
                $transaction = $this->gateway->completePurchase(array(
                    'payer_id' => $sessionID['PayerID'],
                    'transactionReference' => $sessionID['paymentId']
                ));
    
                $response = $transaction->send();

                if ($response->isSuccessful()) {
    
                    $ar = $response->getData();

                    Log::info($ar['transactions'][0]['related_resources'][0]['sale']['state']);

                    if($ar['transactions'][0]['related_resources'][0]['sale']['state'] == 'completed'){

                        $paypalRecord = new PaypalRecord();
                        $paypalRecord->paypal_id = $ar['id'];
                        $paypalRecord->email = $ar['payer']['payer_info']['email'];
                        $paypalRecord->amount = $ar['transactions'][0]['amount']['total'];
                        $paypalRecord->tax = 0;
                        $paypalRecord->desc = json_encode($ar);
                        $paypalRecord->save();

                        Log::info(json_encode($ar));
                        $account = $user->paypal_payment_account();
                        $alreadyAdded = Credit::where('stripe_id', $ar['id'])->first();
                        $sessionID = $ar['id'];

                        $addCredits = true;
                    }
                    else{

                        Log::info(json_encode($ar));
                        Log::info($ar['transactions'][0]['related_resources'][0]['sale']['state']);
                        Log::info($ar['transactions'][0]['related_resources'][0]['sale']['state']);
                        Log::info($ar['transactions'][0]['related_resources'][0]['sale']['state']);
                        Log::info($ar['transactions'][0]['related_resources'][0]['sale']['state']);

                        return redirect()->route('shop-product')->with( 'success', 'sorry, payment is not processed. Please contact support or Paypal.');
                    }

                }

            $account = $user->paypal_payment_account();
            $alreadyAdded = Credit::where('stripe_id', $sessionID)->first();
        }
            
        }
        else{
            $account = $user->paypal_payment_account();
            $alreadyAdded = Credit::where('stripe_id', $sessionID)->first();
            $addCredits = true;
        }

        if($addCredits){

            $price = $package->discounted_price;

            $tax = 0;
            $taxPer = 0;

            if($user->group->tax > 0){
                $taxPer = (float) $user->group->tax;
            }

            $tax = ($taxPer * $price)/100;

            $price = $price + $tax;

            $credit = NULL;

            if(!$alreadyAdded){

                $credit = new Credit();

                

                $credit->credits = $package->credits;
                $credit->type = $type;
                $credit->user_id = $user->id;
                $credit->customer = $user->name;
                $credit->email = $user->email;
                $credit->group_id = $user->group->id;
                $credit->group = $user->group->name;

                $credit->country = code_to_country( $user->country );

                if($type == 'stripe'){
                    $credit->stripe_id = $session->id;
                }
                else{
                    if($type == 'array'){
                        $credit->stripe_id = $sessionID['paymentId'];
                    }
                    else if($type == 'string'){
                        $credit->stripe_id = $sessionID;
                    }
                }

                if($user->test == 1){
                    $credit->test = 1;
                }

                $credit->is_package = true;
                $credit->front_end_id = $frontendID;
                $credit->price_payed = $package->discounted_price + $tax;
                $credit->price_without_tax = $package->discounted_price;
                $credit->tax = $tax;
                $credit->is_evc = 1;
                $credit->invoice_id = 'INV-E'.rand(100,999);
                $credit->save();

            }

            try{

                $response = Http::get('https://evc.de/services/api_resellercredits.asp?apiid=j34sbc93hb90&username=161134&password=MAgWVTqhIBitL0wn&verb=addcustomeraccount&type=stripe&customer='.$user->evc_customer_id.'&credits='.$credit->credits);
                $body = $response->body();
                $ok = substr($body, 0, 2);
            }
            catch(ConnectionException $e){
                // return false;
                // return redirect()->route('evc-credits-shop')->with('danger', 'EVC Credits not added!');
            }

            if($type == 'stripe'){
                $stripeRecrod->credit_id = $credit->id;
                $stripeRecrod->save();

                $this->makePaymentLogEntry( $credit->id, 'stripe', 'success', 'stripe payment worked', $stripeRecrod, $session );
            }
            else if($type == 'paypal'){
                $paypalRecord->credit_id = $credit->id;
                $paypalRecord->save();

                $this->makePaymentLogEntry( $credit->id, 'paypal', 'success', 'paypal payment worked', $paypalRecord, $ar );
            }

            return $credit;
        }
        else{
            return NULL;
        }
        // return true;
        // return redirect()->route('evc-credits-shop')->with('success', 'EVC Credits added, successfully.');
    }

    public function addCreditsPackage($user, $sessionID, $package, $type){

        $addCredits = false;

        if($type == 'stripe'){

            $account = $user->stripe_payment_account();
            
            \Stripe\Stripe::setApiKey($user->stripe_payment_account()->secret);

            try{
            
                $session = \Stripe\Checkout\Session::retrieve($sessionID);

                $stripeRecrod = new StripeRecord();
                $stripeRecrod->stripe_id = $session->id;
                $stripeRecrod->amount = $session->amount_total/100;
                $stripeRecrod->tax = 0;
                $stripeRecrod->desc = $session->id;
                // $stripeRecrod->credit_id = $record->id;
                $stripeRecrod->save();

                $addCredits = true;

                if (!$session) {
                    throw new NotFoundHttpException();
                }
            }

            catch(Exception $e){
                abort(404);
            }

            $customerStripe = \Stripe\Customer::retrieve($session->customer);

            $alreadyAdded = Credit::where('stripe_id', $session->id)->first();

        }
        else if($type == 'viva'){
            $account = $user->viva_payment_account();
            $alreadyAdded = Credit::where('stripe_id', $sessionID)->first();
            $addCredits = true;
        }
        else{

            $this->gateway->setClientId($user->paypal_payment_account()->key);
            $this->gateway->setSecret($user->paypal_payment_account()->secret);
            if($user->test == 1){
                $this->gateway->setTestMode(true);
            }
            else{
                $this->gateway->setTestMode(false);
            }

            if ($sessionID['paymentId'] && $sessionID['PayerID']) {
                $transaction = $this->gateway->completePurchase(array(
                    'payer_id' => $sessionID['PayerID'],
                    'transactionReference' => $sessionID['paymentId']
                ));
    
                $response = $transaction->send();

                if ($response->isSuccessful()) {
    
                    $ar = $response->getData();

                    Log::info($ar['transactions'][0]['related_resources'][0]['sale']['state']);

                    if($ar['transactions'][0]['related_resources'][0]['sale']['state'] == 'completed'){

                        $paypalRecord = new PaypalRecord();
                        $paypalRecord->paypal_id = $ar['id'];
                        $paypalRecord->email = $ar['payer']['payer_info']['email'];
                        $paypalRecord->amount = $ar['transactions'][0]['amount']['total'];
                        $paypalRecord->tax = 0;
                        $paypalRecord->desc = json_encode($ar);
                        $paypalRecord->save();

                        Log::info(json_encode($ar));
                        $account = $user->paypal_payment_account();
                        $alreadyAdded = Credit::where('stripe_id', $ar['id'])->first();
                        $sessionID = $ar['id'];

                        $addCredits = true;
                    }
                    else{

                        Log::info(json_encode($ar));
                        Log::info($ar['transactions'][0]['related_resources'][0]['sale']['state']);
                        Log::info($ar['transactions'][0]['related_resources'][0]['sale']['state']);
                        Log::info($ar['transactions'][0]['related_resources'][0]['sale']['state']);
                        Log::info($ar['transactions'][0]['related_resources'][0]['sale']['state']);

                        return redirect()->route('shop-product')->with( 'success', 'sorry, payment is not processed. Please contact support or Paypal.');
                    }
                }

            }

            $account = $user->paypal_payment_account();
            $alreadyAdded = Credit::where('stripe_id', $sessionID)->first();
            
        }

        if($addCredits){

            $price = $package->discounted_price;

            $tax = 0;
            $taxPer = 0;

            $floatTax = (float) $user->group->tax;

            if($floatTax > 0){
                $taxPer = $floatTax;
            }
            
            $tax = ($taxPer * $price)/100;

            $price = $price + $tax;

            $credit = NULL;

            if(!$alreadyAdded){

                $credit = new Credit();

                $credit->credits = $package->credits;
                $credit->type = $type;
                $credit->user_id = $user->id;
                $credit->customer = $user->name;
                $credit->email = $user->email;
                $credit->group_id = $user->group->id;
                $credit->group = $user->group->name;

                $credit->country = code_to_country( $user->country );

                if($user->test == 1){
                    $credit->test = 1;
                }

                if($type == 'stripe'){
                    $credit->stripe_id = $session->id;
                }
                else{
                    if($type == 'array'){
                        $credit->stripe_id = $sessionID['paymentId'];
                    }
                    else if($type == 'string'){
                        $credit->stripe_id = $sessionID;
                    }
                }

                $credit->is_package = true;
                $credit->front_end_id = $user->front_end_id;
                $credit->price_payed = $package->discounted_price + $tax;
                $credit->price_without_tax = $package->discounted_price;
                $credit->tax = $tax;
                $credit->invoice_id = 'INV-'.$account->prefix.rand(100,999);
                $credit->save();

            }

            if($type == 'stripe'){
                $stripeRecrod->credit_id = $credit->id;
                $stripeRecrod->save();
                $this->makePaymentLogEntry( $credit->id, 'stripe', 'success', 'stripe payment worked', $stripeRecrod, $session );
            }
            else if($type == 'paypal'){
                $paypalRecord->credit_id = $credit->id;
                $paypalRecord->save();
                $this->makePaymentLogEntry( $credit->id, 'paypal', 'success', 'paypal payment worked', $paypalRecord, $ar );
            }
            
            return $credit;
        }
        else{
            return NULL;
        }
    }

    public function addLiteralCredits(){
        
    }

    public function getEVCPackages(){

        return Package::where('active', 1)
        ->where('from_master_subdealer', 0)
        ->whereNull('subdealer_group_id')
        ->where('type', 'evc')->get();

    }
    
    // public function addCreditsWithFile($user, $sessionID, $creditsForFile, $creditsToBuy, $type){
        
    //     $price = Price::where('label', 'credit_price')->whereNull('subdealer_group_id')->first()->value;

    //     if($type == 'stripe'){

    //         $account = $user->stripe_payment_account();

    //         \Stripe\Stripe::setApiKey($user->stripe_payment_account()->secret);

    //         try{
            
    //             $session = \Stripe\Checkout\Session::retrieve($sessionID);
    //             if (!$session) {
    //                 throw new NotFoundHttpException();
    //             }
    //         }

    //         catch(Exception $e){
    //             abort(404);
    //         }

    //         // $customerStripe = \Stripe\Customer::retrieve($session->customer);
    //         $alreadyAdded = Credit::where('stripe_id', $session->id)->first();

    //     }
    //     else{

    //         $account = $user->paypal_payment_account();
    //         $alreadyAdded = Credit::where('stripe_id', $sessionID)->first();
    //     }


    // }

    public function addCredits($user, $sessionID, $credits, $type){

        $addCredits = false;
        
        $price = $this->getPrice()->value;

        if($type == 'stripe'){

            $account = $user->stripe_payment_account();

            \Stripe\Stripe::setApiKey($user->stripe_payment_account()->secret);

            // try{

            //     $session = \Stripe\Checkout\Session::retrieve($sessionID);
                
                $stripeRecrod = new StripeRecord();
                $stripeRecrod->stripe_id = $sessionID;
                $stripeRecrod->amount = 100;
                $stripeRecrod->tax = 0;
                $stripeRecrod->desc = $sessionID;
                // $stripeRecrod->credit_id = $record->id;
                $stripeRecrod->save();

            //     $addCredits = true;

            //     if (!$session) {
            //         throw new NotFoundHttpException();
            //     }
            // }

            // catch(Exception $e){
            //     abort(404);
            // }

            // $customerStripe = \Stripe\Customer::retrieve($session->customer);
            $alreadyAdded = Credit::where('stripe_id', $sessionID)->first();

        }
        else if($type == 'paypal'){

            $this->gateway->setClientId($user->paypal_payment_account()->key);
            $this->gateway->setSecret($user->paypal_payment_account()->secret);
            if($user->test == 1){
                $this->gateway->setTestMode(true);
            }
            else{
                $this->gateway->setTestMode(false);
            }

            if ($sessionID['paymentId'] && $sessionID['PayerID']) {
                $transaction = $this->gateway->completePurchase(array(
                    'payer_id' => $sessionID['PayerID'],
                    'transactionReference' => $sessionID['paymentId']
                ));
    
                $response = $transaction->send();

                if ($response->isSuccessful()) {
    
                    $ar = $response->getData();

                    Log::info($ar['transactions'][0]['related_resources'][0]['sale']['state']);

                    if($ar['transactions'][0]['related_resources'][0]['sale']['state'] == 'completed'){

                        $paypalRecord = new PaypalRecord();
                        $paypalRecord->paypal_id = $ar['id'];
                        $paypalRecord->email = $ar['payer']['payer_info']['email'];
                        $paypalRecord->amount = $ar['transactions'][0]['amount']['total'];
                        $paypalRecord->tax = 0;
                        $paypalRecord->desc = json_encode($ar);
                        $paypalRecord->save();

                        Log::info(json_encode($ar));
                        $account = $user->paypal_payment_account();
                        $sessionID = $ar['id'];
                        $alreadyAdded = Credit::where('stripe_id', $sessionID)->first();

                        $addCredits = true;

                    }
                    else{

                        Log::info(json_encode($ar));
                        Log::info($ar['transactions'][0]['related_resources'][0]['sale']['state']);
                        Log::info($ar['transactions'][0]['related_resources'][0]['sale']['state']);
                        Log::info($ar['transactions'][0]['related_resources'][0]['sale']['state']);
                        Log::info($ar['transactions'][0]['related_resources'][0]['sale']['state']);

                        return redirect()->route('shop-product')->with( 'success', 'sorry, payment is not processed. Please contact support or Paypal.');
                    }

                }

            }

        }
        else{

            $account = $user->paypal_payment_account();
            $alreadyAdded = Credit::where('stripe_id', $sessionID)->first();
            $addCredits = true;
        }

        if($addCredits){

            $totalPrice = $credits * $price;

            $taxPer = 0;
            $tax = 0;

            if($user->group->tax > 0){
                $taxPer = number_format( (float)$user->group->tax, 1);
                $tax = ($taxPer * $totalPrice)/100;
            }

            $pricePayed = $totalPrice + $tax;

            $credit = NULL;

            if($alreadyAdded == null){
                
                $credit = new Credit();

                $credit->credits = $credits;
                $credit->type = $type;
                $credit->user_id = $user->id;
                $credit->customer = $user->name;
                $credit->email = $user->email;
                $credit->group_id = $user->group->id;
                $credit->group = $user->group->name;

                $credit->country = code_to_country( $user->country );

                if($user->test == 1){
                    $credit->test = 1;
                }

                if($type == 'stripe'){
                    $credit->stripe_id = $sessionID;
                }
                else{

                    $sessionType = gettype($sessionID);

                    if($sessionType == 'array'){
                        $credit->stripe_id = $sessionID['paymentId'];
                    }
                    else if($sessionType == 'string'){
                        $credit->stripe_id = $sessionID;
                    }

                }

                $credit->front_end_id = $user->front_end_id;
                $credit->price_payed = $pricePayed;
                $credit->price_without_tax = $totalPrice;
                $credit->unit_price = $price;
                $credit->tax = $tax;
                $credit->invoice_id = 'INV-'.$account->prefix.rand(100,999);
                $credit->save();

            } 

            if($type == 'stripe'){

                $stripeRecrod->credit_id = $credit->id;
                $stripeRecrod->save();

                $this->makePaymentLogEntry( $credit->id, 'stripe', 'success', 'stripe payment worked', $stripeRecrod, $sessionID );
            }
            if($type == 'paypal'){

                $paypalRecord->credit_id = $credit->id;
                $paypalRecord->save();

                $this->makePaymentLogEntry( $credit->id, 'paypal', 'success', 'paypal payment worked', $paypalRecord, $ar );
            }
            
            return $credit;
        
        }
        else{
            return NULL;
        }
    }
    
    public function getPaypalTransaction(){

    }

    public function getPrice(){
        $user = Auth::user();
        return Price::where('label', 'credit_price')->whereNull('subdealer_group_id')
        ->where('front_end_id', $user->front_end_id)
        ->first();
    }

    public function getPackages($frontendID){
        return Package::where('active', 1)->where('front_end_id', $frontendID)->where('from_master_subdealer', 0)->where('type', 'service')->whereNull('subdealer_group_id')->get();
    }

    public function redirectPaypal($user, $price, $credits, $packageID = 0){

        $this->gateway->setClientId($user->paypal_payment_account()->key);
        $this->gateway->setSecret($user->paypal_payment_account()->secret);

        if($user->test == 1){
            $this->gateway->setTestMode(true);
        }
        else{
            $this->gateway->setTestMode(false);
        }

        // $configArr = config('paypal');

        // $configArr['live']['client_id'] = $user->paypal_payment_account()->key;
        // $configArr['live']['client_secret'] = $user->paypal_payment_account()->secret;

        // // dd($configArr);
        
        // PayPal::setProvider();
        // $paypalProvider = PayPal::getProvider();
        // $paypalProvider->setApiCredentials(config('paypal'));

        // // dd($paypalProvider);

        // dd($paypalProvider->getAccessToken());
        
        // $paypalProvider->setAccessToken($paypalProvider->getAccessToken());  
        
        

        if($packageID != 0){

                $package = Package::findOrFail($packageID);

                $total = $package->discounted_price;

                $tax = 0;

                if($user->group->tax > 0){
                    $tax = (float) $user->group->tax;
                }

                $total = $total + ($tax * $total)/100;

        //         $data = [
        //             "intent"              => "CAPTURE",
        //             "purchase_units"      => [
        //                 [
        //                     "amount" => [
        //                         "value"         => number_format((float)$total, 2, '.', ''),
        //                         "currency_code" => 'EUR',
                                
        //                     ],
        //                     "category" => 'DIGITAL_GOODS',
        //                 ],
        //             ],
        //             "application_context" => [
        //                 "cancel_url" => route('checkout.cancel'),
        //                 "return_url" => route('checkout.success') . "?credits=".$credits."&type=paypal&packageID=".$packageID,
        //             ],
        //         ];
    
        //         $order = $paypalProvider->createOrder($data);
        //         if (isset($order['links'])){
        //             return redirect($order['links'][1]['href']);
        //         }
        //         else{
        //             dd($order);
        //             return redirect()->route('shop-product')->with( 'success', 'sorry, something went wrong.');
        //         }
                
                try{

                $response = $this->gateway->purchase(array(
                    'amount' => $total,
                    'credits' => $credits,
                    'currency' => 'eur',
                    'returnUrl' => route('checkout.success') . "?credits=".$credits."&type=paypal&packageID=".$packageID,
                    'cancelUrl' => route('checkout.cancel', [], true)
                ))->send();
                
                if ($response->isRedirect()) {
                    $response->redirect();
                }
                else{
                    dd($response);
                    return redirect()->route('shop-product')->with( 'success', $response->getMessage());

                }

            } catch (\Throwable $th) {
                return $th->getMessage();
            }

        }
        
        else{

            $tax = 0;

            if($user->group->tax > 0){
                $tax = (float) $user->group->tax;
            }

            $price = $price + ($tax * $price)/100;

            $amount = $credits * $price;

            // $data = [
            //     "intent"              => "CAPTURE",
            //     "purchase_units"      => [
            //         [
            //             "amount" => [
            //                 "value"         => number_format((float)$amount, 2, '.', ''),
            //                 "currency_code" => 'EUR',
                            
            //             ],
            //             "category" => 'DIGITAL_GOODS',
            //         ],
            //     ],
            //     "application_context" => [
            //         "cancel_url" => route('checkout.cancel'),
            //         "return_url" => route('checkout.success') . "?credits=".$credits."&type=paypal&packageID=0",
            //     ],
            // ];

            // $order = $paypalProvider->createOrder($data);
            // if (isset($order['links'])){
            //     return redirect($order['links'][1]['href']);
            // }
            // else{
            //     return redirect()->route('shop-product')->with( 'success', 'sorry, something went wrong.');
            // }

            try {

                $amount = $credits * $price;
                $response = $this->gateway->purchase(array(
                    'amount' => $amount,
                    'credits' => $credits,
                    'currency' => 'eur',
                    'returnUrl' => route('checkout.success') . "?credits=".$credits."&type=paypal&packageID=0",
                    'cancelUrl' => route('checkout.cancel', [], true)
                ))->send();
                
                if ($response->isRedirect()) {
                    $response->redirect();
                }
                else{

                    dd($response);

                    return redirect()->route('shop-product')->with( 'success', $response->getMessage());

                }

            } catch (\Throwable $th) {
                return $th->getMessage();
            }

        }

    }

    public function redirectPaypalEVCPackage($user, $package){

        $this->gateway->setClientId($user->paypal_payment_account()->key);
        $this->gateway->setSecret($user->paypal_payment_account()->secret);

        if($user->test == 1){
            $this->gateway->setTestMode(true);
        }
        else{
            $this->gateway->setTestMode(false);
        }
        
        // $configArr = config('paypal');

        // $configArr['live']['client_id'] = $user->paypal_payment_account()->key;
        // $configArr['live']['client_secret'] = $user->paypal_payment_account()->secret;
        
        // PayPal::setProvider();
        // $paypalProvider = PayPal::getProvider();
        // $paypalProvider->setApiCredentials($configArr);
        // $paypalProvider->setAccessToken($paypalProvider->getAccessToken()); 

        $price = $package->discounted_price;

        $tax = 0;

        if($user->group->tax > 0){
            $tax = (float) $user->group->tax;
        }

        $price = $price + ($tax*$price)/100;

        // $data = [
        //     "intent"              => "CAPTURE",
        //     "purchase_units"      => [
        //         [
        //             "amount" => [
        //                 "value"         => number_format((float)$price, 2, '.', ''),
        //                 "currency_code" => 'EUR',
                        
        //             ],
        //             "category" => 'DIGITAL_GOODS',
        //         ],
        //     ],
        //     "application_context" => [
        //         "cancel_url" => route('checkout.cancel'),
        //         "return_url" => url('success_evc_packages?credits='.$package->credits.'&package='.$package->id.'&type=paypal'),
        //     ],
        // ];

        // $order = $paypalProvider->createOrder($data);
        // if (isset($order['links'])){
        //     return redirect($order['links'][1]['href']);
        // }
        // else{
        //     return redirect()->route('shop-product')->with( 'success', 'sorry, something went wrong.');
        // }

        try {

            $response = $this->gateway->purchase(array(
                'amount' => $price,
                'credits' => $package->credits,
                'currency' => 'eur',
                'returnUrl' => url('success_evc_packages?credits='.$package->credits.'&package='.$package->id.'&type=paypal'),
                'cancelUrl' => route('checkout.evc.cancel', [], true)
            ))->send();
                
            if ($response->isRedirect()) {
                $response->redirect();
            }
            else{
                dd($response);
                return redirect()->route('evc-credits-shop')->with( 'danger', $response->getMessage());

            }

        } catch (\Throwable $th) {
            return $th->getMessage();
        }

    }

    public function redirectStripeEVCPackage($user, $package){

        $price = $package->discounted_price;

        $tax = 0;

        if($user->group->tax > 0){
            $tax = (float) $user->group->tax;
        }

        $price = $price + ($tax*$price)/100;

        \Stripe\Stripe::setApiKey($user->stripe_payment_account()->secret);
        
        $lineItems = [];
        
        $lineItems[] = [
          'price_data' => [
              'currency' => 'eur',
              'product_data' => [
                'name' => "Tuning Credit(s)"
            ],
              'unit_amount' => $price * 100,
          ],
          'quantity' => 1,
        ];

        $uniqueKey=strtoupper(substr(sha1(microtime()), rand(0, 5), 20));  
        $uniqueKey  = implode("", str_split($uniqueKey, 5));

        $session = \Stripe\Checkout\Session::create([
            'client_reference_id' => ''.$uniqueKey.'',
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.evc.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}&packageID=".$package->id."&type=stripe",
            'cancel_url' => route('checkout.evc.cancel', [], true),
        ]);

        return redirect($session->url);

    }

    public function redirectStripe($user, $price, $credits, $packageID = 0){
       
        \Stripe\Stripe::setApiKey($user->stripe_payment_account()->secret);

        $tax = 0;

        if($user->group->tax > 0){
            $tax = (float) $user->group->tax;
        }

        $price = $price + ($tax * $price)/100;

        if($packageID != 0){

            $lineItems = [];
            
            $lineItems[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => "Tuning Credit(s)"
                ],
                'unit_amount' => $price * 100,
            ],
            'quantity' => 1,
            ];
            
            $uniqueKey=strtoupper(substr(sha1(microtime()), rand(0, 5), 20));  
            $uniqueKey  = implode("", str_split($uniqueKey, 5));

            try {
                $session = \Stripe\Checkout\Session::create([
                    'client_reference_id' => ''.$uniqueKey.'',
                    'line_items' => $lineItems,
                    'mode' => 'payment',
                    'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}&type=stripe&packageID=".$packageID,
                    'cancel_url' => route('checkout.cancel', [], true),
                ]);
            }
            catch(InvalidRequestException $e){
                return redirect()->route('shop-product', ['success' => "amount of credits can not be zero."]);
            }
        }

        else{

            $lineItems = [];
            
            $lineItems[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => "Tuning Credit(s)"
                ],
                'unit_amount' => $price * 100,
            ],
            'quantity' => $credits,
            ];

            $uniqueKey=strtoupper(substr(sha1(microtime()), rand(0, 5), 20));  
            $uniqueKey  = implode("", str_split($uniqueKey, 5));

            try {
                $session = \Stripe\Checkout\Session::create([
                    'client_reference_id' => ''.$uniqueKey.'',
                    'line_items' => $lineItems,
                    'mode' => 'payment',
                    'success_url' => route('checkout.success', [], true) . "?session_id={CHECKOUT_SESSION_ID}&credits=".$credits."&type=stripe&packageID=0",
                    'cancel_url' => route('checkout.cancel', [], true),
                ]);
            }
            catch(InvalidRequestException $e){
                return redirect()->route('shop-product', ['success' => "amount of credits can not be zero."]);
            }
        }

        return redirect($session->url);
    }
}

?>