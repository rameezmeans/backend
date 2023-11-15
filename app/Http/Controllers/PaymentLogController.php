<?php

namespace App\Http\Controllers;

use App\Models\PaymentLog;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function allPaymentLogs()
    {
        $allPaymentLogs = PaymentLog::orderBy('created_at', 'desc')->get();
        
        return view('payment_logs.all_logs', [
            'allPaymentLogs' => $allPaymentLogs,
            
        ]);
    }
}

