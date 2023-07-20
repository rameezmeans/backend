<?php

namespace App\Http\Controllers;

use App\Models\PaymentAccount;
use Illuminate\Http\Request;

class PaymentAccountsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
    public function index()
    {
        $accounts = PaymentAccount::whereNull('subdealer_group_id')->get();

        return view('payment_accounts.index', ['accounts' => $accounts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('payment_accounts.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $account = new PaymentAccount();
        $account->name = $request->name;
        $account->key = $request->key;
        $account->secret = $request->secret;
        $account->save();

        return redirect()->route('payment-accounts')->with('success', 'Account added, successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentAccount  $paymentAccount
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentAccount $paymentAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentAccount  $paymentAccount
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = PaymentAccount::where('id',$id)
        ->whereNull('subdealer_group_id')
        ->first();

        return view('payment_accounts.create', ['account' => $account]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentAccount  $paymentAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        $account = PaymentAccount::where('id',$request->id)
        ->whereNull('subdealer_group_id')
        ->first();

        $account->name = $request->name;
        $account->key = $request->key;
        $account->secret = $request->secret;
        $account->senders_name = $request->senders_name;
        $account->senders_phone_number = $request->senders_phone_number;
        $account->senders_address = $request->senders_address;
        $account->zip = $request->zip;
        $account->city = $request->city;
        $account->country = $request->country;
        $account->type = $request->type;
        $account->company_id = $request->company_id;
        $account->company = $request->company;
        $account->prefix = $request->prefix;
        $account->note = $request->note;

        if($request->elorus == 'on' || $request->elorus == 1){
            $account->elorus = 1;
        }
        else{
            $account->elorus = 0;
        }

        if(isset($request->companys_logo)){

            $file = $request->file('companys_logo');
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('company_logos'),$fileName);
            $account->companys_logo = $fileName;
        }

        $account->save();

        return redirect()->route('payment-accounts')->with('success', 'Account updated, successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentAccount  $paymentAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $account = PaymentAccount::findOrFail($request->id);
        $account->delete();
    }
}
