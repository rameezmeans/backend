<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function customers(){
        $customers = User::where('is_customer', 1)->get();
        return view('groups.customers', ['customers' => $customers]);
    } 

    public function create_customer(){
        return view('groups.create_edit_customers');
    } 

    public function edit_customer($id){
        $customer = User::findOrFail($id);
        $groups = Group::all();
        return view('groups.create_edit_customers', ['customer' => $customer, 'groups' => $groups]);
    } 

    public function update_customer(Request $request){

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'language' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'zip' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'company_id' => ['string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

       $customer = User::findOrFail($request->id);
       $customer->name = $request->name;

       if($request->password){
           $customer->password = Hash::make($request->password);
       }

       $customer->email = $request->email;
       $customer->phone = $request->phone;
       $customer->language = $request->language;
       $customer->address = $request->address;
       $customer->zip = $request->zip;
       $customer->city = $request->city;
       $customer->country = $request->country;
       $customer->status = $request->status;
       $customer->company_name = $request->company_name;
       $customer->company_id = $request->company_id;
       $customer->group_id = $request->group_id;

       $customer->save();

       return redirect()->route('customers')->with(['success' => 'Service updated, successfully.']);

    }
}
