<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\FrontEnd;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Ui\Presets\React;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('adminOnly');
    }

    public function Customers(){
        $customers = User::where('is_customer', 1)->OrderBy('created_at', 'desc')->get();
        return view('groups.customers', ['customers' => $customers]);
    } 

    public function addCustomer(Request $request){

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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $customer = new User();
        $customer->name = $request->name;

        $customer->password = Hash::make($request->password);
       
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
        $customer->is_customer = 1;
        $customer->front_end_id = $request->front_end_id;

        $customer->save();

        $this->addCredits($customer->group->bonus_credits, $customer);

       return redirect()->route('customers')->with(['success' => 'Customer added, successfully.']);
    }

    public function createCustomer(){
        $groups = Group::all();
        $frontends = FrontEnd::all();
        return view('groups.create_edit_customers', ['groups' => $groups, 'frontends' => $frontends]);
    } 

    public function editCustomer($id){
        $customer = User::findOrFail($id);
        $groups = Group::all();
        $frontends = FrontEnd::all();
        return view('groups.create_edit_customers', ['customer' => $customer, 'groups' => $groups, 'frontends' => $frontends]);
    } 

    public function updateCustomer(Request $request){

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
            $customer->password = Hash::make(trim($request->password));
        }

        $customer->email = trim($request->email);
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
        $customer->front_end_id = $request->front_end_id;

        $customer->save();

        $this->addCredits($customer->group->bonus_credits, $customer);

        return redirect()->route('customers')->with(['success' => 'Customer updated, successfully.']);

    }

    public function addCredits($credits, $customer){
        $credit = new Credit();
        $credit->credits = $credits;
        $credit->user_id = $customer->id;
        $credit->stripe_id = NULL;
        $credit->price_payed = 0;
        $credit->invoice_id = 'Admin-'.mt_rand(1000,9999);
        $credit->save();
    }

    public function addEngineer(Request $request){
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $engineer = new User();
        $engineer->name = $request->name;
        $engineer->password = Hash::make($request->password);
        $engineer->email = $request->email;
        $engineer->phone = $request->phone;
        $engineer->language = "doesnot_matter";
        $engineer->address = $request->address;
        $engineer->zip = $request->zip;
        $engineer->city = $request->city;
        $engineer->country = $request->country;
        $engineer->status ="doesnot_matter";
        $engineer->company_name = "doesnot_matter";
        $engineer->company_id = "doesnot_matter";
        $engineer->is_customer = 0;
        $engineer->is_engineer = 1;
        $engineer->save();

       return redirect()->route('engineers')->with(['success' => 'Engineer added, successfully.']);

    }

    public function updateEngineer(Request $request){
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $engineer = User::findOrFail($request->id);
        $engineer->name = $request->name;

        if($request->password){
            $engineer->password = Hash::make($request->password);
        }
        
        $engineer->email = $request->email;
        $engineer->phone = $request->phone;
        $engineer->address = $request->address;
        $engineer->zip = $request->zip;
        $engineer->city = $request->city;
        $engineer->country = $request->country;
        $engineer->save();

       return redirect()->route('engineers')->with(['success' => 'Engineer Updated, successfully.']);

    }

    public function createEngineer(){
        return view('engineers.create_edit_engineers');
    }

    public function editEngineer($id){
        $engineer = User::findOrfail($id);
        return view('engineers.create_edit_engineers', ['engineer' => $engineer]);
    }

    public function Engineers(){
        $engineers = User::where('is_engineer', 1)->get();
        return view('engineers.engineers', ['engineers' => $engineers]);
    } 

    public function deleteCustomer(Request $request){
        $customer = User::findOrFail($request->id);
        $customer->delete();
        $request->session()->put('success', 'Customer deleted, successfully.');
    }

    public function deleteEngineer(Request $request){
        $engineer = User::findOrFail($request->id);
        $engineer->delete();
        $request->session()->put('success', 'Engineer deleted, successfully.');
    }
}
