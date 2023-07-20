<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\PaymentAccount;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('adminOnly');
    }

    public function index(){
        $groups = Group::whereNull('subdealer_group_id')->get();
        return view('groups.groups', ['groups' => $groups]);
    }

    public function create(){
        return view('groups.groups_create_edit');
    }

    public function edit($id){

        $group = Group::where('id',$id)->whereNull('subdealer_group_id')->first();
        if(!$group){
            abort(404);
        }
        $stripeAccounts = PaymentAccount::whereNull('subdealer_group_id')->where('type','stripe')->get();
        $paypalAccounts = PaymentAccount::whereNull('subdealer_group_id')->where('type','paypal')->get();
        $paymentAccount = null;

        if($group->payment_account_id){
            $paymentAccount = PaymentAccount::findOrFail($group->payment_account_id);
        }
        
        return view('groups.groups_create_edit', [ 'paymentAccount' => $paymentAccount, 'group' => $group, 
        'stripeAccounts' => $stripeAccounts ,
        'paypalAccounts' => $paypalAccounts 
    ]);
    }

    public function add(Request $request){

        $validated = $request->validate([
            'name' => 'required|unique:groups|max:255|min:3',
            'tax' => 'required|numeric',
            'discount' =>  'required|numeric',
            'raise' => 'required|numeric',
            'bonus_credits' => 'required|numeric',
        ]);

        $group = new Group();
        $group->name = $validated['name'];
        $group->tax = $validated['tax'];
        $group->discount = $validated['discount'];
        $group->raise = $validated['raise'];
        $group->bonus_credits = $validated['bonus_credits'];
        $group->save();

        return redirect()->route('groups')->with(['success' => 'Group added, successfully.']);

    }

    public function update(Request $request){

        $group = Group::findOrFail($request->id);
        $group->name = $request->name;
        $group->tax = $request->tax;
        $group->discount = $request->discount;
        $group->raise = $request->raise;
        $group->bonus_credits = $request->bonus_credits;
        $group->stripe_payment_account_id = $request->stripe_payment_account_id;
        $group->paypal_payment_account_id = $request->paypal_payment_account_id;
        $group->save();

        return redirect()->route('groups')->with(['success' => 'Group updated, successfully.']);

    }

    public function delete(Request $request)
    {
        $group = Group::findOrFail($request->id);
        $group->delete();
        $request->session()->put('success', 'Group deleted, successfully.');

    }
}
