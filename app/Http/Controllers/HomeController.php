<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\EngineerFileNote;
use App\Models\File;
use App\Models\FrontEnd;
use App\Models\ReminderManager;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    private $manager;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $reminderManagers = ReminderManager::whereNull('subdealer_group_id')->get();
        $manager = [];
        foreach($reminderManagers as $row){
            $temp[$row->type] = $row->active;
            $manager = array_merge($manager, $temp);
        }

        $this->manager = $manager;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $engineers = get_engineers();
        $customersForECUTech = get_customers(1);
        $customersForTuningX = get_customers(2);
        $engineersCount = sizeof($engineers);

        $frontends = FrontEnd::all();

        return view('home', [ 

            'frontends' => $frontends,
            'engineers' => $engineers,
            'engineersCount' => $engineersCount, 
            'customersForECUTech' => $customersForECUTech,
            'customersForTuningX' => $customersForTuningX,
        
        ]);
    }

    public function getFrontendData(Request $request){

        $customerCount = sizeof(get_customers($request->frontend_id));
        $frontEndID = $request->frontend_id;
        // today

        $totalFileCountToday = File::where('front_end_id', $request->frontend_id)
        ->whereRaw('date(created_at) = curdate()')
        ->where('test', 0)
        ->count();

        $autotunedFileCountToday = File::where('checking_status', 'completed')
        ->where('front_end_id', $request->frontend_id)
        ->where('test', 0)
        ->whereRaw('date(created_at) = curdate()')
        ->count();

        $AvgRTToday = 0;

        $totalTime = File::where('checking_status', 'completed')
        ->where('front_end_id', $request->frontend_id)
        ->where('test', 0)
        ->whereRaw('date(created_at) = curdate()')
        ->sum('response_time');

        if($totalTime != 0){
            $AvgRTToday = round( $totalTime / $autotunedFileCountToday , 2)." sec" ;
        }

        //// 7 days

        $date = Carbon::now()->subDays(7);

        $totalsevenDaysCount = File::where('front_end_id', $request->frontend_id)
        ->where('created_at', '>=', $date)
        ->where('test', 0)
        ->count();
        
        $autotunedFileCountSevendays = File::where('checking_status', 'completed')
        ->where('front_end_id', $request->frontend_id)
        ->where('test', 0)
        ->where('created_at', '>=', $date)
        ->count();

        $AvgRTSevendays = 0;

        $totalTimeSevendays = File::where('checking_status', 'completed')
        ->where('front_end_id', $request->frontend_id)
        ->where('test', 0)
        ->whereRaw('date(created_at) = curdate()')
        ->sum('response_time');

        if($totalTimeSevendays != 0){
            $AvgRTSevendays = round( $totalTimeSevendays / $autotunedFileCountSevendays , 2)." sec" ;
        }

        // 30 days 

        $date = Carbon::now()->subDays(30);

        $total30DaysCount = File::where('front_end_id', $request->frontend_id)
        ->where('test', 0)
        ->where('created_at', '>=', $date)
        ->count();

        $autotunedFileCount30days = File::where('checking_status', 'completed')
        ->where('test', 0)
        ->where('front_end_id', $request->frontend_id)
        ->where('created_at', '>=', $date)
        ->count();

        $AvgRT30days = 0;

        $totalTime30days = File::where('checking_status', 'completed')
        ->where('test', 0)
        ->where('front_end_id', $request->frontend_id)
        ->whereRaw('date(created_at) = curdate()')
        ->sum('response_time');

        if($totalTime30days != 0){
            $AvgRT30days = round( $totalTime30days / $autotunedFileCount30days , 2)." sec" ;
        }

        // 365 days 
        
        $date = Carbon::now()->subDays(365);

        $total365DaysCount = File::where('front_end_id', $request->frontend_id)
        ->where('test', 0)
        ->where('created_at', '>=', $date)
        ->count();

        $autotunedFileCount365days = File::where('checking_status', 'completed')
        ->where('test', 0)
        ->where('front_end_id', $request->frontend_id)
        ->where('created_at', '>=', $date)
        ->count();

        $AvgRT365days = 0;

        $totalTime365days = File::where('checking_status', 'completed')
        ->where('test', 0)
        ->where('front_end_id', $request->frontend_id)
        ->whereRaw('date(created_at) = curdate()')
        ->sum('response_time');

        if($totalTime365days != 0){
            $AvgRT365days = round( $totalTime365days / $autotunedFileCount365days , 2)." sec" ;
        }

        $customerID = Role::where('name', 'customer')->first()->id;

        // $topCountriesObj = User::where('is_customer', 1)
        // ->where('front_end_id', $request->frontend_id)
        // ->groupBy('country')
        // ->selectRaw('count(*) as count,country')
        // ->get();

        $customerRole = Role::where('name','customer')->first();

        $topCountriesObj = User::where('role_id', $customerRole->id)
        ->where('front_end_id', $frontEndID)
        ->whereNotIN('id', [65,80])
        ->groupBy('country')
        ->where('test', 0)
        ->selectRaw('count(*) as count,country')
        ->get();

        foreach($topCountriesObj as $t){
            $temp = [];
            $temp ['country'] = $t->country;
            $temp ['count'] = $t->count;
            $topCountries []= $temp;
        }

        usort($topCountries, array($this, 'sorterc'));

        $countryTable = '<table class="table table-condensed table-hover"><tbody>';
        foreach($topCountries as $country){
        $countryTable .= '<tr><td class="w-10">
        <span style="font-size: 20px;">'.getFlags($country['country']).'</span>
        </td>
            <td class="font-montserrat all-caps fs-12 w-50">'.code_to_country($country['country']).'</td>
            <td class="w-25">
                <span class="font-montserrat fs-18">'.$country['count'].'</span>
            </td></tr>';
        }

        $countryTable .= '</tbody></table>';

        $topBrandsObj = File::groupBy('brand')
        ->where('front_end_id', $request->frontend_id)
        ->where('test', 0)
        ->selectRaw('count(*) as count,brand')
        ->get();

        $topBrands = [];

        foreach($topBrandsObj as $t){
            $temp = [];
            $temp ['brand'] = $t->brand;
            $temp ['count'] = $t->count;
            $topBrands []= $temp;
        }
        
        if(sizeof($topBrands) != 0){
            usort($topBrands, array($this, 'sorterc'));
            $topBrands = array_slice($topBrands, 0, 5);
        }

        $brandsTable = '<table class="table table-condensed table-hover"><tbody>';

        foreach($topBrands as $brand){
            $brandsTable .= '<tr><td class="w-10">
            <img src="'.get_image_from_brand($brand['brand']).'" style="width: 60%;">
            </td>
            <td class="font-montserrat all-caps fs-12 w-50">'.$brand['brand'].'</td>
            <td class="w-25">
                <span class="font-montserrat fs-18">'.$brand['count'].'</span>
            </td></tr>';
        }

        $brandsTable .= '</tbody></table>';

        $customerOptions = '<option value="all_customers">All Customers</option>';

        $customers = get_customers($request->frontend_id);

        foreach($customers as $customer){
            $customerOptions .= '<option value="'.$customer->id.'">'.$customer->name.'</option>';
        }

        return response()->json([
            'customerCount' => $customerCount,
            'AvgRTToday' => $AvgRTToday,
            'autotunedFileCountToday' => $autotunedFileCountToday,
            'totalFileCountToday' => $totalFileCountToday,
            'AvgRTSevendays' => $AvgRTSevendays,
            'autotunedFileCountSevendays' => $autotunedFileCountSevendays,
            'totalsevenDaysCount' => $totalsevenDaysCount,
            'AvgRT30days' => $AvgRT30days,
            'autotunedFileCount30days' => $autotunedFileCount30days,
            'total30DaysCount' => $total30DaysCount,
            'AvgRT365days' => $AvgRT365days,
            'autotunedFileCount365days' => $autotunedFileCount365days,
            'total365DaysCount' => $total365DaysCount,
            'countryTable' => $countryTable,
            'brandsTable' => $brandsTable,
            'customerOptions' => $customerOptions,
        ]);

    }

    public function sorter(array $a, array $b) {
        return $a['credits'] < $b['credits'];
    }

    public function sorterc(array $a, array $b) {
        return $a['count'] < $b['count'];
    }


    public function getResponseTimeChart(Request $request){
        
        $averageTimes = [];
        $engineersA = [];

        $showAverage = false;
        $average = 0;

        if($request->reponse_engineer == 'all_engineers'){
            $engineers = get_engineers();

            foreach($engineers as $engineer){

                $totalResponseTime = File::where('assigned_to', $engineer->id)
                ->where('test', 0)
                ->where('front_end_id', $request->frontend_id)
                ->sum('response_time');
                
                $count = File::where('assigned_to', $engineer->id)
                ->where('test', 0)
                ->where('front_end_id', $request->frontend_id)
                ->count();

                if($count != 0){
                    $averageTime = $totalResponseTime/$count;
                }
                else{
                    $averageTime = 0;
                }
                $averageTimes []= $averageTime;
                $engineersA []= $engineer->name;
            }
        }
        else{

            $showAverage = true;
            $engineer = User::FindOrFail($request->reponse_engineer);
            $totalResponseTime = File::where('assigned_to', $engineer->id)
            ->where('test', 0)
            ->where('front_end_id', $request->frontend_id)
            ->sum('response_time');
            $count = File::where('assigned_to', $engineer->id)
            ->where('test', 0)
            ->where('front_end_id', $request->frontend_id)
            ->count();

            if($count != 0){
                $averageTime = $totalResponseTime/$count;
            }
            else{
                $averageTime = 0;
            }

            if($averageTime != 0){
                $average = \Carbon\CarbonInterval::seconds($averageTime)->cascade()->forHumans();
            }
            else{
                $average = 'No Time';
            }
            $averageTimes []= $averageTime;
            $engineersA []= $engineer->name;
        }

        $graph = [];
        $graph['y_axis']= $averageTimes;
        $graph['x_axis']= $engineersA ;
        $graph['user_average']= $average;
        $graph['show_avarage']= $showAverage;
        $graph['has_files']= true;
        $graph['label']= 'Response Time';
        
        return response()->json(['graph' => $graph]);

    }   

    public function getSupportChart(Request $request){

        $graph = [];

        if(!$request->starts){
            $min = DB::table('engineer_file_notes')->select('created_at')->orderBy('created_at', 'asc')->first();
            $start = $min->created_at;
        }
        else{
            $start = $request->starts;
            $date = str_replace('/', '-', $start);
            $start = date('Y-m-d', strtotime($date));
        }

        if(!$request->ends){
            $max = DB::table('engineer_file_notes')->select('created_at')->orderBy('created_at', 'desc')->first();
            $end = $max->created_at;
        }
        else{
            $end = $request->ends;
            $date = str_replace('/', '-', $end);
            $end = date('Y-m-d', strtotime($date));
        }

        $weekRange = $this->createDateRangeArray($start, $end);

        $weekCount = [];
        $files = [];
        $fileIDs = [];
        $grandTotal = 0;
        foreach($weekRange as $r) {
            $date = DateTime::createFromFormat('d/m/Y', $r);
            $day = $date->format('d');
            $month = $date->format('m');

            if($request->support_engineer == "all_engineers"){
                $grandTotal += EngineerFileNote::whereMonth('engineer_file_notes.created_at',$month)
                ->join('files', 'files.id', 'engineer_file_notes.file_id')
                ->where('files.front_end_id', $request->frontend_id)
                ->whereDay('engineer_file_notes.created_at',$day)->count();

                $weekCount []= EngineerFileNote::whereMonth('engineer_file_notes.created_at',$month)
                ->join('files', 'files.id', 'engineer_file_notes.file_id')
                ->where('files.front_end_id', $request->frontend_id)
                ->whereDay('engineer_file_notes.created_at',$day)->count();
            }
            else {
                $grandTotal += EngineerFileNote::join('files', 'files.id', 'engineer_file_notes.file_id')
                ->where('files.front_end_id', $request->frontend_id)
                ->where('files.assigned_to', $request->support_engineer)
                ->whereMonth('engineer_file_notes.created_at',$month)
                ->whereDay('engineer_file_notes.created_at',$day)
                ->count();
                
                $weekCount []= EngineerFileNote::join('files', 'files.id', 'engineer_file_notes.file_id')
                ->where('files.front_end_id', $request->frontend_id)
                ->where('files.assigned_to', $request->support_engineer)
                ->whereMonth('engineer_file_notes.created_at',$month)
                ->whereDay('engineer_file_notes.created_at',$day)
                ->count();
            }
        }

        $totalEngineers = sizeof(get_engineers());

        $avgTotal = EngineerFileNote::count() / $totalEngineers;

        $filesToList = [];
        foreach(array_unique($fileIDs) as $file){
            $filesToList []= File::findOrFail($file);
        }

        $graph = [];
        $graph['x_axis']= $weekRange;
        $graph['y_axis']= $weekCount;
        $graph['total_requests']= $grandTotal;
        $graph['avg_requests']= round($avgTotal, 2);
        $graph['label']= 'Customer Support';
        
        return response()->json(['graph' => $graph]);

    }

    public function getCreditsChart(Request $request){

        $graph = [];

        $start = NULL;
        $end = NULL;
        if(!$request->startc){
            $min = DB::table('credits')
            
            ->where('credits', '>', 0)
            ->where('test', 0)
            ->where('front_end_id', $request->frontend_id)
            ->select('created_at')
            ->orderBy('created_at', 'asc')->first();
            
            if($min)
                $start = $min->created_at;
        }
        else{
            $start = $request->startc;
            $date = str_replace('/', '-', $start);
            $start = date('Y-m-d', strtotime($date));
        }

        if(!$request->endc){

            $max = DB::table('credits')
            ->where('credits', '>', 0)
            ->where('test', 0)
            ->select('created_at')
            ->orderBy('created_at', 'desc')->first();
            
            if($max)
                $end = $max->created_at;
        }
        else{
            $end = $request->endc;
            $date = str_replace('/', '-', $end);
            $end = date('Y-m-d', strtotime($date));
        }
        
        if($start == NULL && $end == NULL){
            $graph = [];
            $graph['x_axis']= 0;
            $graph['y_axis']= 0;
            $graph['credits']= "";
            $graph['has_credits']= false;
            $graph['label']= 'Credits';
            
            return response()->json(['graph' => $graph]);
        }

        $weekRange = $this->createDateRangeArray($start, $end);

        $weekCount = [];
        foreach($weekRange as $r) {
            $date = DateTime::createFromFormat('d/m/Y', $r);
            $day = $date->format('d');
            $month = $date->format('m');
            
            if($request->customer_credits == "all_customers"){

                $weekCount []= Credit::whereMonth('credits.created_at',$month)
                ->join('users', 'users.id', 'credits.user_id')
                ->where('users.front_end_id', $request->frontend_id)
                ->where('credits', '>', 0)
                ->whereDay('credits.created_at',$day)
                ->where('credits.test', 0)
                ->whereDate('credits.created_at', '>' ,'2023-01-17')
                ->sum('credits');

            }
            else{
                
                $weekCount []= Credit::where('user_id', $request->customer_credits)
                ->whereMonth('created_at',$month)
                ->whereDay('created_at',$day)
                ->where('credits', '>', 0)
                ->where('test', 0)
                ->sum('credits');
            }
        }

        $grandTotal = Credit::where('credits', '>', 0)
        ->where('test', 0)
        ->where('front_end_id', $request->frontend_id)
        ->sum('credits');

        $customers = sizeof(get_customers($request->frontend_id));

        $avgTotal = $grandTotal / $customers;

        $topCustomers = Credit::where('credits', '>', 0)
        ->where('credits.test', 0)
        ->join('users', 'users.id', 'credits.user_id')
        ->where('users.front_end_id', $request->frontend_id)
        ->groupBy('user_id')
        ->selectRaw('user_id,sum(credits) as sum')
        ->get();

        $topUserCredits = [];
        foreach($topCustomers as $c){
            $temp = [];
            $temp ['user'] = User::findOrFail($c->user_id)->name;
            $temp ['credits'] = $c->sum;
            $topUserCredits []= $temp;
        }

        usort($topUserCredits, array($this, 'sorter'));

        $count = 1;
        $top5 = [];
        foreach($topUserCredits as $c){
            if($count > 5){
                break;
            }
            $top5 []= $c;
            $count++;
        }

        $customerTab = '';

        foreach($top5 as $top){
        $customerTab .= '<div class="col-lg-3 col-md-12 b-a b-grey m-r-2 m-b-10">
                    <h4 class="bold no-margin">'.$top['credits'].'</h4>
                    <p class="small no-margin">'.$top['user'].'</p>
                  </div>';
        }

        $graph = [];
        $graph['x_axis']= $weekRange;
        $graph['y_axis']= $weekCount;
        $graph['customerTab']= $customerTab;
        $graph['total_credits']= $grandTotal;
        $graph['avg_credits']= round($avgTotal, 2);
        $graph['label']= 'Credits';
        
        return response()->json(['graph' => $graph]);
    }

    public function getFilesChart(Request $request){
        
        $graph = [];

        if(!$request->start){
            $min = DB::table('files')->select('created_at')
            ->where('test', 0)
            ->where('front_end_id', $request->frontend_id)->orderBy('created_at', 'asc')->first();
            $start = $min->created_at;
        }
        else{
            $start = $request->start;
            $date = str_replace('/', '-', $start);
            $start = date('Y-m-d', strtotime($date));
        }

        if(!$request->end){
            $max = DB::table('files')
            ->where('test', 0)
            ->select('created_at')->where('front_end_id', $request->frontend_id)->orderBy('created_at', 'desc')->first();
            $end = $max->created_at;
        }
        else{
            $end = $request->end;
            $date = str_replace('/', '-', $end);
            $end = date('Y-m-d', strtotime($date));
        }

        $weekRange = $this->createDateRangeArray($start, $end);

        $days = count($weekRange);

        $weekCount = [];
        foreach($weekRange as $r){
            $date = DateTime::createFromFormat('d/m/Y', $r);
            $day = $date->format('d');
            $month = $date->format('m');
            
            if($request->engineer_files == "all_engineers"){
                $weekCount []= File::whereMonth('created_at',$month)->where('test', 0)->where('front_end_id', $request->frontend_id)->whereDay('created_at',$day)->count();
            }
            else{
                $weekCount []= File::where('assigned_to', $request->engineer_files)->where('test', 0)->where('front_end_id', $request->frontend_id)->whereMonth('created_at',$month)->whereDay('created_at',$day)->count();
            }
        }

        $totalEngineers = sizeof(get_engineers());

        if($request->engineer_files == "all_engineers"){
            $files = File::whereBetween('created_at', array($start, $end))->where('test', 0)->where('front_end_id', $request->frontend_id)->where('is_credited', 1)->get();
            $totalFiles = File::whereBetween('created_at', array($start, $end))->where('test', 0)->where('front_end_id', $request->frontend_id)->where('is_credited', 1)->count();
        }
        else{
            $files = File::where('assigned_to', $request->engineer_files)->where('test', 0)->where('front_end_id', $request->frontend_id)->whereBetween('created_at', array($start, $end))->where('is_credited', 1)->get();
            $totalFiles = File::where('assigned_to', $request->engineer_files)->where('test', 0)->where('front_end_id', $request->frontend_id)->whereBetween('created_at', array($start, $end))->where('is_credited', 1)->count();
        }

        $grandTotal = File::count();
        $avgFiles = $totalFiles / $totalEngineers;
        $avgFilesPerDay = $totalFiles / $days;

        $graph = [];
        $graph['x_axis']= $weekRange;
        $graph['y_axis']= $weekCount ;
        $graph['total_files']= $totalFiles;
        $graph['avg_files']= round($avgFiles, 2);
        $graph['avg_files_per_day']= round($avgFilesPerDay, 2);
        $graph['label']= 'Files';
        
        return response()->json(['graph' => $graph]);
    }

    public function getAutotunnedFilesChart(Request $request){

        $frontEndID = $request->frontend_id;
        $graph = [];

        if(!$request->start){
            $min = DB::table('files')->
            select('created_at')
            ->orderBy('created_at', 'asc')
            ->where('front_end_id', $frontEndID)
            ->first();
            $start = $min->created_at;
        }
        else{
            $start = $request->start;
            $date = str_replace('/', '-', $start);
            $start = date('Y-m-d', strtotime($date));
        }

        if(!$request->end){
            $max = DB::table('files')->select('created_at')
            ->where('front_end_id', $frontEndID)
            ->where('test', 0)->orderBy('created_at', 'desc')
            ->first();
            $end = $max->created_at;
        }
        else{
            $end = $request->end;
            $date = str_replace('/', '-', $end);
            $end = date('Y-m-d', strtotime($date));
        }

        $weekRange = $this->createDateRangeArray($start, $end);

        $weekCount = [];
        foreach($weekRange as $r){
            $date = DateTime::createFromFormat('d/m/Y', $r);
            $day = $date->format('d');
            $month = $date->format('m');
            $weekCount []= File::whereMonth('created_at',$month)
            ->where('test', 0)
            ->where('front_end_id', $request->frontend_id)
            ->where('checking_status', 'completed')
            ->whereDay('created_at',$day)->count();
            
        }
        
        $totalAutoTunedFiles = File::whereBetween('created_at', array($start, $end))
        ->where('test', 0)
        ->where('front_end_id', $request->frontend_id)
        ->where('checking_status', 'completed')
        ->where('is_credited', 1)->count();

        $totalFiles = File::whereBetween('created_at', array($start, $end))
        ->where('test', 0)
        ->where('front_end_id', $request->frontend_id)
        ->where('is_credited', 1)->count();

        $totalFilesManual = File::whereBetween('created_at', array($start, $end))
        ->where('test', 0)
        ->where('front_end_id', $request->frontend_id)
        ->whereNot('checking_status', 'completed')
        ->where('is_credited', 1)->count();
        
        $graph = [];
        $graph['x_axis']= $weekRange;
        $graph['y_axis']= $weekCount ;
        $graph['total_autotuned_files']= $totalAutoTunedFiles;
        $graph['total_manual_files']= $totalFilesManual;
        $graph['total_files']= $totalFiles;
        $graph['label']= 'Files';
        
        return response()->json(['graph' => $graph]);
    }

    function createDateRangeArray($strDateFrom,$strDateTo){

        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.
        // could test validity of dates here but I'm already doing
        // that in the main script
    
        $aryRange = [];
    
        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));
    
        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('d/m/y', $iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date('d/m/y', $iDateFrom));
            }
        }
        return $aryRange;
    }
}
