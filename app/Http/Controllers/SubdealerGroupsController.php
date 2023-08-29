<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Group;
use App\Models\Key;
use App\Models\MessageTemplate;
use App\Models\PaymentAccount;
use App\Models\Permission;
use App\Models\Price;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Service;
use App\Models\ServiceSubdealerGroup;
use App\Models\Subdealer;
use App\Models\SubdealerGroup;
use App\Models\SubdealersData;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class SubdealerGroupsController extends Controller
{
    private $token;

    public function __construct(){

        $this->middleware('auth');
    }

    public function addSubdealerGroupPrice(Request $request){

        $record = ServiceSubdealerGroup::where('service_id', $request->service_id)->first();
        
        if($record){
        
            $record->master_credits = $request->credits;
            $record->active = 1;
            $record->save();
        }
        else{

            $service = Service::findOrFail($request->service_id);

            $newRecord = new ServiceSubdealerGroup();
            $newRecord->service_id = $service->id;
            $newRecord->master_credits = $request->credits;
            $newRecord->subdealer_credits = $service->credits;
            $newRecord->active = 1;
            $newRecord->save();

        }
        return Redirect::back()->with(['success' => 'Subdealer credits changed.']);

    }

    public function getCreditsServiceGroup(Request $request){

        $group = SubdealerGroup::findOrFail($request->subdealer_own_group_id)->first();
        
        foreach($group->subdealers as $dealer){
            
            $record = ServiceSubdealerGroup::where('subdealer_group_id', $dealer->subdealer_group_id)
            ->where('service_id', $request->service_id)->first();
            
            return $record->master_credits;
        }
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

        $evcUsername = Key::where('subdealer_group_id', $id)
        ->where('key', 'evc_username')->first();

        $evcPassword = Key::where('subdealer_group_id', $id)
        ->where('key', 'evc_password')->first();

        return view('subdealers.edit_tokens', 
        [
            'subdealerID' => $id,
            'alienTechKey' => $alienTechKey,
            'sid' => $sid,
            'twilioToken' => $twilioToken,
            'twilioNumber' => $twilioNumber,
            'evcUsername' => $evcUsername,
            'evcPassword' => $evcPassword,
        
        ]);
        
    }

    public function updateMasterTokens(Request $request){
        
        $alienTechKey = Key::whereNull('subdealer_group_id')
        ->where('key', 'alientech_access_token')->first();

        if($alienTechKey){
            
            $alienTechKey->key = 'alientech_access_token';
            $alienTechKey->value = $request->alientech_access_token;
            $alienTechKey->save();
        }
        else{
            $new = new Key();
            $new->key = 'alientech_access_token';
            $new->value =  $request->alientech_access_token;
            $new->save();
        }

        $sid = Key::whereNull('subdealer_group_id')
        ->where('key', 'twilio_sid')->first();

        if($sid){
            
            $sid->key = 'twilio_sid';
            $sid->value = $request->twilio_sid;
            $sid->save();
        }
        else{
            $new = new Key();
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
            $new->key = 'twilio_token';
            $new->value =  $request->twilio_token;
            $new->save();
        }

        $twilioNumber = Key::whereNull('subdealer_group_id')
        ->where('key', 'twilio_number')->first();

        if($twilioNumber){
            
            $twilioNumber->key = 'twilio_number';
            $twilioNumber->value = $request->twilio_number;
            $twilioNumber->save();
        }
        else{
            $new = new Key();
            $new->key = 'twilio_number';
            $new->value =  $request->twilio_number;
            $new->save();
        }

        $stripeKey = Key::whereNull('subdealer_group_id')
        ->where('key', 'stripe_key')->first();

        if($stripeKey){
            
            $stripeKey->key = 'stripe_key';
            $stripeKey->value = $request->stripe_key;
            $stripeKey->save();
        }
        else{
            $new = new Key();
            $new->key = 'stripe_key';
            $new->value =  $request->stripe_key;
            $new->save();
        }

        $stripeSecret = Key::whereNull('subdealer_group_id')
        ->where('key', 'stripe_secret')->first();

        if($stripeSecret){
            
            $stripeKey->key = 'stripe_secret';
            $stripeKey->value = $request->stripe_secret;
            $stripeKey->save();
        }
        else{
            $new = new Key();
            $new->key = 'stripe_secret';
            $new->value =  $request->stripe_secret;
            $new->save();
        }

        return redirect()->route('edit-master-tokens')->with(['success' => 'Token updated.']);

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

        $evcUsername = Key::where('subdealer_group_id', $request->id)
        ->where('key', 'evc_username')->first();

        if($evcUsername){
            
            $evcUsername->key = 'evc_username';
            $evcUsername->value = $request->evc_username;
            $evcUsername->save();
        }
        else{
            $new = new Key();
            $new->subdealer_group_id = $request->id;
            $new->key = 'evc_username';
            $new->value =  $request->evc_username;
            $new->save();
        }

        $evcPassword = Key::where('subdealer_group_id', $request->id)
        ->where('key', 'evc_password')->first();

        if($evcPassword){
            
            $evcPassword->key = 'evc_password';
            $evcPassword->value = $request->evc_password;
            $evcPassword->save();
        }
        else{
            $new = new Key();
            $new->subdealer_group_id = $request->id;
            $new->key = 'evc_password';
            $new->value =  $request->evc_password;
            $new->save();
        }

        try{

                $response = Http::get('https://evc.de/services/api_resellercredits.asp?apiid=j34sbc93hb90&username=161134&password=MAgWVTqhIBitL0wn&verb=addcustomer&customer='.$request->evc_username);

                $body = $response->body();

                $ok = substr($body, 0, 2);
                
                if($ok == 'ok'){
                    return redirect()->back()->with('success', 'Tokens updated and EVC account is created, successfully!');
                }
                else{
                    return redirect()->back()->with('success', 'Tokens updated but EVC function remained unchanged!');
                }

            }

            catch(ConnectionException $e){
                return redirect()->back()->with('danger', 'Tokens updated and EVC account is not created!');
            }

            return redirect()->back()->with('success', 'Tokens edited, successfully!');
            
    }

    public function createGroup(){

        return view('subdealers.create_group');
        
    }

    public function create(){

        $subdealerTypes = [
            'lazy' => 'Lazy',
            'brainiac' => 'Brainiac',
            'smart' => 'Smart',
        ];

        return view('subdealers.create', [ 'subdealerTypes' => $subdealerTypes ]);
        
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
        return view('subdealers.edit_permission', ['subdealerID' => $id]);
        
    }

    public function createCustomer($subdealerID){

        $groups = Group::where('subdealer_group_id', $subdealerID)->get();
        return view('subdealers.create_customer', ['subdealerID' => $subdealerID, 'groups' => $groups]);
        
    }

    public function editCustomer($id){
        
        $customer = User::findOrFail($id);
        $subdealerID = $customer->subdealer_group_id;
        $groups = Group::where('subdealer_group_id', $subdealerID)->get();
        return view('subdealers.create_customer', ['customer' => $customer, 'subdealerID' => $subdealerID, 'groups' => $groups]);
    } 

    public function editEngineer($id){
        $engineer = User::findOrFail($id);
        $subdealerID = $engineer->subdealer_group_id;
        return view('subdealers.create_engineer', ['engineer' => $engineer, 'subdealerID' => $subdealerID]);
    } 

    public function editSubdealer($id){
        $subdealer = User::findOrFail($id);
        $subdealerID = $subdealer->subdealer_group_id;
        $subdealerGroups = SubdealerGroup::all();
        return view('subdealers.create_subdealer', ['subdealerGroups' => $subdealerGroups, 'subdealer' => $subdealer, 'subdealerID' => $subdealerID]);
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

        return redirect()->route('edit-subdealer-entity', ['id' => $customer->subdealer_group_id])->with(['success' => 'Customer added, successfully.']);

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

        return redirect()->route('edit-subdealer-entity', ['id' => $subdealerID])->with(['success' => 'Engineer Updated, successfully.']);
    
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
        $subdealer->subdealer_own_group_id = $request->subdealer_own_group_id;

        if($request->exclude_vat_check == 'on'){
            $subdealer->exclude_vat_check = 1;
        }
        else{
            $subdealer->exclude_vat_check = 0;
        }
        
        $subdealer->save();
        
        $subdealerID = $request->subdealer_id;

        return redirect()->route('edit-subdealer-entity', ['id' => $subdealerID])->with(['success' => 'Subdealer Updated, successfully.']);
    
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

        return redirect()->route('edit-subdealer-entity', ['id' => $subdealerID])->with(['success' => 'Customer added, successfully.']);

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
        $engineer->language = 'English';
        $engineer->address = $request->address;
        $engineer->zip = $request->zip;
        $engineer->city = $request->city;
        $engineer->country = $request->country;
        $engineer->status = 'private';
        $engineer->company_name = NULL;
        $engineer->company_id = NULL;
        $engineer->front_end_id = NULL;

        $engineerID = Role::where('name', 'engineer')->first()->id;
        $headID = Role::where('name', 'head')->first()->id;

        $firstEngineer = User::where('role_id', $headID)
        ->where('subdealer_group_id', $subdealerID)
        ->first();

        if($firstEngineer){
            $engineer->role_id = $engineerID;
        }
        else{
            $engineer->role_id = $headID;
        }

        $engineer->subdealer_group_id = $subdealerID;
        $engineer->save();

       return redirect()->route('edit-subdealer-entity', ['id' => $subdealerID])->with(['success' => 'Engineer added, successfully.']);

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
        $subdealer->language = "English";
        $subdealer->status = "company";
        $subdealer->address = $request->address;
        $subdealer->zip = $request->zip;
        $subdealer->city = $request->city;
        $subdealer->country = $request->country;
        $subdealer->company_name = NULL;
        $subdealer->company_id = NULL;
        $subdealer->front_end_id = NULL;

        $engineerID = Role::where('name', 'subdealer')->first()->id;
        $subdealer->role_id = $engineerID;
        $subdealer->subdealer_group_id = $subdealerID;
        $subdealer->subdealer_own_group_id = $request->subdealer_own_group_id;

        $subdealer->save();

       return redirect()->route('edit-subdealer-entity', ['id' => $subdealerID])->with(['success' => 'Engineer added, successfully.']);

    }

    public function createEngineer($subdealerID){
        return view('subdealers.create_engineer', ['subdealerID' => $subdealerID]);
    }

    public function createSubdealer($subdealerID){
        $subdealerGroups = SubdealerGroup::all();
        return view('subdealers.create_subdealer', ['subdealerID' => $subdealerID, 'subdealerGroups' => $subdealerGroups]);
        
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

        $subdealerTypes = [
            'lazy' => 'Lazy',
            'brainiac' => 'Brainiac',
            'smart' => 'Smart',
        ];
        
        $subdealerID = Role::where('name', 'subdealer')->first()->id;
        $subdealers = User::where('subdealer_group_id', $id)->where('role_id', $subdealerID)->get();

        return view('subdealers.create', 
        [   'subdealer' => $subdealer, 
            'customers' => $customers,
            'engineers' => $engineers,
            'subdealers' => $subdealers,
            'subdealerTypes' => $subdealerTypes
        ]);
        
    }

    public function editGroup($id, Request $request){

        $subdealerGroup = SubdealerGroup::findOrFail($id);

        $stripeAccounts = PaymentAccount::where('type','stripe')->get();
        $paypalAccounts = PaymentAccount::where('type','paypal')->get();
        $stripePaymentAccount = null;
        $paypalPaymentAccount = null;
        
        if($subdealerGroup->stripe_payment_account_id){
            $stripePaymentAccount = PaymentAccount::findOrFail($subdealerGroup->stripe_payment_account_id);
        }

        $paypalPaymentAccount = null;

        if($subdealerGroup->paypal_payment_account_id){
            $paypalPaymentAccount = PaymentAccount::findOrFail($subdealerGroup->paypal_payment_account_id);
        }
        
        return view('subdealers.create_group', 
        
        [   'stripePaymentAccount' => $stripePaymentAccount, 
        'paypalPaymentAccount' => $paypalPaymentAccount,
        'subdealerGroup' => $subdealerGroup, 
        'stripeAccounts' => $stripeAccounts ,
        'paypalAccounts' => $paypalAccounts 
        ]);
        
    }

    public function updateGroup(Request $request){

        $subdealerGroup = SubdealerGroup::findOrFail($request->id);
        $subdealerGroup->tax = $request->tax;
        $subdealerGroup->name= $request->name;
        $subdealerGroup->stripe_payment_account_id= $request->stripe_payment_account_id;
        $subdealerGroup->paypal_payment_account_id= $request->paypal_payment_account_id;
        $subdealerGroup->save();

        return redirect()->route('subdealer-groups')->with(['success' => 'Subdealer Group updated.']);

    }

    public function update(Request $request){

        $subdealer = Subdealer::findOrFail($request->id);
        $subdealer->name= $request->name;
        $subdealer->save();

        if(!$subdealer->subdealers_data){

            $subdealerData = new SubdealersData();
            $subdealerData->frontend_url = $request->frontend_url;
            $subdealerData->backend_url = $request->backend_url;
            $subdealerData->colour_scheme = $request->colour_scheme;
            $subdealerData->subdealer_id = $subdealer->id;
            $subdealerData->lua_search_charges = $request->lua_search_charges;
            $subdealerData->type = $request->type;

            if($request->file('logo')){
                $file = $request->file('logo');
                $fileName = $file->getClientOriginalName();
                $file->move(public_path('icons'),$fileName);
                $subdealerData->logo = $fileName;
            }

            $subdealerData->save();

        }
        else{

            $subdealerData = SubdealersData::findOrFail($subdealer->subdealers_data->id);

            $subdealerData->frontend_url = $request->frontend_url;
            $subdealerData->backend_url = $request->backend_url;
            $subdealerData->colour_scheme = $request->colour_scheme;
            $subdealerData->lua_search_charges = $request->lua_search_charges;
            $subdealerData->type = $request->type;
            $subdealerData->subdealer_id = $subdealer->id;

            if($request->file('logo')){
                $file = $request->file('logo');
                $fileName = $file->getClientOriginalName();
                $file->move(public_path('icons'),$fileName);
                $subdealerData->logo = $fileName;
            }

            $subdealerData->save();
        }
        
        return redirect()->back()->with(['success' => 'Subdealer updated.']);

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

        $data = new SubdealersData();
        $data->lua_search_charges = $request->lua_search_charges;
        $data->subdealer_id = $subdealer->id;
        $data->type = $request->type;
        $data->frontend_url = $request->frontend_url;
        $data->backend_url = $request->backend_url;
        $data->colour_scheme = $request->colour_scheme;

        if($request->file('logo')){
            $file = $request->file('logo');
            $fileName = $file->getClientOriginalName();
            $file->move(public_path('icons'),$fileName);
            $data->logo = $fileName;
        }
        else{
            $data->logo = "test.png";
        }

        $data->save();

        $alientTechKey = new Key();
        $alientTechKey->key = 'alientech_access_token';
        $alientTechKey->value = '9fSkh2kilF8';
        $alientTechKey->subdealer_group_id = $subdealer->id;
        $alientTechKey->save();

        $alientTechKey = new Key();
        $alientTechKey->key = 'twilio_sid';
        $alientTechKey->value = 'ACa66083ca269399421ba5c5d31d497bd9';
        $alientTechKey->subdealer_group_id = $subdealer->id;
        $alientTechKey->save();

        $alientTechKey = new Key();
        $alientTechKey->key = 'twilio_number';
        $alientTechKey->value = '+14059134112';
        $alientTechKey->subdealer_group_id = $subdealer->id;
        $alientTechKey->save();

        $alientTechKey = new Key();
        $alientTechKey->key = 'twilio_token';
        $alientTechKey->value = '130c5fe7d4c2d39fa76ab6c01dafa495';
        $alientTechKey->subdealer_group_id = $subdealer->id;
        $alientTechKey->save();

        $creditPriceAlreayd = Price::whereNull('subdealer_group_id')->where('label', 'credit_price')->first();
        $evcCreditPriceAlreayd = Price::whereNull('subdealer_group_id')->where('label', 'evc_credit_price')->first();

        $creditPrice = new Price();
        $creditPrice->label = 'credit_price';
        $creditPrice->value = $creditPriceAlreayd->value;
        $creditPrice->subdealer_group_id = $subdealer->id;
        $creditPrice->save();

        $evcCreditPrice = new Price();
        $evcCreditPrice->label = 'evc_credit_price';
        $evcCreditPrice->value = $evcCreditPriceAlreayd->value;
        $evcCreditPrice->subdealer_group_id = $subdealer->id;
        $evcCreditPrice->save();

        $testGroup = Group::where('name', 'Test')->first();

        $newVat24Group = new Group();
        $newVat24Group->name = 'VAT0';
        $newVat24Group->subdealer_group_id = $subdealer->id;
        $newVat24Group->tax = 0.0;
        $newVat24Group->discount = 0.0;
        $newVat24Group->raise = 0.0;
        $newVat24Group->bonus_credits = 0.0;
        $newVat24Group->slug = 'VAT0';
        $newVat24Group->stripe_payment_account_id = $testGroup->stripe_payment_account_id;
        $newVat24Group->paypal_payment_account_id = $testGroup->paypal_payment_account_id;
        $newVat24Group->save();

        $newVat24Group = new Group();
        $newVat24Group->name = 'VAT24';
        $newVat24Group->subdealer_group_id = $subdealer->id;
        $newVat24Group->tax = 24.0;
        $newVat24Group->discount = 0.0;
        $newVat24Group->raise = 0.0;
        $newVat24Group->bonus_credits = 0.0;
        $newVat24Group->slug = 'VAT24';
        $newVat24Group->stripe_payment_account_id = $testGroup->stripe_payment_account_id;
        $newVat24Group->paypal_payment_account_id = $testGroup->paypal_payment_account_id;
        $newVat24Group->save();

        $newVat24Group = new Group();
        $newVat24Group->name = 'NVAT0';
        $newVat24Group->subdealer_group_id = $subdealer->id;
        $newVat24Group->tax = 0.0;
        $newVat24Group->discount = 0.0;
        $newVat24Group->raise = 0.0;
        $newVat24Group->bonus_credits = 0.0;
        $newVat24Group->slug = 'NVAT0';
        $newVat24Group->stripe_payment_account_id = $testGroup->stripe_payment_account_id;
        $newVat24Group->paypal_payment_account_id = $testGroup->paypal_payment_account_id;
        $newVat24Group->save();

        $allEmailTemplates = EmailTemplate::whereNull('subdealer_group_id')->get();

        foreach($allEmailTemplates as $e){
            $emailTemplate = new EmailTemplate();
            $emailTemplate->name = $e->name;
            $emailTemplate->html = $e->html;
            $emailTemplate->subdealer_group_id = $subdealer->id;
            $emailTemplate->save();
        }

        $allMessageTemplates = MessageTemplate::whereNull('subdealer_group_id')->get();

        foreach($allMessageTemplates as $m){
            $messageTemplate = new MessageTemplate();
            $messageTemplate->name = $m->name;
            $messageTemplate->text = $m->text;
            $messageTemplate->subdealer_group_id = $subdealer->id;
            $messageTemplate->save();
        }

        return redirect()->route('subdealers-entity')->with(['success' => 'Subdealer added.']);

    }

}
