<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Key;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Service;
use App\Models\ServiceSubdealerGroup;
use App\Models\Subdealer;
use App\Models\SubdealerGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class SubdealerGroupsController extends Controller
{
    private $token;

    public function __construct(){

        $this->middleware('auth');
    }

    public function addSubdealerGroupPrice(Request $request){
        
        dd($request->all());
    }

    public function getCreditsServiceGroup(Request $request){
        $record = ServiceSubdealerGroup::where('service_id', $request->service_id)
        ->where('subdealer_group_id', $request->subdealer_group_id)
        ->first();

        if($record)
            return $record->credits;
        else
            return 0;
    }

    public function setPrice($id){

        $subdealerGroups = SubdealerGroup::all();
        $service = Service::findOrFail($id);
        
        
        return view('subdealers.set_group_price', [

            'subdealerGroups' => $subdealerGroups, 
            'service' => $service
        ]);

    }

    public function groups(){

        $subdealerGroups = SubdealerGroup::all();
        return view('subdealers.groups', ['subdealerGroups' => $subdealerGroups]);

    }

    public function index(){

        $subdealers = Subdealer::all();
        return view('subdealers.index', ['subdealers' => $subdealers]);

    }

    public function editTokens($id){
        $alienTechKey = Key::where('subdealer_group_id', $id)
        ->where('key', 'alientech_access_token')->first();

        $sid = Key::where('subdealer_group_id', $id)
        ->where('key', 'twilio_sid')->first();

        $twilioToken = Key::where('subdealer_group_id', $id)
        ->where('key', 'twilio_token')->first();

        $twilioNumber = Key::where('subdealer_group_id', $id)
        ->where('key', 'twilio_number')->first();


        return view('subdealer_groups.edit_tokens', 
        [
            'subdealerID' => $id,
            'alienTechKey' => $alienTechKey,
            'sid' => $sid,
            'twilioToken' => $twilioToken,
            'twilioNumber' => $twilioNumber,
        
        ]);
        
    }

    public function updateTokens(Request $request){
        
        $alienTechKey = Key::where('subdealer_group_id', $request->id)
        ->where('key', 'alientech_access_token')->first();

        if($alienTechKey){
            
            $alienTechKey->key = 'alientech_access_token';
            $alienTechKey->value = $request->alientech_access_token;
            $alienTechKey->save();
        }
        else{
            $new = new Key();
            $new->subdealer_group_id = $request->id;
            $new->key = 'alientech_access_token';
            $new->value =  $request->alientech_access_token;
            $new->save();
        }

        $sid = Key::where('subdealer_group_id', $request->id)
        ->where('key', 'twilio_sid')->first();

        if($sid){
            
            $sid->key = 'twilio_sid';
            $sid->value = $request->twilio_sid;
            $sid->save();
        }
        else{
            $new = new Key();
            $new->subdealer_group_id = $request->id;
            $new->key = 'twilio_sid';
            $new->value =  $request->twilio_sid;
            $new->save();
        }

        $twilioToken = Key::where('subdealer_group_id', $request->id)
        ->where('key', 'twilio_token')->first();

        if($twilioToken){
            
            $twilioToken->key = 'twilio_token';
            $twilioToken->value = $request->twilio_token;
            $twilioToken->save();
        }
        else{
            $new = new Key();
            $new->subdealer_group_id = $request->id;
            $new->key = 'twilio_token';
            $new->value =  $request->twilio_token;
            $new->save();
        }

        $twilioNumber = Key::where('subdealer_group_id', $request->id)
        ->where('key', 'twilio_number')->first();

        if($twilioNumber){
            
            $twilioNumber->key = 'twilio_number';
            $twilioNumber->value = $request->twilio_number;
            $twilioNumber->save();
        }
        else{
            $new = new Key();
            $new->subdealer_group_id = $request->id;
            $new->key = 'twilio_number';
            $new->value =  $request->twilio_number;
            $new->save();
        }

        return redirect()->route('edit-tokens', ['id' => $request->id])->with(['success' => 'Token updated.']);

    }

    public function createGroup(){

        return view('subdealers.create_group');
        
    }

    public function create(){

        return view('subdealer_groups.create');
        
    }

    public function changePermission(Request $request){
        
        if($request->switchStatus == 'true'){
            $new = new Permission();
            $new->permission = $request->permission;
            $new->subdealer_group_id = $request->subdealer_group_id;
            $new->save();
        }
        else if($request->switchStatus == 'false'){
            Permission::where('permission', $request->permission)
            ->where('subdealer_group_id', $request->subdealer_group_id)->delete();
        }

        return response('permission updated', 200);
    }

    public function editPermissions($id){
        return view('subdealer_groups.edit_permission', ['subdealerID' => $id]);
        
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

    public function deleteGroup(Request $request){

        $subdealer = SubdealerGroup::findOrFail($request->id);
        $subdealer->delete();
    }

    public function delete(Request $request){

        $subdealer = Subdealer::findOrFail($request->id);
        $subdealer->delete();
    }

    public function edit($id){
        $subdealer = Subdealer::findOrFail($id);

        $customerID = Role::where('name', 'customer')->first()->id;
        $customers = User::where('subdealer_group_id', $id)->where('role_id', $customerID)->get();

        $engineerID = Role::where('name', 'engineer')->first()->id;
        $headID = Role::where('name', 'head')->first()->id;
        $engineers = User::where('subdealer_group_id', $id)
        ->where('role_id', $engineerID)
        ->orWhere('role_id', $headID)->where('subdealer_group_id', $id)
        ->get();

        $subdealerID = Role::where('name', 'subdealer')->first()->id;
        $subdealers = User::where('subdealer_group_id', $id)->where('role_id', $subdealerID)->get();

        return view('subdealers.create', 
        [   'subdealer' => $subdealer, 
            'customers' => $customers,
            'engineers' => $engineers,
            'subdealers' => $subdealers
        ]);
        
    }

    public function editGroup($id){
        
        $subdealerGroup = SubdealerGroup::findOrFail($id);
        return view('subdealers.create_group', 
        [   'subdealerGroup' => $subdealerGroup,
        ]);
        
    }

    public function updateGroup(Request $request){

        $subdealer = SubdealerGroup::findOrFail($request->id);
        $subdealer->name= $request->name;
        $subdealer->save();

        return redirect()->route('subdealer-groups')->with(['success' => 'Subdealer Group updated.']);

    }

    public function update(Request $request){

        $subdealer = Subdealer::findOrFail($request->id);
        $subdealer->name= $request->name;
        $subdealer->save();

        return redirect()->route('subdealers-entity')->with(['success' => 'Subdealer updated.']);

    }

    public function addGroup(Request $request){

        $subdealer = new SubdealerGroup();
        $subdealer->name= $request->name;
        $subdealer->save();

        return redirect()->route('subdealer-groups')->with(['success' => 'Subdealer Group added.']);

    }

    public function add(Request $request){

        $subdealer = new Subdealer();
        $subdealer->name= $request->name;
        $subdealer->save();

        return redirect()->route('subdealers-entity')->with(['success' => 'Subdealer added.']);

    }

}
