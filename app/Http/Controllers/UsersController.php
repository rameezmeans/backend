<?php

namespace App\Http\Controllers;

use App\Models\ChMessage;
use App\Models\Credit;
use App\Models\EngineersPermission;
use App\Models\File;
use App\Models\FrontEnd;
use App\Models\Group;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Ui\Presets\React;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('adminOnly');
    }

    public function changeEngineerPermission(Request $request){

        if(!Auth::user()->is_admin()){
            abort(404);
        }

        if($request->switchStatus == 'true'){
            $new = new EngineersPermission();
            $new->permission = $request->permission;
            $new->engineer_id = $request->engineer_id;
            $new->save();
        }
        else if($request->switchStatus == 'false'){
            EngineersPermission::where('permission', $request->permission)
            ->where('engineer_id', $request->engineer_id)->delete();
        }

        return response('permission updated', 200);
    }

    public function engineersPermissions($id){

        if(!Auth::user()->is_admin()){
            abort(404);
        }

        $engineer = User::findOrFail($id);
        
        return view('engineers.edit_permissions', ['engineer' => $engineer]);

    }

    public function getDays($strDateFrom, $strDateTo){  
        // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange = [];

    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo) {
            $iDateFrom += 86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
     }  

    public function getCountriesReport(Request $request) {

        if(isset($request->duration)) {

            $table1 = [];

            if($request->duration == 'today') {

                $countries = User::select('country', \DB::raw("count(id) as count"))
                ->groupby('country')
                ->orderBy('count', 'desc')
                ->where('test', 0)
                ->whereRaw('date(created_at) = curdate()')
                ->where('country', '!=' ,'Live Chat')
                ->where('front_end_id', $request->front_end)
                ->get();

            }
            else {

                $countries = User::select('country', \DB::raw("count(id) as count"))
                ->groupby('country')
                ->orderBy('count', 'desc')
                ->where('test', 0)
                ->whereDay('created_at', Carbon::yesterday())
                ->where('country', '!=' ,'Live Chat')
                ->where('front_end_id', $request->front_end)
                ->get();
            }

            foreach($countries as $country) {

                $temp = [];
                if($request->duration == 'today') {

                    $usersCount = User::where('country', $country->country)
                    ->whereRaw('date(created_at) = curdate()')
                    ->where('test','=', 0)
                    ->where('front_end_id', $request->front_end)->count();

                    $users = User::where('country', $country->country)
                    ->whereRaw('date(created_at) = curdate()')
                    ->where('test','=', 0)
                    ->where('front_end_id', $request->front_end)->get('id')->toArray();

                    $ids = [];
                    foreach($users as $u){
                        $ids []= $u['id'];
                    }
                    
                    $filesCount = File::whereIn('user_id', $ids)->count();
                    $creditsCount = (int) Credit::whereIn('user_id', $ids)
                    ->where('credits', '>', 0)->sum('credits');

                }
                else if($request->duration == 'yesterday') {

                    $usersCount = User::where('country', $country->country)
                    ->whereDate('created_at', Carbon::yesterday())
                    ->where('test','=', 0)
                    ->where('front_end_id', $request->front_end)->count();

                    $users = User::where('country', $country->country)
                    ->whereDate('created_at', Carbon::yesterday())
                    ->where('test','=', 0)
                    ->where('front_end_id', $request->front_end)->get('id')->toArray();

                    $ids = [];
                    foreach($users as $u){
                        $ids []= $u['id'];
                    }
                    
                    $filesCount = File::whereIn('user_id', $ids)->count();
                    $creditsCount = (int) Credit::whereIn('user_id', $ids)
                    ->where('credits', '>', 0)->sum('credits');

                }

                $temp[$country->country] = [$usersCount,$filesCount,$creditsCount];
                $table1[$country->country]= $temp[$country->country];

            }

            return view('groups.table',['frontend' => $request->front_end, 'table1' => $table1, 'duration' => $request->duration]);

        }

        if(isset($request->start)) {

                $startd = str_replace('/', '-', $request->start);
                $startDate = date('Y-m-d', strtotime($startd));

                $endd = str_replace('/', '-', $request->end);
                $endDate = date('Y-m-d', strtotime($endd));

                $datesArray = $this->getDays($startDate, $endDate);

                $countries = User::select('country', \DB::raw("count(id) as count"))
                ->groupby('country')
                ->orderBy('count', 'desc')
                ->where('test','=', 0)
                ->whereDate('created_at', '>=' , $startDate)
                ->whereDate('created_at', '<=' , $endDate)
                ->where('country', '!=' ,'Live Chat')
                ->where('front_end_id', $request->front_end)
                ->get();

                $table2 = [];

                foreach($countries as $country) {

                    $usersCount = User::where('country', $country->country)
                    ->whereDate('created_at', '>=' , $startDate)
                    ->whereDate('created_at', '<=' , $endDate)
                    ->where('test','=', 0)
                    ->where('front_end_id', $request->front_end)->count();

                    $users = User::where('country', $country->country)
                    ->whereDate('created_at', '>=' , $startDate)
                    ->whereDate('created_at', '<=' , $endDate)
                    ->where('test','=', 0)
                    ->where('front_end_id', $request->front_end)->get('id')->toArray();

                    $ids = [];
                    foreach($users as $u){
                        $ids []= $u['id'];
                    }
                    
                    $filesCount = File::whereIn('user_id', $ids)->count();
                    $creditsCount = (int) Credit::whereIn('user_id', $ids)
                    ->where('credits', '>', 0)->sum('credits');

                    $countsArray = [];
                    
                    foreach($datesArray as $d){
                        $counts = User::where('country', $country->country)
                        ->whereDate('created_at', '=' , $d)
                        ->where('test','=', 0)
                        ->where('front_end_id', $request->front_end)->count();

                        if($counts > 0){
                            $countsArray []= $counts;
                        }
                    }

                    dd($countsArray);

                    $temp[$country->country] = [$usersCount,$filesCount,$creditsCount];
                    $table2[$country->country]= $temp[$country->country];
                
                }

                return view('groups.table',['frontend' => $request->front_end,
                    'table2' => $table2,
                    'start' => $request->start,
                    'end' => $request->end]);

        }
    }

    public function countriesReport(){

        $frontends = FrontEnd::all();
        $countries = User::select('country')->groupby('country')->get();
        return view('groups.countries', ['countries' => $countries, 'frontends' => $frontends]);
    }

    public function Customers(){

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'view-customers')){


        $customers = get_customers();

        return view('groups.customers', ['customers' => $customers]);
        }
        
        else{
            abort(404);
        }
    } 

    public function addCustomer(Request $request){

        if(!Auth::user()->is_admin()){
            abort(404);
        }
        

        if($request->evc_customer_id){

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
                'evc_customer_id' => ['unique:users'],
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
                'password' => ['required', 'string', 'min:8'],
            ]);

        }

        $customerID = Role::where('name', 'customer')->first()->id;

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
        $customer->evc_customer_id = $request->evc_customer_id;
        $customer->group_id = $request->group_id;
        $customer->front_end_id = $request->front_end_id;
        $customer->role_id = $customerID;

        if($request->exclude_vat_check == 'on'){
            $customer->exclude_vat_check = 1;
        }
        else{
            $customer->exclude_vat_check = 0;
        }

        $customer->save();

        if($customer->evc_customer_id){

            try{

                $response = Http::get('https://evc.de/services/api_resellercredits.asp?apiid=j34sbc93hb90&username=161134&password=MAgWVTqhIBitL0wn&verb=addcustomer&customer='.$customer->evc_customer_id);

                $body = $response->body();

            }

            catch(ConnectionException $e){
                
            }

        }

        if($customer->group->bonus_credits > 0){
            $this->addCredits($customer->group->bonus_credits, $customer);
        }
        
       return redirect()->route('customers')->with(['success' => 'Customer added, successfully.']);
    }

    public function createCustomer(){

        if(!Auth::user()->is_admin()){
            abort(404);
        }

        $groups = Group::all();
        $frontends = FrontEnd::all();
        return view('groups.create_edit_customers', ['groups' => $groups, 'frontends' => $frontends]);
    } 

    public function editCustomer($id){

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-customers')){

            $customer = User::findOrFail($id);
            $groups = Group::all();
            $frontends = FrontEnd::all();
            return view('groups.create_edit_customers', ['customer' => $customer, 'groups' => $groups, 'frontends' => $frontends]);
        }
        else{
            abort(404);
        }
    } 

    public function updateCustomer(Request $request){

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'edit-customers')){
            
        $anyOtherUserWithSameUniqueEVCCustomerID = User::where('evc_customer_id', $request->evc_customer_id)
        ->where('id','!=', $request->id)
        ->first();

        if($anyOtherUserWithSameUniqueEVCCustomerID && $request->evc_customer_id){

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
                'evc_customer_id' => ['unique:users'],
                // 'email' => ['required', 'string', 'email', 'max:255'],
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
                // 'email' => ['required', 'string', 'email', 'max:255'],
            ]);
        }


        $customer = User::findOrFail($request->id);

        $customer->name = $request->name;
        

        if($request->password){
            $customer->password = Hash::make(trim($request->password));
        }

        // $customer->email = trim($request->email);
        $customer->phone = $request->phone;
        $customer->language = $request->language;
        $customer->address = $request->address;
        $customer->zip = $request->zip;
        $customer->city = $request->city;
        $customer->country = $request->country;
        $customer->status = $request->status;
        $customer->company_name = $request->company_name;
        $customer->company_id = $request->company_id;
        $customer->evc_customer_id = $request->evc_customer_id;
        $customer->group_id = $request->group_id;
        $customer->front_end_id = $request->front_end_id;
        // $customer->elorus_id = null;

        if($request->exclude_vat_check == 'on'){
            $customer->exclude_vat_check = 1;
        }
        else{
            $customer->exclude_vat_check = 0;
        }

        $customer->save();

        if($customer->evc_customer_id){

            try{

                $response = Http::get('https://evc.de/services/api_resellercredits.asp?apiid=j34sbc93hb90&username=161134&password=MAgWVTqhIBitL0wn&verb=addcustomer&customer='.$customer->evc_customer_id);

                $body = $response->body();

            }

            catch(ConnectionException $e){
                
            }

        }

        if($customer->group->bonus_credits > 0){
            $this->addCredits($customer->group->bonus_credits, $customer);
        }

        if($customer->elorus_id){
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.elorus.com/v1.1/contacts/'.$customer->elorus_id.'/');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

            curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"first_name\":\"$customer->name\", \"last_name\":\"\", \"vat_number\":\"$customer->company_id\",\"company\":\"$customer->company_name\",\"active\":true, \"is_supplier\":false}");

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Token 32fd4c0b90ac267da4c548ea4410b126db2eaf53';
            $headers[] = 'X-Elorus-Organization: 1357060486331368800';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);

        }

        return redirect()->route('customers')->with(['success' => 'Customer updated, successfully.']);
    }
    else{
        abort(404);
    }

    }

    public function addCredits($credits, $customer){

        if(!Auth::user()->is_admin()){
            abort(404);
        }

        $credit = new Credit();
        $credit->credits = $credits;
        $credit->user_id = $customer->id;
        $credit->stripe_id = NULL;
        $credit->price_payed = 0;
        $credit->invoice_id = 'Admin-'.mt_rand(1000,9999);

        if($customer->test == 1){
            $credit->test = 1;
        }
        
        $credit->save();
    }

    public function addEngineer(Request $request){

        if(!Auth::user()->is_admin()){
            abort(404);
        }
        
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

        $engineerID = Role::where('name', 'engineer')->first()->id;
        $engineer->role_id = $engineerID;

        $engineer->save();

       return redirect()->route('engineers')->with(['success' => 'Engineer added, successfully.']);

    }

    public function updateEngineer(Request $request){

        if(!Auth::user()->is_admin()){
            abort(404);
        }

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

       return redirect()->route('engineers')->with(['success' => 'Engineer Updated, successfully.']);

    }

    public function createEngineer(){

        if(!Auth::user()->is_admin()){
            abort(404);
        }

        return view('engineers.create_edit_engineers');
    }

    public function editEngineer($id){

        if(!Auth::user()->is_admin()){
            abort(404);
        }

        $engineer = User::findOrfail($id);
        return view('engineers.create_edit_engineers', ['engineer' => $engineer]);
    }

    public function Engineers(){

        if(!Auth::user()->is_admin()){
            abort(404);
        }

        $engineers = get_engineers();
        return view('engineers.engineers', ['engineers' => $engineers]);
    } 

    public function deleteCustomer(Request $request){

        if(Auth::user()->is_admin() || get_engineers_permission(Auth::user()->id, 'delete-customers')){
            

            $customer = User::findOrFail($request->id);
            $customer->delete();
            $request->session()->put('success', 'Customer deleted, successfully.');
        
        }
        else{
            return 'not deleteed';
        }
    }

    public function deleteEngineer(Request $request){

        if(!Auth::user()->is_admin()){
            abort(404);
        }

        $engineer = User::findOrFail($request->id);
        $engineer->delete();
        $request->session()->put('success', 'Engineer deleted, successfully.');
    }
}
