<?php

namespace App\Http\Controllers;

use App\Models\PaymentAccount;
use Illuminate\Http\Request;

class PaymentAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = PaymentAccount::all();

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
        $account = PaymentAccount::findOrFail($id);
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
        $account = PaymentAccount::findOrFail($request->id);
        $account->name = $request->name;
        $account->key = $request->key;
        $account->secret = $request->secret;
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
