<?php

namespace App\Http\Controllers;

use App\Models\PaymentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // $this->middleware('adminOnly');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'view-payment-accounts')){
            
            $accounts = PaymentAccount::whereNull('subdealer_group_id')->get();
            return view('payment_accounts.index', ['accounts' => $accounts]);
        }
        else{
            abort(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

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

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

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
        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-payment-accounts')){
        
            $account = PaymentAccount::where('id',$id)
            ->whereNull('subdealer_group_id')
            ->first();
            }
        
        else{
            return abort(404);
        }

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

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-payment-accounts')){
            
            $account = PaymentAccount::where('id',$request->id)
            ->whereNull('subdealer_group_id')
            ->first();

            $account->name = $request->name;
            $account->key = $request->key;
            $account->secret = $request->secret;

            $account->viva_client_id = $request->viva_client_id;
            $account->viva_merchant_id = $request->viva_merchant_id;
            $account->source_code = $request->source_code;
            $account->env = $request->env;

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
            $account->zohobooks_account_id = $request->zohobooks_account_id;

            if($request->elorus == 'on' || $request->elorus == 1){
                $account->elorus = 1;
            }
            else{
                $account->elorus = 0;
            }

            if($request->test == 'on' || $request->test == 1){
                $account->test = 1;
            }
            else{
                $account->test = 0;
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
        else{
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentAccount  $paymentAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        if(!Auth::user()->is_admin()){
            return abort(404);
        }

        $account = PaymentAccount::findOrFail($request->id);
        $account->delete();
    }
}
