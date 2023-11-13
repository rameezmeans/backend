<?php

namespace App\Http\Controllers;

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
}
