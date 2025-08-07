<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\FrontEnd;
use App\Models\PaymentLog;
use App\Models\User;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use Svg\Tag\Rect;
use Yajra\DataTables\Facades\DataTables;

class PaymentLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('adminOnly');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customers()
    {
        $customers = User::where('front_end_id', 1)
        ->whereNot('name', 'Live Chat')
        ->whereNot('name', 'Live Chat Sub')
        ->whereNULL('subdealer_group_id')
        ->where('role_id', 4)->get();

        return view('payment_logs.customers', ['customers' => $customers]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function payments($id)
    {
        $customer = User::findOrFail($id);
        $allPayments = $customer->all_payments;
        
        return view('payment_logs.payments', [
            'allPayments' => $allPayments,
            'customer' => $customer
        ]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentLogs($id)
    {
        $customer = User::findOrFail($id);

        $paymentLogs = $customer->payment_logs;
        
        return view('payment_logs.logs', [
            'paymentLogs' => $paymentLogs,
            'customer' => $customer
        ]);
    }

    public function paymentDetails($id){

        $credit = Credit::findOrFail($id);
        $user = User::findOrFail($credit->user_id);
        $group = $user->group;

        return view('payment_logs.details', [
            'credit' => $credit,
            'user' => $user,
            'group' => $group,
        ]);
    }

    public function livePayments(){

        // $allPayments = Credit::orderBy('created_at', 'desc')->where('price_payed', '>', 0)->orWhere('gifted', 1)->where('credits', '>', 0)->get();
        
        return view('payment_logs.all_payments_live', [
            
            
        ]);   

    }

    public function refundPost(Request $request){

        $request->validate([
            'credit_id' => 'required|exists:credits,id',
        ]);

        $credit = Credit::findOrFail($request->credit_id);
        $user = User::findOrFail($credit->user_id);

        if($credit->type == 'stripe'){

        try {
            \Stripe\Stripe::setApiKey($user->stripe_payment_account()->secret);

            $session = \Stripe\Checkout\Session::retrieve($credit->stripe_id, [
                'expand' => ['payment_intent'],
            ]);

            $paymentIntent = $session->payment_intent;

            // Now expand the charges object
            $paymentIntent = \Stripe\PaymentIntent::retrieve(
                $paymentIntent,
                ['expand' => ['charges']]
            );

            $charge = $paymentIntent->latest_charge ?? null;
            
            if ($charge) {
                $refund = \Stripe\Refund::create([
                    'charge' => $charge,
                    // 'amount' => $request->amount * 100, // amount in cents
                ]);

                $credit->refund = 1;
                $credit->save();

                return back()->with('success', 'Refund processed successfully.');
            }

            return back()->with('error', 'Charge not found for refund.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }
    else{
        $gateway = Omnipay::create('PayPal_Rest');
        $gateway->setClientId($user->paypal_payment_account()->key);
        $gateway->setSecret($user->paypal_payment_account()->secret);

        if($user->test){
            $gateway->setTestMode(true); // or false in production
        }
        else{
            $gateway->setTestMode(false); 
        }

        $response = $gateway->refund([
            'transactionReference' => $credit->stripe_id,  // PayPal transaction ID
        ])->send();

        if ($response->isSuccessful()) {
        // Refund successful
            $refundData = $response->getData(); // contains refund info
            return back()->with('success', 'PayPal refund successful.');
        } else {
            // Refund failed
            return back()->with('error', 'Refund failed: ' . $response->getMessage());
        }
    }


    }

    public function refund($id){

        $credit = Credit::findOrFail($id);
        return view('payment_logs.refund', [
            'credit' => $credit,
            
        ]);
    }

    public function paymentsTable(Request $request){
            
            $data = Credit::select('*')->where('credits', '>', 0)->where('price_payed', '>', 0);

            if ($request->filled('from_date') && $request->filled('to_date')) {

                $data = $data->whereDate('created_at', '>=', $request->from_date)
                ->whereDate('created_at', '<=', $request->to_date);

            }

            if ($request->filled('frontend')) {
                if($request->frontend != 'all'){
                    $data = $data->where('front_end_id', '=', $request->frontend);
                }
            }
            
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('frontend', function($row){

                $frontEndID = $row->front_end_id;

                if($frontEndID == 1){
                    $btn = '<span class="label bg-primary text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else if($frontEndID == 2){
                    $btn = '<span class="label bg-warning">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }
                else if($frontEndID == 3){
                    $btn = '<span class="label bg-info text-white">'.FrontEnd::findOrFail($frontEndID)->name.'</span>';
                }

                return $btn;

        })

        ->editColumn('created_at', function ($credit) {
            return [
                'display' => e($credit->created_at->format('d-m-Y')),
                'timestamp' => $credit->created_at->timestamp
            ];
        })
        ->filterColumn('created_at', function ($query, $keyword) {
            $query->whereRaw("DATE_FORMAT(created_at,'%d-%m-%Y') LIKE ?", ["%$keyword%"]);
        })

        ->addColumn('created_time', function ($credit) {
                return $credit->created_at->format('h:i A');
        })

        ->addColumn('paypal_email', function($row){
            if($row->type == 'paypal'){
                return $row->paypal->email;
            }
            else{
                return 'Not Paypal';
            }
        })

        ->addColumn('details', function($row){
            return '<a class="btn btn-warning text-black" target="_blank" href="'.route("payment-details", $row->id).'">Payment Details</a>';
        })
        ->addColumn('elorus', function($row){
            if($row->elorus_permalink){
                return '<a class="btn btn-warning text-black" target="_blank" href="'.$row->elorus_permalink.'">Go To Elorus</a>';
            }
        })
        ->addColumn('zohobooks', function($row){
            if($row->zohobooks_id){
                return '<a class="btn btn-warning text-black" target="_blank" href="'.'https://books.zoho.com/app/8745725#/invoices/'.$row->zohobooks_id.'">Go To Zohobooks</a>';
            }
        })

        ->rawColumns(['frontend','type','details','elorus','zohobooks','created_time'])
        ->make(true);
    }

    public function allPayments(){

        // $allPayments = Credit::orderBy('created_at', 'desc')->where('price_payed', '>', 0)->orWhere('gifted', 1)->where('credits', '>', 0)->get();
        
        return view('payment_logs.all_payments_live', [
            
            // 'allPayments' => $allPayments,
        ]);   

    }

    public function allPaymentsAdmin(){
        $allPayments = Credit::orderBy('created_at', 'desc')->where('gifted', 1)->where('credits', '>', 0)->get();
        
        return view('payment_logs.all_payments', [
            'allPayments' => $allPayments,
            
        ]);
    }

    public function paymentLogsTable(Request $request){

        $data = PaymentLog::select('*')->orderBy('created_at', 'desc');

        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('db_id', function($row){

            return \App\Models\Credit::findOrFail($row->payment_id)->id;

        })
        ->addColumn('invoice_id', function($row){

            return \App\Models\Credit::findOrFail($row->payment_id)->invoice_id;

        })

        ->editColumn('created_at', function ($row) {
            return [
                'display' => e($row->created_at->format('d-m-Y')),
                'timestamp' => $row->created_at->timestamp
            ];
        })
        ->filterColumn('created_at', function ($query, $keyword) {
            $query->whereRaw("DATE_FORMAT(created_at,'%d-%m-%Y') LIKE ?", ["%$keyword%"]);
        })

        ->addColumn('customer', function($row){

            return \App\Models\User::findOrFail($row->user_id)->name;

        })

        ->addColumn('email', function($row){

            return \App\Models\User::findOrFail($row->user_id)->email;

        })

        ->addColumn('group', function($row){

            if(\App\Models\User::findOrFail($row->user_id)->group != NULL){
                return \App\Models\User::findOrFail($row->user_id)->group->name;
            }
            else{
                return 'No Group';
            }
            

        })

        ->addColumn('credits', function($row){

            return \App\Models\Credit::findOrFail($row->payment_id)->credits;

        })

        ->addColumn('price_payed', function($row){

            return \App\Models\Credit::findOrFail($row->payment_id)->price_payed;

        })

        ->addColumn('elorus', function($row){

            if($row->elorus_id){
                return '<a class="btn btn-warning text-black" target="_blank" href="'.\App\Models\Credit::findOrFail($row->payment_id)->elorus_permalink.'">To Elorus</a>';
            }
            else {
                return 'No Elorus';
            }

        })

        ->addColumn('zohobooks', function($row){

            if($row->elorus_id){
                return '<a class="btn btn-warning text-black" target="_blank" href="https://books.zoho.com/app/8745725#/invoices/'.$row->zohobooks_id.'">To Zohobooks</a>';
            }
            else {
                return 'No Zohobooks';
            }

        })

        ->addColumn('email_sent', function($row){

            if($row->email_sent){
                return 'Yes';
            }
            else{
                return 'No';
            }

        })

        
        ->rawColumns(['db_id','invoice_id', 'email_sent', 'zohobooks', 'elorus', 'price_payed', 'credits', 'group', 'email', 'customer'])
        ->make(true);

    }

    public function allPaymentLogs()
    {
        $allPaymentLogs = PaymentLog::orderBy('created_at', 'desc')->get();
        
        return view('payment_logs.all_logs', [
            'allPaymentLogs' => $allPaymentLogs,
            
        ]);
    }
}

