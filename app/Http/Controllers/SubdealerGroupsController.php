<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\SubdealerGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class SubdealerGroupsController extends Controller
{
    private $token;

    public function __construct(){

        $this->middleware('auth');
    }

    public function index(){

        $subdealers = SubdealerGroup::all();
        return view('subdealer_groups.index', ['subdealers' => $subdealers]);

    }

    public function create(){

        return view('subdealer_groups.create');
        
    }

    public function createCustomer($subdealerID){

        return view('subdealer_groups.create_customer', ['subdealerID' => $subdealerID]);
        
    }

    public function editCustomer($id){
        $customer = User::findOrFail($id);
        $subdealerID = $customer->subdealer_group_id;
        return view('subdealer_groups.create_customer', ['customer' => $customer, 'subdealerID' => $subdealerID]);
    } 

    public function editEngineer($id){
        $engineer = User::findOrFail($id);
        $subdealerID = $engineer->subdealer_group_id;
        return view('subdealer_groups.create_engineer', ['engineer' => $engineer, 'subdealerID' => $subdealerID]);
    } 

    public function editSubdealer($id){
        $subdealer = User::findOrFail($id);
        $subdealerID = $subdealer->subdealer_group_id;
        return view('subdealer_groups.create_subdealer', ['subdealer' => $subdealer, 'subdealerID' => $subdealerID]);
    } 

    public function updateCustomer(Request $request){
        
        $customer = User::findOrFail($request->id);

        if($customer->email == $request->email){

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

        }
        else{

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
            ]);

        }

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
        
        $customer->save();

        return redirect()->route('edit-subdealer-group', ['id' => $customer->subdealer_group_id])->with(['success' => 'Customer added, successfully.']);

    }

    public function updateEngineer(Request $request){

        $engineer = User::findOrFail($request->id);

        if($engineer->email == $request->email){
        
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'country' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
            ]);
            
        }
        else{

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'country' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
        }
        
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

        $subdealerID = $request->subdealer_id;

        return redirect()->route('edit-subdealer-group', ['id' => $subdealerID])->with(['success' => 'Engineer Updated, successfully.']);
    
    }

    public function updateSubdealer(Request $request){

        $subdealer = User::findOrFail($request->id);

        if($subdealer->email == $request->email){
        
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'country' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
            ]);
            
        }
        else{

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'address' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'country' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
        }
        
        $subdealer->name = $request->name;

        if($request->password){
            $subdealer->password = Hash::make($request->password);
        }

        $subdealer->email = $request->email;
        $subdealer->phone = $request->phone;
        $subdealer->address = $request->address;
        $subdealer->zip = $request->zip;
        $subdealer->city = $request->city;
        $subdealer->country = $request->country;
        $subdealer->save();

        $subdealerID = $request->subdealer_id;

        return redirect()->route('edit-subdealer-group', ['id' => $subdealerID])->with(['success' => 'Engineer Updated, successfully.']);
    
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

        $customerID = Role::where('name', 'customer')->first()->id;
        
        $subdealerID = $request->subdealer_id;

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
        $customer->front_end_id = $request->front_end_id;
        $customer->role_id = $customerID;
        $customer->subdealer_group_id = $subdealerID;
        $customer->save();

        return redirect()->route('edit-subdealer-group', ['id' => $subdealerID])->with(['success' => 'Customer added, successfully.']);

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

        $subdealerID = $request->subdealer_id;

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
        $engineer->front_end_id = NULL;

        $engineerID = Role::where('name', 'engineer')->first()->id;
        $engineer->role_id = $engineerID;
        $engineer->subdealer_group_id = $subdealerID;
        $engineer->save();

       return redirect()->route('edit-subdealer-group', ['id' => $subdealerID])->with(['success' => 'Engineer added, successfully.']);

    }

    public function addSubdealer(Request $request){
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $subdealerID = $request->subdealer_id;

        $subdealer = new User();
        $subdealer->name = $request->name;
        $subdealer->password = Hash::make($request->password);
        $subdealer->email = $request->email;
        $subdealer->phone = $request->phone;
        $subdealer->language = "doesnot_matter";
        $subdealer->address = $request->address;
        $subdealer->zip = $request->zip;
        $subdealer->city = $request->city;
        $subdealer->country = $request->country;
        $subdealer->status ="doesnot_matter";
        $subdealer->company_name = "doesnot_matter";
        $subdealer->company_id = "doesnot_matter";
        $subdealer->front_end_id = NULL;

        $engineerID = Role::where('name', 'subdealer')->first()->id;
        $subdealer->role_id = $engineerID;
        $subdealer->subdealer_group_id = $subdealerID;
        $subdealer->save();

       return redirect()->route('edit-subdealer-group', ['id' => $subdealerID])->with(['success' => 'Engineer added, successfully.']);

    }

    public function createEngineer($subdealerID){
        return view('subdealer_groups.create_engineer', ['subdealerID' => $subdealerID]);
    }

    public function createSubdealer($subdealerID){

        return view('subdealer_groups.create_subdealer', ['subdealerID' => $subdealerID]);
        
    }

    public function deleteUser(Request $request){

        $subdealer = User::findOrFail($request->id);
        $subdealer->delete();
    }

    public function delete(Request $request){

        $subdealer = SubdealerGroup::findOrFail($request->id);
        $subdealer->delete();
    }

    public function edit($id){
        $subdealer = SubdealerGroup::findOrFail($id);

        $customerID = Role::where('name', 'customer')->first()->id;
        $customers = User::where('subdealer_group_id', $id)->where('role_id', $customerID)->get();

        $engineerID = Role::where('name', 'engineer')->first()->id;
        $engineers = User::where('subdealer_group_id', $id)->where('role_id', $engineerID)->get();

        $subdealerID = Role::where('name', 'subdealer')->first()->id;
        $subdealers = User::where('subdealer_group_id', $id)->where('role_id', $subdealerID)->get();

        return view('subdealer_groups.create', 
        [   'subdealer' => $subdealer, 
            'customers' => $customers,
            'engineers' => $engineers,
            'subdealers' => $subdealers
        ]);
        
    }

    public function update(Request $request){

        $subdealer = SubdealerGroup::findOrFail($request->id);
        $subdealer->name= $request->name;
        $subdealer->save();

        return redirect()->route('subdealer-groups')->with(['success' => 'Subdealer updated.']);

    }

    public function add(Request $request){

        $subdealer = new SubdealerGroup();
        $subdealer->name= $request->name;
        $subdealer->save();

        return redirect()->route('subdealer-groups')->with(['success' => 'Subdealer added.']);

    }

}
