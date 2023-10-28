<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\PaymentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('adminOnly');
    }

    public function index(){

        if( Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'view-groups') ){
            
            $groups = Group::whereNull('subdealer_group_id')->get();
            return view('groups.groups', ['groups' => $groups]);
        }
        else{
            abort(404);
        }
    }

    public function create(){

        if(!Auth::user()->is_admin()){
            abort(404);
        }

        return view('groups.groups_create_edit');
    }

    public function edit($id){

        if( Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-groups') ){

                $group = Group::where('id',$id)->whereNull('subdealer_group_id')->first();

                if(!$group){
                    abort(404);
                }
                $stripeAccounts = PaymentAccount::whereNull('subdealer_group_id')->where('type','stripe')->get();
                $paypalAccounts = PaymentAccount::whereNull('subdealer_group_id')->where('type','paypal')->get();
                
                $stripePaymentAccount = null;
                $paypalPaymentAccount = null;

                if($group->stripe_payment_account_id){
                    $stripePaymentAccount = PaymentAccount::findOrFail($group->stripe_payment_account_id);
                }
                
                if($group->paypal_payment_account_id){
                    $paypalPaymentAccount = PaymentAccount::findOrFail($group->paypal_payment_account_id);
                }

                return view('groups.groups_create_edit', [ 
                    'stripePaymentAccount' => $stripePaymentAccount, 
                    'paypalPaymentAccount' => $paypalPaymentAccount,
                    'group' => $group, 
                    'stripeAccounts' => $stripeAccounts ,
                    'paypalAccounts' => $paypalAccounts 
                ]);
        }
        else{
            abort(404);
        }
    }

    public function add(Request $request){

        if(!Auth::user()->is_admin()){
            abort(404);
        }

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

        if( Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-groups') ){

            $group = Group::findOrFail($request->id);
            $group->name = $request->name;
            $group->tax = $request->tax;
            $group->discount = $request->discount;
            $group->raise = $request->raise;
            $group->bonus_credits = $request->bonus_credits;
            $group->stripe_payment_account_id = $request->stripe_payment_account_id;
            $group->paypal_payment_account_id = $request->paypal_payment_account_id;
            $group->elorus_template_id = $request->elorus_template_id;
            $group->elorus_tax_id = $request->elorus_tax_id;
            $group->save();

            return redirect()->route('groups')->with(['success' => 'Group updated, successfully.']);
        }
        else{
            abort(404);
        }

    }

    public function delete(Request $request)
    {

        if( Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'delete-groups') ){
            $group = Group::findOrFail($request->id);
            $group->delete();
            $request->session()->put('success', 'Group deleted, successfully.');
        }

    }
}
